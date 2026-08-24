const MOCK_TOKEN =
  'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJ1c2VybmFtZSI6ImZvMSIsInJvbGUiOiJmbyJ9.mock'

const NOW = Date.now()
const TS = (offset = 0) => new Date(NOW - offset * 60000).toISOString()

const services = [
  {
    id: 1,
    nama: 'Perekaman KTP',
    deskripsi: 'Perekaman data kependudukan',
    logo: null,
    instansi: { id: 1, kode: '11', nama: 'Dinas Dukcapil', gerai: { kode: 'GR-001', nama: 'Gerai Dukcapil' } },
    fields: [
      { id: 'f1', name: 'nik', label: 'NIK', type: 'number', required: true, options: [] },
      { id: 'f2', name: 'nama_lengkap', label: 'Nama Lengkap', type: 'text', required: true, options: [] },
      { id: 'f3', name: 'alamat', label: 'Alamat', type: 'textarea', required: false, options: [] },
    ],
  },
  {
    id: 2,
    nama: 'Perizinan Usaha',
    deskripsi: 'Pengurusan izin usaha',
    logo: null,
    instansi: { id: 2, kode: '21', nama: 'DPMPTSP', gerai: { kode: 'GR-002', nama: 'Gerai PM-PTSP' } },
    fields: [
      { id: 'f4', name: 'nama_usaha', label: 'Nama Usaha', type: 'text', required: true, options: [] },
      { id: 'f5', name: 'jenis_usaha', label: 'Jenis Usaha', type: 'select', required: true, options: ['Kuliner', 'Fashion', 'Kerajinan', 'Jasa'] },
      { id: 'f8', name: 'kelengkapan_berkas', label: 'Kelengkapan Berkas', type: 'checkbox', required: true, options: ['KTP', 'Surat Usaha', 'NPWP'] },
    ],
  },
  {
    id: 3,
    nama: 'Akta Catatan Sipil',
    deskripsi: 'Pembuatan akta',
    logo: null,
    instansi: { id: 1, kode: '11', nama: 'Dinas Dukcapil', gerai: { kode: 'GR-001', nama: 'Gerai Dukcapil' } },
    fields: [
      { id: 'f6', name: 'nama_anak', label: 'Nama Anak', type: 'text', required: true, options: [] },
      { id: 'f7', name: 'tanggal_lahir', label: 'Tanggal Lahir', type: 'text', required: true, options: [] },
    ],
  },
  {
    id: 4,
    nama: 'Surat Keterangan',
    deskripsi: 'Penerbitan surat keterangan',
    logo: null,
    instansi: { id: 3, kode: '31', nama: 'Dinas Sosial', gerai: { kode: 'GR-003', nama: 'Gerai Sosial' } },
    fields: [],
  },
]

const geraiList = [
  { id: 1, nama: 'Gerai 1', kode: 'G01', lokasi: 'Lantai 1 Timur', opd_id: 1 },
  { id: 2, nama: 'Gerai 2', kode: 'G02', lokasi: 'Lantai 1 Barat', opd_id: 2 },
  { id: 3, nama: 'Gerai 3', kode: 'G03', lokasi: 'Lantai 2 Timur', opd_id: 3 },
]

const petugasMap = {
  fo1: { id: 1, nama: 'Andre FO', username: 'fo1', role: 'petugasfrontoffice', counter_id: 1, counter_nama: 'Gerai 1' },
  fo2: { id: 2, nama: 'Budi FO', username: 'fo2', role: 'petugasfrontoffice', counter_id: 2, counter_nama: 'Gerai 2' },
  gerai1: { id: 3, nama: 'Citra Gerai', username: 'gerai1', role: 'gerai', counter_id: 1, counter_nama: 'Gerai 1' },
  gerai2: { id: 4, nama: 'Dewi Gerai', username: 'gerai2', role: 'gerai', counter_id: 2, counter_nama: 'Gerai 2' },
  gerai3: { id: 5, nama: 'Eko Gerai', username: 'gerai3', role: 'gerai', counter_id: 3, counter_nama: 'Gerai 3' },
}

let ticketCounter = 5
const geraiCounter = {}
const tickets = [
  { id: 1, nomor_antrian: 'DC-001', service_id: 1, counter_tujuan_id: null, status: 'waiting_fo', fo_petugas_id: null, gerai_petugas_id: null, catatan: null, alasan_reject: null, tgl_ambil: TS(30), called_at: null, done_at: null, created_at: TS(30), updated_at: TS(30), service_name: 'Perekaman KTP', counter_tujuan: null, fo_petugas: null, gerai_petugas: null },
  { id: 2, nomor_antrian: 'PS-001', service_id: 2, counter_tujuan_id: null, status: 'waiting_fo', fo_petugas_id: null, gerai_petugas_id: null, catatan: null, alasan_reject: null, tgl_ambil: TS(25), called_at: null, done_at: null, created_at: TS(25), updated_at: TS(25), service_name: 'Perizinan Usaha', counter_tujuan: null, fo_petugas: null, gerai_petugas: null },
  { id: 3, nomor_antrian: 'AK-001', service_id: 3, counter_tujuan_id: null, status: 'waiting_fo', fo_petugas_id: null, gerai_petugas_id: null, catatan: null, alasan_reject: null, tgl_ambil: TS(20), called_at: null, done_at: null, created_at: TS(20), updated_at: TS(20), service_name: 'Akta Catatan Sipil', counter_tujuan: null, fo_petugas: null, gerai_petugas: null },
  { id: 4, nomor_antrian: 'SK-001', service_id: 4, counter_tujuan_id: null, status: 'waiting_fo', fo_petugas_id: null, gerai_petugas_id: null, catatan: null, alasan_reject: null, tgl_ambil: TS(15), called_at: null, done_at: null, created_at: TS(15), updated_at: TS(15), service_name: 'Surat Keterangan', counter_tujuan: null, fo_petugas: null, gerai_petugas: null },
  { id: 5, nomor_antrian: 'DC-002', service_id: 1, counter_tujuan_id: null, status: 'waiting_fo', fo_petugas_id: null, gerai_petugas_id: null, catatan: null, alasan_reject: null, tgl_ambil: TS(10), called_at: null, done_at: null, created_at: TS(10), updated_at: TS(10), service_name: 'Perekaman KTP', counter_tujuan: null, fo_petugas: null, gerai_petugas: null },
]

function delay(ms = 300) {
  return new Promise(r => setTimeout(r, ms))
}

function nextNomorAntrian(service) {
  const gerai = service?.instansi?.gerai || service?.gerai
  const short = (gerai?.kode || 'GR').split('-')[0] || 'GR'
  geraiCounter[short] = (geraiCounter[short] || 0) + 1
  return `${short}-${String(geraiCounter[short]).padStart(3, '0')}`
}

export async function mockLogin(username, password) {
  await delay(400)
  if (password !== 'lerd123') {
    throw new Error('Username atau password salah')
  }
  const user = petugasMap[username]
  if (!user) {
    throw new Error('Username atau password salah')
  }
  return {
    user,
    access_token: MOCK_TOKEN,
    refresh_token: MOCK_TOKEN,
  }
}

export async function mockGetServices() {
  await delay()
  return [...services]
}

export async function mockAmbilNomor(serviceID) {
  await delay(500)
  const svc = services.find(s => s.id === serviceID)
  ticketCounter++
  const ticket = {
    id: ticketCounter,
    nomor_antrian: nextNomorAntrian(svc),
    service_id: serviceID,
    counter_tujuan_id: null,
    status: 'waiting_fo',
    fo_petugas_id: null,
    gerai_petugas_id: null,
    catatan: null,
    alasan_reject: null,
    tgl_ambil: new Date().toISOString(),
    called_at: null,
    done_at: null,
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
    service_name: svc?.nama || '',
    counter_tujuan: null,
    fo_petugas: null,
    gerai_petugas: null,
  }
  tickets.push(ticket)
  return ticket
}

export async function mockAmbilNomorForm(serviceID, formData, notes) {
  await delay(700)
  const svc = services.find(s => s.id === serviceID)
  ticketCounter++
  const ticket = {
    id: ticketCounter,
    queue_id: ticketCounter,
    nomor_antrian: nextNomorAntrian(svc),
    service_id: serviceID,
    status: 'waiting_fo',
    catatan: notes || null,
    created_at: new Date().toISOString(),
    service_name: svc?.nama || '',
  }
  tickets.push(ticket)
  return ticket
}

export async function mockFOGetWaitingList() {
  await delay()
  return tickets.filter(t => t.status === 'waiting_fo')
}

export async function mockFOGetCurrentCalling() {
  await delay()
  return tickets.find(t => t.status === 'calling_fo') || null
}

export async function mockFOPanggilBerikutnya() {
  await delay(500)
  const next = tickets.find(t => t.status === 'waiting_fo')
  if (!next) throw new Error('Tidak ada antrian')
  next.status = 'calling_fo'
  next.fo_petugas_id = 1
  next.called_at = new Date().toISOString()
  return { ...next }
}

export async function mockFOLanjutKeGerai(ticketID, catatan) {
  await delay(400)
  const t = tickets.find(t => t.id === ticketID)
  if (!t) throw new Error('Ticket tidak ditemukan')
  t.status = 'waiting_gerai'
  t.fo_petugas_id = 1
  t.counter_tujuan_id = null
  t.counter_tujuan = null
  t.catatan = catatan || null
  return { ...t }
}

export async function mockFOTolak(ticketID, alasan) {
  await delay(400)
  const t = tickets.find(t => t.id === ticketID)
  if (!t) throw new Error('Ticket tidak ditemukan')
  t.status = 'rejected'
  t.fo_petugas_id = 1
  t.alasan_reject = alasan
  return { ...t }
}

export async function mockFOPanggilUlang(ticketID) {
  await delay(400)
  const t = tickets.find(t => t.id === ticketID)
  if (!t) throw new Error('Ticket tidak ditemukan')
  t.called_at = new Date().toISOString()
  return { ...t }
}

export async function mockFOSkip(ticketID) {
  await delay(300)
  const t = tickets.find(t => t.id === ticketID)
  if (!t) throw new Error('Ticket tidak ditemukan')
  t.status = 'waiting_fo'
  t.fo_petugas_id = null
  return { ...t }
}

export async function mockGeraiGetWaitingList(geraiID) {
  await delay()
  const g = geraiList.find(x => x.id === geraiID)
  return tickets.filter(t => {
    if (t.status !== 'waiting_gerai') return false
    const svc = services.find(s => s.id === t.service_id)
    return svc?.instansi?.id === g?.opd_id
  })
}

export async function mockGeraiGetCurrentCalling(geraiID) {
  await delay()
  return tickets.find(t => t.status === 'calling_gerai' && t.counter_tujuan_id === geraiID) || null
}

export async function mockGeraiPanggilBerikutnya(geraiID) {
  await delay(500)
  const g = geraiList.find(x => x.id === geraiID)
  const next = tickets.find(t => {
    if (t.status !== 'waiting_gerai') return false
    const svc = services.find(s => s.id === t.service_id)
    return svc?.instansi?.id === g?.opd_id
  })
  if (!next) throw new Error('Tidak ada antrian')
  next.status = 'calling_gerai'
  next.counter_tujuan_id = geraiID
  next.counter_tujuan = g?.nama || ''
  next.gerai_petugas_id = 1
  next.called_at = new Date().toISOString()
  return { ...next }
}

export async function mockGeraiSelesai(ticketID, catatan) {
  await delay(400)
  const t = tickets.find(t => t.id === ticketID)
  if (!t) throw new Error('Ticket tidak ditemukan')
  t.status = 'done'
  t.gerai_petugas_id = 1
  t.catatan = catatan || null
  t.done_at = new Date().toISOString()
  return { ...t }
}

export async function mockGeraiPanggilUlang(ticketID) {
  await delay(400)
  const t = tickets.find(t => t.id === ticketID)
  if (!t) throw new Error('Ticket tidak ditemukan')
  t.called_at = new Date().toISOString()
  t.gerai_called_at = new Date().toISOString()
  return { ...t }
}

export async function mockGetAllGerai() {
  await delay()
  return [...geraiList]
}

export async function mockDisplayHistory() {
  await delay()
  const ts = (offsetMin) => Math.floor((NOW - offsetMin * 60000) / 1000)
  return [
    { queue_number: 'A-005', gerai_name: 'Loket 1 - Pendaftaran', status: 'Selesai', timestamp: ts(3) },
    { queue_number: 'A-004', gerai_name: 'Loket 1 - Pendaftaran', status: 'Selesai', timestamp: ts(9) },
    { queue_number: 'FO-003', gerai_name: 'Front Office', status: 'Tidak Hadir', timestamp: ts(15) },
    { queue_number: 'AB-002', gerai_name: 'Loket 2 - Pencetakan', status: 'Selesai', timestamp: ts(21) },
    { queue_number: 'AB-001', gerai_name: 'Loket 2 - Pencetakan', status: 'Selesai', timestamp: ts(27) },
  ]
}

export async function mockLogout() {
  await delay(200)
  return { success: true }
}

export async function mockLogoutAll() {
  await delay(200)
  return { success: true }
}
