import { useState } from 'react'
import { Login as LoginAPI } from '../services/api'
import { useApp } from '../contexts/AppContext'
import { BackButton, GlassButton, GlassCard, GlassInput, GlassIconWrap } from '../components/ui'

const TOKEN_REFRESH_MARGIN = 5 * 60 * 1000

const LABELS = { fo: 'Front Office', gerai: 'Gerai' }

const UserIcon = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
    <circle cx="12" cy="7" r="4" />
  </svg>
)

const LockIcon = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
  </svg>
)

const KeyIcon = () => (
  <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
    <circle cx="12" cy="16" r="1.2" />
  </svg>
)

export default function Login({ mode, onBack }) {
  const { setPetugas, setMode, setToken, setRefreshToken } = useApp()
  const [username, setUsername] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    setLoading(true)
    try {
      const res = await LoginAPI(username, password)
      if (!res) {
        setError('Username atau password salah')
        return
      }
      const { user, access_token, refresh_token } = res
      if (mode === 'fo' && user.role !== 'petugasfrontoffice') {
        setError('Akun ini bukan petugas FO')
        return
      }
      if (mode === 'gerai' && user.role !== 'gerai') {
        setError('Akun ini bukan petugas Gerai')
        return
      }
      setToken(access_token)
      setRefreshToken(refresh_token)
      setPetugas(user)
      setMode(mode)
    } catch (err) {
      setError(err?.message || 'Gagal login')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="page page-centered">
      <BackButton onBack={onBack} />

      <GlassCard padding="lg" className="login-glass">
        <GlassIconWrap size="lg" color="rgba(248,171,58,0.12)">
          <KeyIcon />
        </GlassIconWrap>
        <h2 className="login-title">Login {LABELS[mode] || ''}</h2>
        <form onSubmit={handleSubmit}>
          <GlassInput icon={<UserIcon />} placeholder="Username" value={username} onChange={e => setUsername(e.target.value)} required />
          <GlassInput icon={<LockIcon />} type="password" placeholder="Password" value={password} onChange={e => setPassword(e.target.value)} required />
          {error && <p className="form-error">{error}</p>}
          <GlassButton variant="accent" fullWidth type="submit" disabled={loading} loading={loading}>
            {loading ? 'Memproses...' : 'Masuk'}
          </GlassButton>
        </form>
      </GlassCard>
    </div>
  )
}
