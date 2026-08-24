const SIZE_MAP = {
  sm: { width: 44, height: 44 },
  md: { width: 54, height: 54 },
  lg: { width: 64, height: 64 },
  xl: { width: 72, height: 72 },
}

export default function GlassIconWrap({ size = 'md', color, className = '', children }) {
  const dim = typeof size === 'number' ? { width: size, height: size } : SIZE_MAP[size] || SIZE_MAP.md
  return (
    <span
      className={`header-icon-wrap ${className}`}
      style={{
        ...dim,
        '--icon-bg': color || 'rgba(20,159,181,0.12)',
      }}
    >
      {children}
    </span>
  )
}
