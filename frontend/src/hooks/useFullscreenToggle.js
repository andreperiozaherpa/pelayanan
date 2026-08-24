import { useEffect, useRef } from 'react'
import { SetFullscreen } from '../../wailsjs/go/main/App'

export function useFullscreenToggle() {
  const escCount = useRef(0)
  const escTimer = useRef(null)

  useEffect(() => {
    function onKeyDown(e) {
      if (e.key === 'F11') {
        e.preventDefault()
        SetFullscreen(true)
        return
      }

      if (e.key === 'Escape') {
        escCount.current += 1
        if (escCount.current === 2) {
          escCount.current = 0
          clearTimeout(escTimer.current)
          SetFullscreen(false)
          return
        }
        clearTimeout(escTimer.current)
        escTimer.current = setTimeout(() => {
          escCount.current = 0
        }, 600)
      }
    }

    window.addEventListener('keydown', onKeyDown)
    return () => {
      window.removeEventListener('keydown', onKeyDown)
      clearTimeout(escTimer.current)
    }
  }, [])
}
