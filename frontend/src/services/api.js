import { OpenDisplay, GetMode, GetConfig } from '../../wailsjs/go/main/App'
import * as mock from './mockData.js'

let USE_MOCK = import.meta.env.VITE_USE_MOCK === 'true'

let API = ''
let WS_URL = ''

export async function loadConfig() {
  try {
    const cfg = await GetConfig()
    API = cfg.api_base_url
    WS_URL = cfg.ws_url
    if (cfg.mock_api) USE_MOCK = true
  } catch {
    API = 'http://localhost:8080/api/v1'
    WS_URL = 'ws://localhost:8080/ws'
    if (import.meta.env.DEV) USE_MOCK = true
  }
}

export function getWSURL() {
  return WS_URL
}

let getToken = null
let getRefreshToken = null
let setTokens = null
let onUnauthorized = null

export function configureAuth(tokenFn, refreshTokenFn, setTokensFn, unauthFn) {
  getToken = tokenFn
  getRefreshToken = refreshTokenFn
  setTokens = setTokensFn
  onUnauthorized = unauthFn
}

async function refreshAccessToken() {
  if (!getRefreshToken || !getRefreshToken()) return false
  try {
    const res = await fetch(`${API}/auth/refresh`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ refresh_token: getRefreshToken() }),
    })
    const json = await res.json()
    if (!json.success) return false
    setTokens(json.data.access_token, json.data.refresh_token)
    return true
  } catch {
    return false
  }
}

async function request(method, path, body) {
  const opts = { method, headers: {} }

  const token = getToken ? getToken() : null
  if (token) opts.headers['Authorization'] = `Bearer ${token}`

  if (body) {
    opts.headers['Content-Type'] = 'application/json'
    opts.body = JSON.stringify(body)
  }

  let res = await fetch(`${API}${path}`, opts)

  if (res.status === 401 && token) {
    const refreshed = await refreshAccessToken()
    if (refreshed) {
      opts.headers['Authorization'] = `Bearer ${getToken()}`
      res = await fetch(`${API}${path}`, opts)
    } else {
      onUnauthorized?.()
      throw new Error('Sesi berakhir, silakan login ulang')
    }
  }

  if (!res.ok) {
    const text = await res.text()
    console.error(`API ${method} ${path} ${res.status}:`, text.slice(0, 300))
    try {
      const json = JSON.parse(text)
      const first = json?.errors ? Object.values(json.errors)[0] : null
      throw new Error(json.error || json.message || first || `HTTP ${res.status}`)
    } catch (e) {
      if (e instanceof SyntaxError) throw new Error(`Server returned ${res.status} (non-JSON)`)
      throw e
    }
  }

  const json = await res.json()
  if (!json.success) throw new Error(json.error || json.message || 'Unknown error')
  return json.data
}

async function requestForm(method, path, formData) {
  const opts = { method, headers: {} }

  const token = getToken ? getToken() : null
  if (token) opts.headers['Authorization'] = `Bearer ${token}`
  opts.body = formData

  let res = await fetch(`${API}${path}`, opts)

  if (res.status === 401 && token) {
    const refreshed = await refreshAccessToken()
    if (refreshed) {
      opts.headers['Authorization'] = `Bearer ${getToken()}`
      res = await fetch(`${API}${path}`, opts)
    } else {
      onUnauthorized?.()
      throw new Error('Sesi berakhir, silakan login ulang')
    }
  }

  if (!res.ok) {
    const text = await res.text()
    console.error(`API ${method} ${path} ${res.status}:`, text.slice(0, 300))
    try {
      const json = JSON.parse(text)
      const first = json?.errors ? Object.values(json.errors)[0] : null
      throw new Error(json.error || json.message || first || `HTTP ${res.status}`)
    } catch (e) {
      if (e instanceof SyntaxError) throw new Error(`Server returned ${res.status} (non-JSON)`)
      throw e
    }
  }

  const json = await res.json()
  if (!json.success) throw new Error(json.error || json.message || 'Unknown error')
  return json.data
}

export function getErrorMessage(err, fallback = 'Terjadi kesalahan') {
  const msg = err?.message || ''
  if (/tidak ada antrian/i.test(msg)) return 'Belum ada antrian yang menunggu.'
  if (/failed to fetch|networkerror|load failed|network error/i.test(msg)) {
    return 'Gagal terhubung ke server. Periksa koneksi Anda.'
  }
  return msg || fallback
}

export function Login(username, password) {
  if (USE_MOCK) return mock.mockLogin(username, password)
  return request('POST', '/auth/login', { username, password })
}

export function GetServices() {
  if (USE_MOCK) return mock.mockGetServices()
  return request('GET', '/services')
}

export function AmbilNomor(serviceID) {
  if (USE_MOCK) return mock.mockAmbilNomor(serviceID)
  return request('POST', '/tickets', { service_id: serviceID })
}

export function AmbilNomorForm(serviceID, formData, notes) {
  if (USE_MOCK) return mock.mockAmbilNomorForm(serviceID, formData, notes)

  const hasFile = Object.values(formData || {}).some(v => v instanceof File)
  if (hasFile) {
    const fd = new FormData()
    Object.entries(formData).forEach(([k, v]) => {
      if (v instanceof File) fd.append(`form_data[${k}]`, v, v.name)
      else if (Array.isArray(v)) v.forEach(item => fd.append(`form_data[${k}][]`, item))
      else if (v !== undefined && v !== null && v !== '') fd.append(`form_data[${k}]`, v)
    })
    if (notes) fd.append('notes', notes)
    return requestForm('POST', `/services/${serviceID}/requests`, fd)
  }
  return request('POST', `/services/${serviceID}/requests`, { form_data: formData, notes })
}

export function FOGetWaitingList() {
  if (USE_MOCK) return mock.mockFOGetWaitingList()
  return request('GET', '/fo/waiting')
}

export function FOGetCurrentCalling() {
  if (USE_MOCK) return mock.mockFOGetCurrentCalling()
  return request('GET', '/fo/calling')
}

export function FOPanggilBerikutnya() {
  if (USE_MOCK) return mock.mockFOPanggilBerikutnya()
  return request('POST', '/fo/call')
}

export function FOLanjutKeGerai(ticketID, catatan) {
  if (USE_MOCK) return mock.mockFOLanjutKeGerai(ticketID, catatan)
  return request('POST', '/fo/forward', { ticket_id: ticketID, catatan })
}

export function FOTolak(ticketID, alasan) {
  if (USE_MOCK) return mock.mockFOTolak(ticketID, alasan)
  return request('POST', '/fo/reject', { ticket_id: ticketID, alasan })
}

export function FOPanggilUlang(ticketID) {
  if (USE_MOCK) return mock.mockFOPanggilUlang(ticketID)
  return request('POST', '/fo/recall', { ticket_id: ticketID })
}

export function FOSkip(ticketID) {
  if (USE_MOCK) return mock.mockFOSkip(ticketID)
  return request('POST', '/fo/skip', { ticket_id: ticketID })
}

export function GeraiGetWaitingList(geraiID) {
  if (USE_MOCK) return mock.mockGeraiGetWaitingList(geraiID)
  return request('GET', `/gerai/${geraiID}/waiting`)
}

export function GeraiGetCurrentCalling(geraiID) {
  if (USE_MOCK) return mock.mockGeraiGetCurrentCalling(geraiID)
  return request('GET', `/gerai/${geraiID}/calling`)
}

export function GeraiPanggilBerikutnya(geraiID) {
  if (USE_MOCK) return mock.mockGeraiPanggilBerikutnya(geraiID)
  return request('POST', '/gerai/call')
}

export function GeraiSelesai(ticketID, catatan) {
  if (USE_MOCK) return mock.mockGeraiSelesai(ticketID, catatan)
  return request('POST', '/gerai/complete', { ticket_id: ticketID, catatan })
}

export function GeraiPanggilUlang(ticketID) {
  if (USE_MOCK) return mock.mockGeraiPanggilUlang(ticketID)
  return request('POST', '/gerai/recall', { ticket_id: ticketID })
}

export function GetAllGerai() {
  if (USE_MOCK) return mock.mockGetAllGerai()
  return request('GET', '/gerai')
}

export function DisplayGetHistory() {
  if (USE_MOCK) return mock.mockDisplayHistory()
  return request('GET', '/display/history')
}

export function Logout() {
  if (USE_MOCK) return mock.mockLogout()
  return request('POST', '/auth/logout')
}

export function LogoutAll() {
  if (USE_MOCK) return mock.mockLogoutAll()
  return request('POST', '/auth/logout-all')
}

export { OpenDisplay, GetMode, GetConfig }
