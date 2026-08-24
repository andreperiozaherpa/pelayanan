import { initializeApp } from 'firebase/app'
import { getDatabase, ref, onValue, set, push, remove } from 'firebase/database'

/*
 * Firebase Realtime Database — Display Caller MPP.
 *
 * Struktur data (lihat antrian/firebase/db-structure.json):
 *   current_call        { queue_number, gerai_name, agency, service_type, timestamp }
 *   active_counters     { <key>: { label, number, timestamp }, ... }
 *   recent_history      [ { queue_number, gerai_name, status, timestamp }, ... ]
 *   display_settings    { header_title, header_subtitle, running_text, youtube_url,
 *                         tts: { enabled, rate, pitch }, colors: { bg, bg_card, border,
 *                         text, text_muted, accent, number } }
 */

const FIREBASE_ENV = {
  apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
  authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
  databaseURL: import.meta.env.VITE_FIREBASE_DATABASE_URL,
  projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
  storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
  messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
  appId: import.meta.env.VITE_FIREBASE_APP_ID,
}

export function isFirebaseEnabled() {
  return Boolean(FIREBASE_ENV.apiKey && FIREBASE_ENV.databaseURL)
}

let app = null
let db = null

function getDb() {
  if (db) return db
  if (!isFirebaseEnabled()) return null
  app = app || initializeApp(FIREBASE_ENV)
  db = getDatabase(app)
  return db
}

export const DISPLAY_DEFAULT_SETTINGS = {
  header_title: 'SIBERUGO MPP TUBABA',
  header_subtitle: 'Sistem Informasi Bersama Antar Gerai MPP Tulang Bawang Barat',
  running_text: 'Selamat Datang di Mal Pelayanan Publik (MPP) Tulang Bawang Barat. Silakan antri sesuai nomor yang dipanggil.',
  youtube_url: '',
  tts: { enabled: true, rate: 0.9, pitch: 1, voice: 'google' },
  chime_sound: 'airport-3tone',
  colors: {
    bg: '#0f172a',
    bg_card: '#1e293b',
    border: '#334155',
    text: '#f8fafc',
    text_muted: '#94a3b8',
    accent: '#f8ab3a',
    number: '#f8ab3a',
  },
}

function subscribe(path, cb) {
  const database = getDb()
  if (!database) return () => {}
  return onValue(ref(database, path), (snap) => cb(snap.val() || {}))
}

/** Pengaturan tampilan (running text, youtube, header, tts, warna) — realtime. */
export function subscribeDisplaySettings(cb) {
  return subscribe('display_settings', (val) => {
    cb({
      ...DISPLAY_DEFAULT_SETTINGS,
      ...val,
      tts: { ...DISPLAY_DEFAULT_SETTINGS.tts, ...(val.tts || {}) },
      colors: { ...DISPLAY_DEFAULT_SETTINGS.colors, ...(val.colors || {}) },
    })
  })
}

/** Panggilan aktif yang sedang ditampilkan di layar. */
export function subscribeCurrentCall(cb) {
  return subscribe('current_call', (val) => cb(Object.keys(val || {}).length ? val : null))
}

/** Daftar kartu loket aktif (urut dari panggilan terbaru). */
export function subscribeActiveCounters(cb) {
  return subscribe('active_counters', (val) => {
    const list = Object.entries(val || {})
      .filter(([, v]) => v && typeof v === 'object')
      .map(([key, v]) => ({ key, ...v }))
      .sort((a, b) => (b.timestamp || 0) - (a.timestamp || 0))
    cb(list)
  })
}

/** Riwayat 15-20 antrian terakhir beserta status. */
export function subscribeRecentHistory(cb) {
  return subscribe('recent_history', (val) => {
    const arr = Array.isArray(val) ? val : Object.values(val || {})
    cb(arr)
  })
}

/* ===== Write helpers (admin / tooling / simulasi) ===== */

export function writeDisplaySettings(settings) {
  const database = getDb()
  if (!database) return Promise.resolve()
  return set(ref(database, 'display_settings'), settings)
}

export function publishCurrentCall(call) {
  const database = getDb()
  if (!database) return Promise.resolve()
  return set(ref(database, 'current_call'), call)
}

export function setActiveCounter(key, data) {
  const database = getDb()
  if (!database) return Promise.resolve()
  return data === null ? remove(ref(database, `active_counters/${key}`)) : set(ref(database, `active_counters/${key}`), data)
}

export function pushRecentHistory(entry, limit = 20) {
  const database = getDb()
  if (!database) return Promise.resolve()
  return push(ref(database, 'recent_history'), entry)
}

export { getDb }
