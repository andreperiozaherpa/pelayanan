import { createContext, useContext, useState, useCallback, useRef } from 'react'
import Toast from '../components/ui/Toast'

const ToastContext = createContext(null)

const DEFAULT_DURATION = 4500

export function ToastProvider({ children }) {
  const [toasts, setToasts] = useState([])
  const idRef = useRef(0)

  const dismiss = useCallback((id) => {
    setToasts(prev => prev.filter(t => t.id !== id))
  }, [])

  const showToast = useCallback((message, type = 'info', duration = DEFAULT_DURATION) => {
    const id = ++idRef.current
    setToasts(prev => [...prev, { id, message, type }])
    window.setTimeout(() => dismiss(id), duration)
  }, [dismiss])

  return (
    <ToastContext.Provider value={{ showToast, dismiss }}>
      {children}
      {toasts.length > 0 && (
        <div className="toast-container">
          {toasts.map(t => (
            <Toast key={t.id} message={t.message} type={t.type} onClose={() => dismiss(t.id)} />
          ))}
        </div>
      )}
    </ToastContext.Provider>
  )
}

export function useToast() {
  const ctx = useContext(ToastContext)
  if (!ctx) throw new Error('useToast must be used within ToastProvider')
  return ctx
}