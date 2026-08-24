import React from 'react'
import { createRoot } from 'react-dom/client'
import './style.css'
import App from './App'
import { AppProvider } from './contexts/AppContext'
import { ToastProvider } from './contexts/ToastContext'

const container = document.getElementById('root')
const root = createRoot(container)

root.render(
  <React.StrictMode>
    <AppProvider>
      <ToastProvider>
        <App />
      </ToastProvider>
    </AppProvider>
  </React.StrictMode>
)
