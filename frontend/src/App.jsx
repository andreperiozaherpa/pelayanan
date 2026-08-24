import { useState, useEffect, useRef } from 'react'
import { useApp } from './contexts/AppContext'
import Home from './pages/Home'
import Login from './pages/Login'
import Anjungan from './pages/Anjungan'
import FO from './pages/FO'
import Gerai from './pages/Gerai'
import CallerDisplay from './pages/CallerDisplay'
import { GetMode, configureAuth, loadConfig } from './services/api'
import { SetFullscreen, SetStaffWindow, SetHomeWindow } from '../wailsjs/go/main/App'
import { useFullscreenToggle } from './hooks/useFullscreenToggle'
import './App.css'

export default function App() {
  const { mode, setMode, petugas, token, refreshToken, setToken, setRefreshToken, logout, setConfig } = useApp()
  const [selected, setSelected] = useState(null)
  const [configLoaded, setConfigLoaded] = useState(false)
  const prevMode = useRef(null)

  useFullscreenToggle()

  useEffect(() => {
    if (mode === prevMode.current) return
    prevMode.current = mode
    if (mode === 'display') return
    SetFullscreen(mode === 'anjungan')
    if (mode === 'fo' || mode === 'gerai') {
      SetStaffWindow()
    } else if (mode == null) {
      SetHomeWindow()
    }
  }, [mode])

  useEffect(() => {
    loadConfig().then(() => {
      setConfigLoaded(true)
    })
  }, [setConfig])

  useEffect(() => {
    configureAuth(
      () => token,
      () => refreshToken,
      (at, rt) => { setToken(at); setRefreshToken(rt) },
      () => logout(),
    )
  })

  useEffect(() => {
    if (!mode) {
      GetMode().then(m => {
        if (m === 'display') setMode('display')
      }).catch(() => {})
    }
  }, [mode, setMode])

  useEffect(() => {
    if (!selected) return
    if (selected === 'anjungan') {
      setMode('anjungan')
      return
    }
    const authed =
      (selected === 'fo' && petugas?.role === 'petugasfrontoffice') ||
      (selected === 'gerai' && petugas?.role === 'gerai')
    if (authed) setMode(selected)
  }, [selected, petugas, setMode])

  useEffect(() => {
    if (!mode) setSelected(null)
  }, [mode])

  if (!configLoaded) return null

  if (mode === 'display') return <CallerDisplay />
  if (mode === 'anjungan') return <Anjungan />
  if (mode === 'fo') return <FO />
  if (mode === 'gerai') return <Gerai />

  if (!selected) return <Home onSelect={setSelected} />
  return <Login mode={selected} onBack={() => setSelected(null)} />
}
