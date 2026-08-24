import { useApp } from '../../contexts/AppContext'
import { Logout } from '../../services/api'

const labels = { anjungan: 'Anjungan', fo: 'FO', gerai: 'Gerai', display: 'Display' }

export default function LogoutBtn({ variant }) {
  const { mode, setMode, setPetugas, setToken, setRefreshToken, token } = useApp()
  if (mode === 'display') return null
  async function handleLogout() {
    try {
      if (token) await Logout()
    } catch {}
    setMode(null)
    setPetugas(null)
    setToken(null)
    setRefreshToken(null)
  }
  const cls = variant === 'header' ? 'logout-btn logout-btn-header' : 'logout-btn'
  return (
    <button className={cls} onClick={handleLogout}>
      {labels[mode] || ''} — Keluar
    </button>
  )
}
