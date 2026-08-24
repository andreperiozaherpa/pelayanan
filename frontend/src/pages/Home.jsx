import { useState, useEffect } from 'react'
import { OpenDisplay } from '../services/api'
import CallCenterIllustration from '../components/CallCenterIllustration'

const MODES = [
  {
    key: 'anjungan',
    label: 'Anjungan',
    desc: 'Ambil nomor antrian',
    color: 'var(--primary)',
    bgColor: 'rgba(20,159,181,0.15)',
    borderGlow: 'rgba(20,159,181,0.2)',
    icon: (
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <rect x="4" y="3" width="16" height="18" rx="2.5" />
        <rect x="6.5" y="5.5" width="11" height="8" rx="1.5" opacity="0.5" />
        <line x1="9" y1="8.5" x2="15" y2="8.5" opacity="0.5" />
        <line x1="9" y1="10.8" x2="13" y2="10.8" opacity="0.5" />
        <rect x="9" y="16.5" width="6" height="2" rx="1" opacity="0.5" />
      </svg>
    ),
  },
  {
    key: 'fo',
    label: 'Front Office',
    desc: 'Panggil & cek berkas',
    color: 'var(--accent)',
    bgColor: 'rgba(248,171,58,0.15)',
    borderGlow: 'rgba(248,171,58,0.2)',
    icon: (
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <path d="M5 3 L16 3 L21 8 L21 21 L5 21 Z" />
        <path d="M16 3 L16 8 L21 8" opacity="0.5" />
        <line x1="8" y1="11" x2="18" y2="11" opacity="0.5" />
        <line x1="8" y1="14.5" x2="16" y2="14.5" opacity="0.5" />
        <line x1="8" y1="18" x2="14" y2="18" opacity="0.5" />
      </svg>
    ),
  },
  {
    key: 'gerai',
    label: 'Gerai',
    desc: 'Selesaikan layanan',
    color: '#f9c476',
    bgColor: 'rgba(249,196,118,0.15)',
    borderGlow: 'rgba(249,196,118,0.2)',
    icon: (
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <path d="M5 3 L16 3 L21 8 L21 21 L5 21 Z" />
        <path d="M16 3 L16 8 L21 8" opacity="0.5" />
        <rect x="7.5" y="12" width="9" height="8" rx="1.5" opacity="0.5" />
        <path d="M9.5 16 L11.5 18 L15 14" opacity="0.5" />
      </svg>
    ),
  },
  {
    key: 'display',
    label: 'Display Caller',
    desc: 'Tampilkan panggilan',
    color: 'var(--secondary)',
    bgColor: 'rgba(43,81,93,0.15)',
    borderGlow: 'rgba(43,81,93,0.2)',
    icon: (
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <rect x="3" y="4" width="18" height="12" rx="2" />
        <rect x="5.5" y="6" width="13" height="8" rx="1" opacity="0.5" />
        <line x1="12" y1="16" x2="12" y2="19" />
        <line x1="8" y1="19" x2="16" y2="19" />
      </svg>
    ),
  },
]

export default function Home({ onSelect }) {
  const [clicked, setClicked] = useState(null)
  const [visible, setVisible] = useState(false)
  const [toast, setToast] = useState(null)

  useEffect(() => { setVisible(true) }, [])

  function showToast(message) {
    setToast(message)
    setTimeout(() => setToast(null), 4000)
  }

  function handleDisplay() {
    setClicked('display')

    OpenDisplay()
      .catch(err => {
        showToast(err?.message || 'Monitor kedua tidak ditemukan')
      })
      .finally(() => setClicked(null))
  }

  function handleClick(key) {
    if (key === 'display') {
      handleDisplay()
      return
    }
    setClicked(key)
    setTimeout(() => onSelect(key), 300)
  }

  return (
    <div className="home-page">
      {toast && (
        <div className="home-toast">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            <line x1="12" y1="9" x2="12" y2="13" />
            <line x1="12" y1="17" x2="12.01" y2="17" />
          </svg>
          {toast}
        </div>
      )}

      <div className="home-bg-figure home-bg-figure-1" />
      <div className="home-bg-figure home-bg-figure-2" />
      <div className="home-bg-figure home-bg-figure-3" />

      <div className="home-left">
        <div className="home-character-wrapper">
          <CallCenterIllustration className="csr-lineart-svg" />
        </div>

        <div className="home-left-text">
          <h2 className="home-left-title">Sistem Antrian</h2>
          <p className="home-left-sub">Mal Pelayanan Publik</p>
        </div>
      </div>

      <div className="home-right">
        <div className="home-glass-container">
          <div className="home-welcome">
            <h1 className="home-welcome-title">Selamat Datang</h1>
            <p className="home-welcome-sub">Pilih titik layanan untuk memulai</p>
          </div>

          <div className="home-grid">
            {MODES.map((m, i) => (
              <button
                key={m.key}
                className={`home-glass-card ${visible ? 'card-enter' : ''} ${clicked === m.key ? 'card-clicked' : ''}`}
                onClick={() => handleClick(m.key)}
                style={{
                  '--card-color': m.color,
                  '--card-glow': m.borderGlow,
                  '--i': i,
                }}
                disabled={clicked !== null}
              >
                <span className="home-card-icon" style={{ '--icon-bg': m.bgColor }}>
                  {m.icon}
                </span>
                <span className="home-card-label">{m.label}</span>
                <span className="home-card-desc">{m.desc}</span>
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}
