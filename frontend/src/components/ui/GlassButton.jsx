const VARIANT_MAP = {
  primary: 'btn-glass-primary',
  accent: 'btn-glass-accent',
  danger: 'btn-glass-danger',
  warning: 'btn-glass-warning',
  secondary: 'btn-glass-secondary',
}

const SIZE_MAP = {
  sm: { padding: '8px 16px', fontSize: '12px' },
  md: {},
  lg: { padding: '14px 28px', fontSize: '15px' },
}

export default function GlassButton({
  variant = 'primary',
  size = 'md',
  fullWidth,
  loading,
  disabled,
  icon,
  children,
  className = '',
  ...rest
}) {
  const cls = [
    'btn-glass',
    VARIANT_MAP[variant] || VARIANT_MAP.primary,
    fullWidth && 'btn-full',
    className,
  ]
    .filter(Boolean)
    .join(' ')

  return (
    <button className={cls} disabled={disabled || loading} style={SIZE_MAP[size]} {...rest}>
      {loading ? (
        <span className="btn-spinner" />
      ) : (
        icon && <span className="btn-icon">{icon}</span>
      )}
      {children}
    </button>
  )
}
