import { useEffect, useRef, useCallback } from 'react'
import { useApp } from '../contexts/AppContext'
import { getWSURL } from '../services/api'

export function useWebSocket(onMessage) {
  const { token } = useApp()
  const wsRef = useRef(null)
  const handlersRef = useRef(onMessage)
  handlersRef.current = onMessage
  const tokenRef = useRef(token)
  tokenRef.current = token

  const send = useCallback((data) => {
    if (wsRef.current?.readyState === WebSocket.OPEN) {
      wsRef.current.send(typeof data === 'string' ? data : JSON.stringify(data))
    }
  }, [])

  const subscribe = useCallback((channel) => {
    send({ type: 'subscribe', channel })
  }, [send])

  useEffect(() => {
    let reconnectTimer
    let closed = false

    function connect() {
      if (closed) return
      const base = getWSURL()
      const url = tokenRef.current ? `${base}?token=${encodeURIComponent(tokenRef.current)}` : base
      const ws = new WebSocket(url)
      wsRef.current = ws

      ws.onopen = () => {
        subscribe('all')
      }

      ws.onmessage = (event) => {
        try {
          const msg = JSON.parse(event.data)
          handlersRef.current?.(msg)
        } catch {}
      }

      ws.onclose = () => {
        if (!closed) {
          reconnectTimer = setTimeout(connect, 2000)
        }
      }

      ws.onerror = () => ws.close()
    }

    connect()

    return () => {
      closed = true
      clearTimeout(reconnectTimer)
      wsRef.current?.close()
    }
  }, [subscribe])

  return { send, subscribe, ws: wsRef }
}
