import { useState, useEffect, useCallback, useMemo } from 'react'
import {
  FOGetWaitingList,
  FOGetCurrentCalling,
  FOPanggilBerikutnya,
  FOLanjutKeGerai,
  FOTolak,
  FOPanggilUlang,
  FOSkip,
  getErrorMessage,
} from '../services/api'
import { useApp } from '../contexts/AppContext'
import { useToast } from '../contexts/ToastContext'
import { useWebSocket } from '../hooks/useWebSocket'
import { SetStaffWindow } from '../../wailsjs/go/main/App'
import { BackButton, GlassButton, GlassInput, GlassModal, UserMenu } from '../components/ui'

/* ===== Icons (line-art) ===== */
const ListIcon = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <line x1="8" y1="6" x2="21" y2="6" />
    <line x1="8" y1="12" x2="21" y2="12" />
    <line x1="8" y1="18" x2="21" y2="18" />
    <line x1="3" y1="6" x2="3.01" y2="6" />
    <line x1="3" y1="12" x2="3.01" y2="12" />
    <line x1="3" y1="18" x2="3.01" y2="18" />
  </svg>
)

const CheckIcon = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <polyline points="9 11 12 14 22 4" />
    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
  </svg>
)

const XIcon = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <line x1="18" y1="6" x2="6" y2="18" />
    <line x1="6" y1="6" x2="18" y2="18" />
  </svg>
)

const RefreshIcon = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <polyline points="1 4 1 10 7 10" />
    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
  </svg>
)

const SkipIcon = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <polyline points="13 17 18 12 13 7" />
    <polyline points="6 17 11 12 6 7" />
  </svg>
)

const PhoneIcon = () => (
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94" />
    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
  </svg>
)

const InfoIcon = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <circle cx="12" cy="12" r="10" />
    <line x1="12" y1="16" x2="12" y2="12" />
    <line x1="12" y1="8" x2="12.01" y2="8" />
  </svg>
)

const ForwardIcon = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <polyline points="9 18 15 12 9 6" />
  </svg>
)

const ChevronDoubleLeft = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <polyline points="11 17 6 12 11 7" />
    <polyline points="18 17 13 12 18 7" />
  </svg>
)

const ChevronLeft = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <polyline points="15 18 9 12 15 6" />
  </svg>
)

const ChevronRight = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <polyline points="9 18 15 12 9 6" />
  </svg>
)

const ChevronDoubleRight = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <polyline points="13 17 18 12 13 7" />
    <polyline points="6 17 11 12 6 7" />
  </svg>
)

/* ===== Helpers ===== */
const PAGE_SIZE = 5

const STATUS_META = {
  waiting_fo: { label: 'Menunggu FO', cls: 'is-waiting' },
  calling_fo: { label: 'Dipanggil FO', cls: 'is-calling' },
  waiting_gerai: { label: 'Menunggu Gerai', cls: 'is-waiting' },
  calling_gerai: { label: 'Dipanggil Gerai', cls: 'is-calling' },
  done: { label: 'Selesai (Gerai)', cls: 'is-done' },
  rejected: { label: 'Tidak Hadir', cls: 'is-rejected' },
}

function getGreeting() {
  const h = new Date().getHours()
  if (h < 11) return 'Selamat Pagi'
  if (h < 15) return 'Selamat Siang'
  if (h < 18) return 'Selamat Sore'
  return 'Selamat Malam'
}

function formatDuration(iso, nowMs) {
  if (!iso) return '00:00:00'
  const ms = Math.max(0, nowMs - new Date(iso).getTime())
  const sec = Math.floor(ms / 1000)
  const hh = String(Math.floor(sec / 3600)).padStart(2, '0')
  const mm = String(Math.floor((sec % 3600) / 60)).padStart(2, '0')
  const ss = String(sec % 60).padStart(2, '0')
  return `${hh}:${mm}:${ss}`
}

function getPageNumbers(total, cur) {
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1)
  const set = new Set([1, total, cur - 1, cur, cur + 1])
  return [...set].filter(n => n >= 1 && n <= total).sort((a, b) => a - b)
}

export default function FO() {
  const { petugas } = useApp()
  const { showToast } = useToast()
  const [waiting, setWaiting] = useState([])
  const [calling, setCalling] = useState(null)
  const [alasanTolak, setAlasanTolak] = useState('')
  const [targetTicket, setTargetTicket] = useState(null)
  const [showTolakModal, setShowTolakModal] = useState(false)
  const [showInfoModal, setShowInfoModal] = useState(false)
  const [showWaiting, setShowWaiting] = useState(true)
  const [page, setPage] = useState(1)
  const [now, setNow] = useState(Date.now())
  const [loading, setLoading] = useState(true)
  const [actionLoading, setActionLoading] = useState(false)

  const subText = useMemo(() => {
    const svc = calling?.service || waiting[0]?.service
    return svc?.instansi?.nama || 'Dinas Kependudukan dan Pencatatan Sipil (DISDUKCAPIL)'
  }, [calling, waiting])

  const loadData = useCallback(async () => {
    try {
      const [w, c] = await Promise.all([
        FOGetWaitingList(),
        FOGetCurrentCalling(),
      ])
      setWaiting(w)
      setCalling(c)
      setPage(1)
    } catch (err) {
      console.error('FO load error:', err)
    } finally {
      setLoading(false)
    }
  }, [])

  useEffect(() => {
    SetStaffWindow().catch(err => console.error('SetStaffWindow FO:', err))
  }, [])

  useEffect(() => { loadData() }, [loadData])

  useEffect(() => {
    const id = setInterval(() => setNow(Date.now()), 1000)
    return () => clearInterval(id)
  }, [])

  useWebSocket((msg) => {
    if (msg.channel === 'fo' || msg.channel === 'all') loadData()
  })

  const totalPages = Math.max(1, Math.ceil(waiting.length / PAGE_SIZE))
  const currentPage = Math.min(page, totalPages)
  const pagedWaiting = waiting.slice((currentPage - 1) * PAGE_SIZE, currentPage * PAGE_SIZE)
  const activeTicket = targetTicket || calling

  async function handlePanggil() {
    setActionLoading(true)
    try {
      const ticket = await FOPanggilBerikutnya()
      setCalling(ticket)
      setWaiting(prev => prev.filter(t => t.id !== ticket.id))
      setTargetTicket(null)
    } catch (err) {
      if (/tidak ada antrian/i.test(err?.message || '')) {
        showToast('Belum ada antrian yang menunggu.', 'info')
      } else {
        showToast(getErrorMessage(err, 'Gagal memanggil'), 'error')
      }
    } finally {
      setActionLoading(false)
    }
  }

  async function handleLanjut(ticket) {
    if (!ticket) return
    setActionLoading(true)
    try {
      await FOLanjutKeGerai(ticket.id, '')
      if (calling?.id === ticket.id) setCalling(null)
      setWaiting(prev => prev.filter(t => t.id !== ticket.id))
      setTargetTicket(null)
      showToast(`Nomor ${ticket.nomor_antrian} dilanjutkan ke gerai.`, 'success')
    } catch (err) {
      showToast(getErrorMessage(err, 'Gagal melanjutkan ke gerai'), 'error')
    } finally {
      setActionLoading(false)
    }
  }

  async function handleTolak() {
    if (!activeTicket || !alasanTolak) return
    setActionLoading(true)
    try {
      await FOTolak(activeTicket.id, alasanTolak)
      if (calling?.id === activeTicket.id) setCalling(null)
      setWaiting(prev => prev.filter(t => t.id !== activeTicket.id))
      setTargetTicket(null)
      setAlasanTolak('')
      setShowTolakModal(false)
      showToast(`Nomor ${activeTicket.nomor_antrian} ditandai tidak hadir.`, 'success')
    } catch (err) {
      showToast(getErrorMessage(err, 'Gagal menolak'), 'error')
    } finally {
      setActionLoading(false)
    }
  }

  async function handlePanggilUlang() {
    setActionLoading(true)
    try {
      const ticket = await FOPanggilUlang(calling.id)
      setCalling(ticket)
      showToast(`Nomor ${ticket.nomor_antrian} dipanggil ulang.`, 'success')
    } catch (err) {
      showToast(getErrorMessage(err, 'Gagal memanggil ulang'), 'error')
    } finally {
      setActionLoading(false)
    }
  }

  async function handleSkip() {
    setActionLoading(true)
    try {
      await FOSkip(calling.id)
      setCalling(null)
      showToast('Antrian dilewati.', 'success')
    } catch (err) {
      showToast(getErrorMessage(err, 'Gagal melewati antrian'), 'error')
    } finally {
      setActionLoading(false)
    }
  }

  function openTolakModal(t) {
    setTargetTicket(t)
    setAlasanTolak('')
    setShowTolakModal(true)
  }

  function openInfoModal(t) {
    setTargetTicket(t)
    setShowInfoModal(true)
  }

  if (loading) return <div className="page page-centered"><div className="loading-glass">Memuat data...</div></div>

  return (
    <div className="page">
      <BackButton style={{ color: '#fff', borderColor: 'rgba(255,255,255,0.35)', background: 'rgba(255,255,255,0.14)' }} />
      <div className="fo-page">
        {/* ===================== HEADER ===================== */}
        <header className="fo-header">
          <div className="fo-header-greet">
            <h1 className="fo-header-greet-title">{getGreeting()}, Front Office</h1>
            <span className="fo-header-greet-sub">{petugas?.nama || petugas?.name || ''}</span>
          </div>
          <h2 className="fo-header-title">Daftar Antrian</h2>
          <UserMenu />
        </header>

        {/* ===================== CONTENT ===================== */}
        <main className="fo-content">
          {/* ── Toolbar: primary action (kiri) + pagination (kanan) ── */}
          <div className="fo-toolbar">
            <div className="fo-toolbar-left">
              <GlassButton variant="primary" onClick={() => setShowWaiting(v => !v)} icon={<ListIcon />}>
                Tampilkan Antrian Menunggu
              </GlassButton>
              <GlassButton variant="accent" onClick={handlePanggil} disabled={actionLoading || !!calling} icon={<PhoneIcon />}>
                {actionLoading ? 'Memproses...' : 'Panggil Berikutnya'}
              </GlassButton>
            </div>
            <div className="fo-toolbar-right">
              <div className="fo-pagination">
                <button className="fo-page-btn nav" onClick={() => setPage(1)} disabled={currentPage === 1} title="Halaman pertama">
                  <ChevronDoubleLeft />
                </button>
                <button className="fo-page-btn nav" onClick={() => setPage(currentPage - 1)} disabled={currentPage === 1} title="Halaman sebelumnya">
                  <ChevronLeft />
                </button>
                {getPageNumbers(totalPages, currentPage).map((n, idx, arr) => [
                  idx > 0 && n - arr[idx - 1] > 1 && <span key={`e-${n}`} className="fo-page-ellipsis">…</span>,
                  <button
                    key={n}
                    className={`fo-page-btn ${n === currentPage ? 'active' : ''}`}
                    onClick={() => setPage(n)}
                    disabled={n === currentPage}
                  >
                    {n}
                  </button>,
                ])}
                <button className="fo-page-btn nav" onClick={() => setPage(currentPage + 1)} disabled={currentPage === totalPages} title="Halaman berikutnya">
                  <ChevronRight />
                </button>
                <button className="fo-page-btn nav" onClick={() => setPage(totalPages)} disabled={currentPage === totalPages} title="Halaman terakhir">
                  <ChevronDoubleRight />
                </button>
              </div>
            </div>
          </div>

          {/* ── Kartu nomor yang sedang dipanggil ── */}
          {calling && (
            <div className="fo-calling">
              <div className="fo-calling-info">
                <p className="fo-calling-label">DIPANGGIL</p>
                <div className="fo-calling-number">{calling.nomor_antrian}</div>
                <p className="fo-calling-service">{calling.service_name}</p>
                <p className="fo-calling-dur">Waktu berjalan: <strong>{formatDuration(calling.fo_called_at || calling.called_at, now)}</strong></p>
              </div>
              <div className="fo-calling-actions">
                <button className="fo-calling-btn accent" onClick={() => handleLanjut(calling)} disabled={actionLoading}>
                  <CheckIcon /> Lanjut Gerai
                </button>
                <button className="fo-calling-btn danger" onClick={() => openTolakModal(calling)} disabled={actionLoading}>
                  <XIcon /> Tolak
                </button>
                <button className="fo-calling-btn warning" onClick={handlePanggilUlang} disabled={actionLoading}>
                  <RefreshIcon /> Panggil Ulang
                </button>
                <button className="fo-calling-btn neutral" onClick={handleSkip} disabled={actionLoading}>
                  <SkipIcon /> Lewati
                </button>
              </div>
            </div>
          )}

          {/* ── Tabel Antrian ── */}
          {showWaiting && (
            <>
              <div className="fo-table-header">
                <span>NOMOR</span>
                <span>STATUS</span>
                <span>AKSI</span>
              </div>
              <p className="fo-table-sub">{subText}</p>

              <div className="fo-queue-list">
                {pagedWaiting.map(t => {
                  const meta = STATUS_META[t.status] || { label: t.status || 'Menunggu', cls: 'is-waiting' }
                  return (
                    <div className="fo-queue-row" key={t.id}>
                      {/* Kolom NOMOR (panel teal) */}
                      <div className="fo-nomor">{t.nomor_antrian}</div>

                      {/* Kolom STATUS */}
                      <div className="fo-status">
                        <div className="fo-status-top">
                          <span className={`fo-status-badge ${meta.cls}`}>{meta.label}</span>
                        </div>
                        <p className="fo-status-desc">{t.service_name}</p>
                        <p className="fo-status-dur">Durasi: {formatDuration(t.created_at, now)}</p>
                      </div>

                      {/* Kolom AKSI */}
                      <div className="fo-aksi">
                        <button className="fo-icon-btn grey" onClick={() => openTolakModal(t)} title="Tolak / Tidak Hadir">
                          <XIcon />
                        </button>
                        <button className="fo-icon-btn primary" onClick={() => openInfoModal(t)} title="Lihat Detail">
                          <InfoIcon />
                        </button>
                        <button className="fo-icon-btn primary" onClick={() => handleLanjut(t)} title="Lanjut ke Gerai">
                          <ForwardIcon />
                        </button>
                      </div>
                    </div>
                  )
                })}
                {pagedWaiting.length === 0 && <p className="fo-empty">Tidak ada antrian menunggu</p>}
              </div>
            </>
          )}
        </main>
      </div>

      {/* ── Modal: Tolak / Tidak Lengkap ── */}
      <GlassModal
        open={showTolakModal}
        onClose={() => setShowTolakModal(false)}
        title="Tolak / Tidak Hadir"
        actions={
          <>
            <GlassButton variant="danger" onClick={handleTolak} disabled={!alasanTolak || actionLoading}>
              {actionLoading ? 'Memproses...' : 'Tolak'}
            </GlassButton>
            <GlassButton variant="secondary" onClick={() => setShowTolakModal(false)}>Batal</GlassButton>
          </>
        }
      >
        <p className="modal-info">Nomor: <strong>{activeTicket?.nomor_antrian}</strong></p>
        <GlassInput textarea placeholder="Alasan penolakan" value={alasanTolak} onChange={e => setAlasanTolak(e.target.value)} required />
      </GlassModal>

      {/* ── Modal: Detail Antrian ── */}
      <GlassModal
        open={showInfoModal}
        onClose={() => setShowInfoModal(false)}
        title="Detail Antrian"
        actions={
          <GlassButton variant="primary" onClick={() => setShowInfoModal(false)}>Tutup</GlassButton>
        }
      >
        <div className="fo-info">
          <p>Nomor: <strong>{activeTicket?.nomor_antrian}</strong></p>
          <p>Layanan: <strong>{activeTicket?.service_name}</strong></p>
          <p>Status: <strong>{STATUS_META[activeTicket?.status]?.label || activeTicket?.status || '-'}</strong></p>
          <p>Diambil: <strong>{activeTicket?.created_at ? new Date(activeTicket.created_at).toLocaleString('id-ID') : '-'}</strong></p>
          <p>Durasi: <strong>{formatDuration(activeTicket?.created_at, now)}</strong></p>
        </div>
      </GlassModal>
    </div>
  )
}
