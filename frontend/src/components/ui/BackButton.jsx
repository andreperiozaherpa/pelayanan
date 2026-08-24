import { useApp } from '../../contexts/AppContext'
import GlassButton from './GlassButton'

const ArrowLeft = () => (
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <line x1="19" y1="12" x2="5" y2="12" />
    <polyline points="12 19 5 12 12 5" />
  </svg>
)

export default function BackButton({ onBack, style }) {
  const { setMode } = useApp()
  return (
    <GlassButton
      variant="secondary"
      size="sm"
      onClick={onBack ?? (() => setMode(null))}
      style={{ position: 'absolute', top: 16, left: 16, zIndex: 10, ...style }}
    >
      <ArrowLeft /> Kembali
    </GlassButton>
  )
}
