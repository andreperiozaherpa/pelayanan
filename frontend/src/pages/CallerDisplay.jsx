import { useState, useEffect, useRef, useCallback, useMemo, memo } from 'react'
import { WindowUnfullscreen } from '../../wailsjs/runtime'
import { GetConfig } from '../../wailsjs/go/main/App'
import { useWebSocket } from '../hooks/useWebSocket'
import { useWailsAudioQueue } from '../hooks/useWailsAudioQueue'
import { DisplayGetHistory } from '../services/api'
import {
  isFirebaseEnabled,
  subscribeDisplaySettings,
  subscribeCurrentCall,
  subscribeActiveCounters,
  subscribeRecentHistory,
  DISPLAY_DEFAULT_SETTINGS,
} from '../services/firebase'

/* ===== Helpers ===== */
function getYouTubeId(url) {
  const m = String(url || '').match(/(?:youtu\.be\/|v\/|u\/\w\/|embed\/|shorts\/|watch\?v=|\&v=)([A-Za-z0-9_-]{11})/)
  return m ? m[1] : null
}

function formatTime(d) {
  const p = n => String(n).padStart(2, '0')
  return `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`
}

const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
const DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']

function formatDate(d) {
  return `${DAYS[d.getDay()]}, ${d.getDate()} ${MONTHS[d.getMonth()]} ${d.getFullYear()}`
}

/* ===== Riwayat antrian: sumber data = API (hari ini), Firebase hanya realtime ===== */
function tsToMs(ts) {
  const n = Number(ts) || 0
  return n < 1e12 ? n * 1000 : n
}

function isToday(ts) {
  if (!ts) return false
  const d = new Date(tsToMs(ts))
  const now = new Date()
  return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth() && d.getDate() === now.getDate()
}

function normalizeHistoryItem(item) {
  return {
    queue_number: item.queue_number,
    gerai_name: item.gerai_name || '-',
    status: item.status || 'Selesai',
    timestamp: Number(item.timestamp) || 0,
  }
}

function historyKey(h) {
  return `${h.queue_number}|${h.status}`
}

function mergeHistory(prev, incoming, limit = 20) {
  const seen = new Set()
  const out = []
  for (const item of [...prev, ...incoming]) {
    if (!item) continue
    const h = normalizeHistoryItem(item)
    if (!isToday(h.timestamp)) continue
    const key = historyKey(h)
    if (seen.has(key)) continue
    seen.add(key)
    out.push(h)
  }
  return out.sort((a, b) => b.timestamp - a.timestamp).slice(0, limit)
}

/* ===== Chime dipindah ke useWailsAudioQueue (engine web) / Go backend ===== */

/* Panel video YouTube (via relay origin HTTP) atau banner fallback.
   Di-memo agar tidak re-render saat status lain berubah (menghemat CPU). */
const MediaPanel = memo(function MediaPanel({ relayUrl, title, subtitle }) {
  if (relayUrl) {
    return (
      <div className="dc-youtube-isolate">
        <iframe
          className="dc-youtube-frame"
          src={relayUrl}
          title="YouTube Video Display"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          referrerPolicy="strict-origin-when-cross-origin"
          allowFullScreen
        />
      </div>
    )
  }
  return (
    <div className="dc-media-fallback">
      <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
        <polygon points="23 7 16 12 23 17 23 7" />
        <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
      </svg>
      <h4>{title || 'SIBERUGO MPP TUBABA'}</h4>
      <p>{subtitle || 'Selamat Datang di Mal Pelayanan Publik Tulang Bawang Barat'}</p>
      <span>Silakan mengambil nomor antrian di Anjungan</span>
    </div>
  )
})

/* Jam & tanggal realtime. Diisolasi (memo) agar update tiap detik hanya
   me-render ulang jam itu sendiri, tidak menyentuh parent/iframe/loket. */
const DisplayClock = memo(function DisplayClock() {
  const [now, setNow] = useState(() => new Date())

  useEffect(() => {
    const id = setInterval(() => setNow(new Date()), 1000)
    return () => clearInterval(id)
  }, [])

  return (
    <div className="dc-header-right">
      <div className="dc-clock">{formatTime(now)}</div>
      <div className="dc-date">{formatDate(now)}</div>
    </div>
  )
})

/* ===== Halaman Utama ===== */
export default function CallerDisplay() {
  const [settings, setSettings] = useState(DISPLAY_DEFAULT_SETTINGS)
  const [current, setCurrent] = useState(null)
  const [counters, setCounters] = useState([])
  const [history, setHistory] = useState([])
  const [showHint, setShowHint] = useState(true)

  const fbEnabled = useMemo(isFirebaseEnabled, [])
  const fbHistoryRef = useRef([])
  const apiHistoryLoaded = useRef(false)
  const fbSnapshotTaken = useRef(false)
  const historyFallback = useRef(false)
  const currentCallSnapshotTaken = useRef(false)

  const { engine, enqueue, unlock } = useWailsAudioQueue({
    tts: settings.tts,
    chime: settings.chime_sound,
  })

  /* Audio: tanpa popup unlock — auto-unlock saat engine siap (engine web saja,
     engine 'go' memutar lewat OS sehingga tidak butuh unlock) */
  useEffect(() => {
    if (engine === 'web') unlock()
  }, [engine, unlock])

  /* Escape → keluar fullscreen */
  useEffect(() => {
    function onKeyDown(e) { if (e.key === 'Escape') WindowUnfullscreen() }
    window.addEventListener('keydown', onKeyDown)
    return () => window.removeEventListener('keydown', onKeyDown)
  }, [])

  useEffect(() => {
    const t = setTimeout(() => setShowHint(false), 5000)
    return () => clearTimeout(t)
  }, [])

  /* ===== Audio queue terpadu (Go/Web) — dipindah ke useWailsAudioQueue ===== */

  /* ===== Riwayat antrian: sumber data = API (hari ini); Firebase hanya menambah entry baru realtime ===== */
  useEffect(() => {
    let cancelled = false
    const load = async () => {
      try {
        const data = await DisplayGetHistory()
        if (cancelled) return
        const items = Array.isArray(data) ? data : []
        apiHistoryLoaded.current = true
        setHistory(() => mergeHistory([], items))
      } catch {
        if (cancelled) return
        // API tidak tersedia → fallback ke data Firebase yang terakhir diketahui
        apiHistoryLoaded.current = true
        historyFallback.current = true
        setHistory(() => mergeHistory([], fbHistoryRef.current))
      }
    }
    load()
    const id = setInterval(load, 5 * 60 * 1000)
    return () => {
      cancelled = true
      clearInterval(id)
    }
  }, [])

  /* ===== Firebase = websocket realtime: hanya menambahkan nomor tiket baru ===== */
  useEffect(() => {
    if (!fbEnabled) return
    const offs = [
      subscribeDisplaySettings(setSettings),
      subscribeCurrentCall((c) => {
        // Snapshot awal current_call adalah panggilan lama — abaikan agar layar
        // baru dibuka tidak menampilkan panggilan sebelum FO/gerai memanggil.
        if (!currentCallSnapshotTaken.current) {
          currentCallSnapshotTaken.current = true
          return
        }
        setCurrent(c)
        enqueue(c)
      }),
      subscribeActiveCounters(setCounters),
      subscribeRecentHistory((list) => {
        const items = Array.isArray(list) ? list : []
        const isSnapshot = !fbSnapshotTaken.current
        fbSnapshotTaken.current = true

        if (isSnapshot) {
          // Snapshot awal = baseline; daftar sudah terisi dari API.
          if (apiHistoryLoaded.current && historyFallback.current) {
            setHistory(prev => mergeHistory(prev, items))
          }
          fbHistoryRef.current = items
          return
        }

        if (!apiHistoryLoaded.current) return

        // Real-time: tambahkan tiket baru & perbarui status tiket yang sudah ada
        setHistory(prev => {
          const byNumber = new Map(prev.map(h => [h.queue_number, h]))
          for (const item of items) {
            if (!item) continue
            const h = normalizeHistoryItem(item)
            if (!isToday(h.timestamp)) continue
            byNumber.set(h.queue_number, h)
          }
          return [...byNumber.values()].sort((a, b) => b.timestamp - a.timestamp).slice(0, 20)
        })
      }),
    ]
    return () => offs.forEach((off) => off?.())
  }, [fbEnabled, enqueue])

  /* ===== Fallback WebSocket (saat Firebase nonaktif) ===== */
  const handleMessage = useCallback((msg) => {
    if (msg.type !== 'display:call') return
    const data = msg.payload
    const parsed = typeof data === 'string' ? JSON.parse(data) : data
    const call = {
      queue_number: parsed.nomor,
      gerai_name: parsed.tujuan,
      agency: parsed.agency,
      service_type: parsed.service_type,
      timestamp: parsed.timestamp || Date.now(),
    }
    setCurrent(call)
    setHistory(prev => mergeHistory(prev, [{ queue_number: call.queue_number, gerai_name: call.gerai_name, status: 'Dipanggil', timestamp: call.timestamp }]))
    enqueue(call)
  }, [enqueue])

  const { subscribe } = useWebSocket(handleMessage)
  useEffect(() => {
    if (fbEnabled) return
    subscribe('display')
  }, [subscribe, fbEnabled])

  /* ===== Gaya dari pengaturan warna ===== */
  const colors = settings.colors || DISPLAY_DEFAULT_SETTINGS.colors
  const pageStyle = {
    '--dc-bg': colors.bg,
    '--dc-bg-card': colors.bg_card,
    '--dc-border': colors.border,
    '--dc-text': colors.text,
    '--dc-text-muted': colors.text_muted,
    '--dc-accent': colors.accent,
    '--dc-number': colors.number,
  }

  const videoId = getYouTubeId(settings.youtube_url)
  const visibleHistory = history.slice(0, 10)
  // Hanya loket yang sedang melayani/memproses antrian (memiliki nomor aktif).
  const activeCounters = counters.filter((c) => {
    const n = String(c.number || '').trim()
    return n !== '' && n !== '--'
  })

  // URL relay YouTube di-host server Laravel (origin HTTP valid) agar iframe
  // mendapat Referer yang diterima YouTube (mencegah Error 153 di Wails).
  const [relayUrl, setRelayUrl] = useState('')
  useEffect(() => {
    let cancelled = false
    if (!videoId) {
      setRelayUrl('')
      return undefined
    }
    const build = async () => {
      try {
        const cfg = await GetConfig()
        const origin = new URL(cfg.api_base_url).origin
        if (!cancelled) setRelayUrl(`${origin}/yt-relay?v=${videoId}`)
      } catch {
        if (!cancelled) setRelayUrl('')
      }
    }
    build()
    return () => { cancelled = true }
  }, [videoId])

  return (
    <div className={`dc-page${relayUrl ? ' dc-page--video' : ''}`} style={pageStyle}>
      <div className="dc-bg-glow" />
      {showHint && <div className="dc-hint">Tekan ESC untuk keluar fullscreen</div>}

      {/* ===== Header: logo, instansi, jam & tanggal ===== */}
      <header className="dc-header">
        <div className="dc-header-left">
          <div className="dc-logo">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
              <line x1="8" y1="21" x2="16" y2="21" />
              <line x1="12" y1="17" x2="12" y2="21" />
            </svg>
          </div>
          <div className="dc-header-titles">
            <h1 className="dc-title">{settings.header_title}</h1>
            <p className="dc-subtitle">{settings.header_subtitle}</p>
          </div>
        </div>
        <DisplayClock />
      </header>

      {/* ===== Hero: panggilan + video (kiri), loket aktif (kanan) ===== */}
      <main className="dc-hero">
        <div className="dc-hero-left">
          <section className="dc-card dc-call">
            <p className="dc-call-label">NOMOR ANTRIAN</p>
            <div className="dc-call-number">{current?.queue_number || '--'}</div>
            <div className="dc-call-divider" />
            <p className="dc-call-gerai">{current?.gerai_name ? current.gerai_name.toUpperCase() : 'MENUNGGU'}</p>
            <p className="dc-call-agency">{current?.agency || ''}</p>
            <p className="dc-call-service">{current?.service_type || 'Silakan mengambil nomor antrian'}</p>
          </section>

          <section className="dc-card dc-media">
            <MediaPanel
              relayUrl={relayUrl}
              title={settings.header_title}
              subtitle={settings.header_subtitle}
            />
          </section>
        </div>

        <section className="dc-card dc-counters-panel">
          <h3 className="dc-card-title">Loket Sedang Melayani</h3>
          {activeCounters.length > 0 ? (
            <div className="dc-counters">
              {activeCounters.map((c) => (
                <div className="dc-counter" key={c.key}>
                  <span className="dc-counter-label">{c.label || c.key}</span>
                  <span className="dc-counter-number">{c.number || '--'}</span>
                </div>
              ))}
            </div>
          ) : (
            <div className="dc-counters-empty">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
              </svg>
              <p>Semua loket siap melayani</p>
              <span>Silakan mengambil nomor antrian di Anjungan</span>
            </div>
          )}
        </section>
      </main>

      {/* ===== Riwayat antrian hari ini (paling baru) ===== */}
      <section className="dc-card dc-history">
        <h3 className="dc-card-title">Riwayat Antrian Hari Ini</h3>
        <div className="dc-history-head">
          <span>NOMOR</span>
          <span>GERAI</span>
          <span>STATUS</span>
        </div>
        <div className="dc-history-list">
          {visibleHistory.map((h, i) => (
            <div className="dc-history-row" key={`${h.queue_number}-${i}`}>
              <span className="dc-history-nomor">{h.queue_number}</span>
              <span className="dc-history-gerai">{h.gerai_name || '-'}</span>
              <span className={`dc-status ${String(h.status || '').toLowerCase().includes('hadir') ? 'is-rejected' : 'is-done'}`}>
                {h.status || 'Selesai'}
              </span>
            </div>
          ))}
          {visibleHistory.length === 0 && <p className="dc-empty">Belum ada riwayat</p>}
        </div>
      </section>

      {/* ===== Running text ===== */}
      <footer className="dc-marquee">
        <div className="dc-marquee-track">
          <span className="dc-marquee-text">{settings.running_text}</span>
        </div>
      </footer>
    </div>
  )
}
