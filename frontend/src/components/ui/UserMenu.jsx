import { useEffect, useRef, useState } from 'react'
import { useApp } from '../../contexts/AppContext'
import { Logout } from '../../services/api'

const labels = { anjungan: 'Anjungan', fo: 'FO', gerai: 'Gerai', display: 'Display' }

const UserIcon = () => (
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
    <circle cx="12" cy="7" r="4" />
  </svg>
)

const LogoutIcon = () => (
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
    <polyline points="16 17 21 12 16 7" />
    <line x1="21" y1="12" x2="9" y2="12" />
  </svg>
)

export default function UserMenu() {
  const { petugas, mode, setMode, setPetugas, setToken, setRefreshToken, token } = useApp()
  const [open, setOpen] = useState(false)
  const ref = useRef(null)

  useEffect(() => {
    if (!open) return
    function onOutside(e) {
      if (ref.current && !ref.current.contains(e.target)) setOpen(false)
    }
    function onKey(e) {
      if (e.key === 'Escape') setOpen(false)
    }
    document.addEventListener('mousedown', onOutside)
    document.addEventListener('keydown', onKey)
    return () => {
      document.removeEventListener('mousedown', onOutside)
      document.removeEventListener('keydown', onKey)
    }
  }, [open])

  async function handleLogout() {
    try {
      if (token) await Logout()
    } catch {}
    setMode(null)
    setPetugas(null)
    setToken(null)
    setRefreshToken(null)
  }

  const name = petugas?.nama || petugas?.name || petugas?.username || 'User'

  return (
    <div className="user-menu" ref={ref}>
      <button type="button" className="user-menu-avatar" onClick={() => setOpen(v => !v)} aria-label="Menu pengguna">
        <UserIcon />
      </button>
      {open && (
        <div className="user-menu-dropdown">
          <div className="user-menu-head">
            <p className="user-menu-name">{name}</p>
            <span className="user-menu-role">{labels[mode] || mode || ''}</span>
          </div>
          <button type="button" className="user-menu-item danger" onClick={handleLogout}>
            <LogoutIcon /> Keluar
          </button>
        </div>
      )}
    </div>
  )
}
