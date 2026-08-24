export default function GlassBadge({ variant = 'default', children, className = '' }) {
  const bgMap = {
    default: 'rgba(0,0,0,0.06)',
    primary: 'rgba(20,159,181,0.12)',
    accent: 'rgba(248,171,58,0.12)',
  }
  return (
    <span
      className={className}
      style={{
        display: 'inline-flex',
        alignItems: 'center',
        gap: 4,
        padding: '2px 10px',
        borderRadius: 20,
        fontSize: 11,
        fontWeight: 600,
        color: 'rgba(0,0,0,0.4)',
        background: bgMap[variant] || bgMap.default,
      }}
    >
      {children}
    </span>
  )
}
