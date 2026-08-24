import { useState } from 'react'

export default function AudioUnlockModal({ engine, onUnlock }) {
  const [testing, setTesting] = useState(false)

  async function handleUnlock() {
    if (testing) return
    setTesting(true)
    try {
      await onUnlock()
    } finally {
      setTesting(false)
    }
  }

  const engineLabel = engine === 'go' ? 'Mesin Audio (Go)' : engine === 'web' ? 'Browser (Web Speech)' : '—'

  return (
    <div className="dc-unlock-overlay">
      <div className="dc-unlock-card">
        <div className="dc-unlock-icon">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
            <path d="M15.54 8.46a5 5 0 0 1 0 7.07" />
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
          </svg>
        </div>
        <h2 className="dc-unlock-title">Aktifkan Suara</h2>
        <p className="dc-unlock-desc">
          Klik tombol di bawah untuk membuka kunci audio. Setelah aktif, setiap panggilan
          antrian akan otomatis diumumkan (chime + suara) berurutan tanpa menimpa.
        </p>
        <span className={`dc-unlock-engine is-${engine}`}>Engine: {engineLabel}</span>
        <button className="dc-unlock-btn" onClick={handleUnlock} disabled={testing}>
          {testing ? 'Menguji suara…' : 'Aktifkan Suara & Tes'}
        </button>
      </div>
    </div>
  )
}
