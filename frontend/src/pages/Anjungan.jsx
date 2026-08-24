import { useState, useEffect, useMemo } from 'react'
import { GetServices, AmbilNomorForm } from '../services/api'
import { BackButton, GlassButton, GlassCard, GlassInput, GlassIconWrap } from '../components/ui'

const SERVICE_ICONS = {
  'GR-001': (
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
      <polyline points="14 2 14 8 20 8" />
      <line x1="16" y1="13" x2="8" y2="13" />
      <line x1="16" y1="17" x2="8" y2="17" />
    </svg>
  ),
  'GR-002': (
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
      <circle cx="12" cy="12" r="10" />
      <path d="M12 6v6l4 2" />
    </svg>
  ),
  'GR-003': (
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
    </svg>
  ),
  'GR-004': (
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
      <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
    </svg>
  ),
  'GR-005': (
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
      <path d="M3 21h18" />
      <path d="M5 21V7l7-4 7 4v14" />
      <path d="M9 21v-4h6v4" />
    </svg>
  ),
}

const DEFAULT_COLORS = {
  'GR-001': { color: 'var(--primary)', bg: 'rgba(20,159,181,0.15)' },
  'GR-002': { color: 'var(--accent)', bg: 'rgba(248,171,58,0.15)' },
  'GR-003': { color: '#f9c476', bg: 'rgba(249,196,118,0.15)' },
  'GR-004': { color: '#6bd59e', bg: 'rgba(107,213,158,0.15)' },
  'GR-005': { color: '#9c6bff', bg: 'rgba(156,107,255,0.15)' },
}

const KioskIcon = () => (
  <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
    <rect x="3" y="2" width="18" height="15" rx="2.5" />
    <line x1="7.5" y1="6" x2="16.5" y2="6" />
    <line x1="12" y1="17" x2="12" y2="20.5" />
    <line x1="8" y1="20.5" x2="16" y2="20.5" />
    <line x1="6" y1="22" x2="18" y2="22" />
  </svg>
)

const CheckBigIcon = () => (
  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
    <polyline points="9 11 12 14 22 4" />
    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
  </svg>
)

const DEFAULT_PALETTE = { color: 'var(--primary)', bg: 'rgba(20,159,181,0.15)' }

function serviceKode(s) {
  return s?.instansi?.gerai?.kode || ''
}

function serviceLogo(s) {
  return s?.logo || null
}

const StoreIcon = () => (
  <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
    <path d="M3 9l1.5-5h15L21 9" />
    <path d="M3 9v11h18V9" />
    <path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0" />
    <line x1="7" y1="20" x2="7" y2="14" />
    <line x1="12" y1="20" x2="12" y2="14" />
    <line x1="17" y1="20" x2="17" y2="14" />
  </svg>
)

function ServiceIcon({ service, size }) {
  const kode = serviceKode(service)
  const logo = serviceLogo(service)

  if (logo) {
    return (
      <img
        src={logo}
        alt={service?.nama || 'Logo'}
        width={size || 32}
        height={size || 32}
        className="service-logo"
      />
    )
  }

  return SERVICE_ICONS[kode] || SERVICE_ICONS['GR-001']
}

function GeraiIcon({ gerai, size }) {
  const kode = gerai?.kode || ''
  const logo = gerai?.logo || null

  if (logo) {
    return (
      <img
        src={logo}
        alt={gerai?.nama || 'Logo'}
        width={size || 32}
        height={size || 32}
        className="service-logo"
      />
    )
  }

  return SERVICE_ICONS[kode] || <StoreIcon />
}

const ANJUNGAN_STEPS = ['Instansi', 'Layanan', 'Data']

function KioskHeader({ step, icon, title, subtitle, palette }) {
  const { color = 'var(--primary)', bg = 'rgba(20,159,181,0.15)' } = palette || DEFAULT_PALETTE
  return (
    <GlassCard className="anjungan-header-glass" padding="md">
      <div className="anjungan-header">
        <GlassIconWrap size="md" color={bg}>
          {icon}
        </GlassIconWrap>
        <div className="anjungan-header-body">
          <div className="anjungan-steps" aria-label="Langkah">
            {ANJUNGAN_STEPS.map((label, i) => (
              <div
                key={label}
                className={`anjungan-step ${i < step ? 'is-done' : ''} ${i === step ? 'is-active' : ''}`}
              >
                <span className="anjungan-step-dot">{i < step ? '✓' : i + 1}</span>
                <span className="anjungan-step-label">{label}</span>
                {i < ANJUNGAN_STEPS.length - 1 && <span className="anjungan-step-link" />}
              </div>
            ))}
          </div>
          <div className="anjungan-header-text">
            <h1 className="page-title" style={{ color }}>{title}</h1>
            <p className="page-sub">{subtitle}</p>
          </div>
        </div>
      </div>
    </GlassCard>
  )
}

export default function Anjungan() {
  const [services, setServices] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [instansi, setInstansi] = useState(null)
  const [selected, setSelected] = useState(null)
  const [formValues, setFormValues] = useState({})
  const [submitting, setSubmitting] = useState(false)
  const [result, setResult] = useState(null)

  useEffect(() => {
    GetServices()
      .then(s => { setServices(s); setLoading(false) })
      .catch(err => { setError(err?.message || 'Gagal memuat data layanan'); setLoading(false) })
  }, [])

  const instansiList = useMemo(() => {
    const groups = new Map()
    for (const s of services) {
      const inst = s.instansi || { id: null, kode: '', nama: 'Umum', gerai: null }
      const key = inst.id ?? 'umum'
      if (!groups.has(key)) groups.set(key, { instansi: inst, services: [] })
      groups.get(key).services.push(s)
    }
    return [...groups.values()]
  }, [services])

  function setField(name, value) {
    setFormValues(prev => ({ ...prev, [name]: value }))
  }

  function handlePilihInstansi(inst) {
    setError('')
    setResult(null)
    setSelected(null)
    setFormValues({})
    setInstansi(inst)
  }

  function handlePilih(service) {
    setError('')
    setResult(null)
    if (!service.fields || service.fields.length === 0) {
      submitRequest(service, {})
      return
    }
    setSelected(service)
    setFormValues({})
  }

  async function submitRequest(service, values) {
    setSubmitting(true)
    setError('')
    try {
      const ticket = await AmbilNomorForm(service.id, values, '')
      setResult({ ...ticket, service_name: service.nama })
    } catch (err) {
      setError(err?.message || 'Gagal mengambil nomor')
    } finally {
      setSelected(null)
      setSubmitting(false)
    }
  }

  function handleSubmitForm(e) {
    e.preventDefault()
    const missing = (selected.fields || [])
      .filter(f => f.required)
      .filter(f => {
        const v = formValues[f.name]
        if (Array.isArray(v)) return v.length === 0
        return v === undefined || v === null || String(v).trim() === ''
      })
      .map(f => f.label)
    if (missing.length > 0) {
      setError('Lengkapi data wajib: ' + missing.join(', '))
      return
    }
    submitRequest(selected, formValues)
  }

  function resetAll() {
    setResult(null)
    setError('')
    setFormValues({})
  }

  if (result) {
    return (
      <div className="page page-centered">
        <BackButton />
        <GlassCard padding="xl" className="ticket-result-glass">
          <GlassIconWrap size="xl" color="rgba(20,159,181,0.10)">
            <CheckBigIcon />
          </GlassIconWrap>
          <p className="ticket-result-label">Nomor Antrian Anda</p>
          <div className="ticket-result-number">{result.nomor_antrian}</div>
          <div className="ticket-result-service">{result.service_name || ''}</div>
          <div className="ticket-result-time">
            {new Date().toLocaleTimeString('id-ID')}
          </div>
          <GlassButton variant="primary" fullWidth onClick={resetAll}>
            Ambil Nomor Lain
          </GlassButton>
        </GlassCard>
      </div>
    )
  }

  if (selected) {
    const kode = serviceKode(selected)
    const palette = DEFAULT_COLORS[kode] || DEFAULT_PALETTE
    return (
      <div className="page page-centered">
        <BackButton onBack={() => setSelected(null)} />
        <KioskHeader
          step={2}
          palette={palette}
          icon={<ServiceIcon service={selected} size={32} />}
          title={selected.nama}
          subtitle="Lengkapi data berikut untuk mengambil nomor antrian"
        />

        {error && <p className="form-error">{error}</p>}

        <GlassCard padding="lg" className="kiosk-form-glass">
          <form onSubmit={handleSubmitForm}>
            {selected.fields.map(field => (
              <div className="kiosk-field" key={field.id || field.name}>
                <label className="kiosk-field-label">
                  {field.label}
                  {field.required ? <span className="kiosk-required"> *</span> : ''}
                </label>
                <FieldInput field={field} value={formValues[field.name]} onChange={setField} />
              </div>
            ))}
            <GlassButton variant="primary" fullWidth type="submit" disabled={submitting} loading={submitting}>
              {submitting ? 'Memproses...' : 'Ambil Nomor Antrian'}
            </GlassButton>
          </form>
        </GlassCard>
      </div>
    )
  }

  const currentInstansi = instansiList.find(g => g.instansi.id === instansi?.id)

  if (instansi && !currentInstansi) {
    return (
      <div className="page page-centered">
        <BackButton onBack={() => setInstansi(null)} />
        <KioskHeader
          step={1}
          icon={<GeraiIcon gerai={instansi.gerai} size={32} />}
          title={instansi.nama}
          subtitle="Tidak ada layanan tersedia pada instansi ini"
        />
        <GlassButton variant="primary" onClick={() => setInstansi(null)}>
          Pilih Instansi Lain
        </GlassButton>
      </div>
    )
  }

  if (instansi) {
    const kode = serviceKode({ instansi: currentInstansi.instansi })
    const palette = DEFAULT_COLORS[kode] || DEFAULT_PALETTE
    return (
      <div className="page page-centered">
        <BackButton onBack={() => setInstansi(null)} />
        <KioskHeader
          step={1}
          palette={palette}
          icon={<GeraiIcon gerai={currentInstansi.instansi.gerai} size={32} />}
          title={currentInstansi.instansi.nama}
          subtitle="Silakan pilih jenis layanan"
        />

        {error && <p className="form-error">{error}</p>}

        <div className="service-grid">
          {currentInstansi.services.map(s => {
            const skode = serviceKode(s)
            const spalette = DEFAULT_COLORS[skode] || DEFAULT_PALETTE
            return (
              <button
                key={s.id}
                className="glass-card service-card"
                style={{ '--card-color': spalette.color, '--card-bg': spalette.bg }}
                onClick={() => handlePilih(s)}
              >
                <span className="service-icon-wrap" style={{ '--icon-bg': spalette.bg }}>
                  <ServiceIcon service={s} size={32} />
                </span>
                <span className="service-name">{s.nama}</span>
                <span className="service-code-badge">{s.deskripsi || s.instansi?.gerai?.nama || skode}</span>
              </button>
            )
          })}
        </div>
      </div>
    )
  }

  return (
    <div className="page page-centered">
      <BackButton />
      <KioskHeader
        step={0}
        icon={<KioskIcon />}
        title="Sistem Antrian"
        subtitle="Silakan pilih instansi pelayanan tujuan"
      />

      {error && <p className="form-error">{error}</p>}

      {loading ? (
        <div className="loading-glass">Memuat data...</div>
      ) : (
        <div className="service-grid">
          {instansiList.map(({ instansi: inst, services: svc }) => {
            const kode = inst.gerai?.kode || ''
            const palette = DEFAULT_COLORS[kode] || DEFAULT_PALETTE
            return (
              <button
                key={inst.id ?? 'umum'}
                className="glass-card service-card"
                style={{ '--card-color': palette.color, '--card-bg': palette.bg }}
                onClick={() => handlePilihInstansi(inst)}
              >
                <span className="service-icon-wrap" style={{ '--icon-bg': palette.bg }}>
                  <GeraiIcon gerai={inst.gerai} size={32} />
                </span>
                <span className="service-name">{inst.nama}</span>
                <span className="service-code-badge">{svc.length} Layanan</span>
              </button>
            )
          })}
        </div>
      )}
    </div>
  )
}

function FieldInput({ field, value, onChange }) {
  const label = field.label

  if (field.type === 'select') {
    return (
      <GlassInput type="select" value={value || ''} onChange={e => onChange(field.name, e.target.value)}>
        <option value="">-- Pilih {label} --</option>
        {(field.options || []).map(opt => (
          <option key={opt} value={opt}>{opt}</option>
        ))}
      </GlassInput>
    )
  }

  if (field.type === 'checkbox') {
    const options = field.options || []
    const selected = Array.isArray(value) ? value : []
    return (
      <div className="kiosk-checkbox-group">
        {options.map(opt => {
          const val = opt?.value ?? opt
          const checked = selected.includes(val)
          return (
            <label key={val} className={`kiosk-checkbox ${checked ? 'is-checked' : ''}`}>
              <input
                type="checkbox"
                checked={checked}
                onChange={() => {
                  const next = checked
                    ? selected.filter(x => x !== val)
                    : [...selected, val]
                  onChange(field.name, next)
                }}
              />
              <span>{opt?.label ?? opt}</span>
            </label>
          )
        })}
      </div>
    )
  }

  if (field.type === 'textarea') {
    return (
      <GlassInput
        textarea
        placeholder={label}
        value={value || ''}
        onChange={e => onChange(field.name, e.target.value)}
      />
    )
  }

  if (field.type === 'file') {
    return (
      <input
        type="file"
        className="glass-input kiosk-file-input"
        onChange={e => onChange(field.name, e.target.files?.[0] || null)}
      />
    )
  }

  return (
    <GlassInput
      type={field.type === 'number' ? 'number' : 'text'}
      placeholder={label}
      value={value || ''}
      onChange={e => onChange(field.name, e.target.value)}
    />
  )
}
