import { forwardRef } from 'react'

const PADDING_MAP = {
  none: { padding: 0 },
  sm: { padding: '16px' },
  md: {},
  lg: { padding: '36px 32px' },
  xl: { padding: '44px 36px' },
}

const GlassCard = forwardRef(function GlassCard(
  {
    variant = 'default',
    padding = 'md',
    hoverable,
    accent,
    accentColor,
    className = '',
    style,
    children,
    ...rest
  },
  ref,
) {
  let cls = 'glass-card'
  if (variant === 'calling') cls = 'calling-card-glass'
  if (variant === 'modal') cls = 'glass-card modal-glass'
  if (hoverable) cls += ' glass-card-hover'
  if (className) cls += ' ' + className

  const mergedStyle = {
    ...(padding !== 'md' ? PADDING_MAP[padding] || {} : {}),
    ...(accent && { borderColor: accentColor || 'var(--accent)' }),
    ...style,
  }

  return (
    <div ref={ref} className={cls} style={mergedStyle} {...rest}>
      {children}
    </div>
  )
})

export default GlassCard
