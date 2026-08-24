import { createContext, useContext, useState, useEffect, useCallback } from 'react'

const AppContext = createContext(null)

function getInitialMode() {
  if (typeof window === 'undefined') return null
  const params = new URLSearchParams(window.location.search)
  return params.get('mode') || null
}

export function AppProvider({ children }) {
  const [mode, setMode] = useState(getInitialMode)
  const [petugas, setPetugas] = useState(null)
  const [token, setToken] = useState(null)
  const [refreshToken, setRefreshToken] = useState(null)
  const [config, setConfig] = useState(null)

  useEffect(() => {
    if (mode === 'display') {
      document.title = 'Display Caller - Sistem Antrian'
    }
  }, [mode])

  const logout = useCallback(() => {
    setMode(null)
    setPetugas(null)
    setToken(null)
    setRefreshToken(null)
  }, [])

  return (
    <AppContext.Provider value={{
      mode, setMode, petugas, setPetugas, token, setToken,
      refreshToken, setRefreshToken, logout, config, setConfig,
    }}>
      {children}
    </AppContext.Provider>
  )
}

export function useApp() {
  const ctx = useContext(AppContext)
  if (!ctx) throw new Error('useApp must be used within AppProvider')
  return ctx
}
