import { forwardRef } from 'react'

const GlassInput = forwardRef(function GlassInput(
  { icon, textarea, error, className = '', wrapperStyle, children, ...rest },
  ref,
) {
  if (textarea) {
    return (
      <div>
        <textarea ref={ref} className={`glass-textarea ${className}`} {...rest} />
        {error && <p className="form-error">{error}</p>}
      </div>
    )
  }

  if (rest.type === 'select') {
    return (
      <div>
        <select ref={ref} className={`glass-input ${className}`} {...rest}>
          {children}
        </select>
        {error && <p className="form-error">{error}</p>}
      </div>
    )
  }

  return (
    <div>
      <div className={`glass-input-wrap ${className}`} style={wrapperStyle}>
        {icon && <span className="input-icon">{icon}</span>}
        <input ref={ref} className="glass-input" {...rest} />
      </div>
      {error && <p className="form-error">{error}</p>}
    </div>
  )
})

export default GlassInput
