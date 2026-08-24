export default function GlassModal({ open, onClose, title, children, actions }) {
  if (!open) return null
  return (
    <div className="modal-overlay-glass" onClick={onClose}>
      <div className="glass-card modal-glass" onClick={e => e.stopPropagation()}>
        <div className="modal-head">
          {title && <h3>{title}</h3>}
          <button type="button" className="modal-close" onClick={onClose} aria-label="Tutup">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>
        {children}
        {actions && <div className="modal-actions-glass">{actions}</div>}
      </div>
    </div>
  )
}
