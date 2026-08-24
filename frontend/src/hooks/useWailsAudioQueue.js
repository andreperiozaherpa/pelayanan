import { useState, useEffect, useRef, useCallback } from 'react'
import { EventsOn, EventsOff } from '../../wailsjs/runtime'
import { PlayCallAudio, PlayTestAudio, IsAudioAvailable, SynthSpeech, GetChimeDataURL } from '../../wailsjs/go/main/App'

function spellChars(s) {
  return s.split('').join(', ')
}

const ID_ONES = ['nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan']

function numberToWords(n) {
  if (n === 0) return 'nol'
  if (n < 0) return numberToWords(-n)
  const parts = []
  const th = Math.floor(n / 1000)
  if (th > 0) {
    parts.push(th === 1 ? 'seribu' : `${numberToWords(th)} ribu`)
    n %= 1000
  }
  const h = Math.floor(n / 100)
  if (h > 0) {
    parts.push(h === 1 ? 'seratus' : `${ID_ONES[h]} ratus`)
    n %= 100
  }
  const t = Math.floor(n / 10)
  const u = n % 10
  if (t > 0) {
    if (t === 1 && u === 0) parts.push('sepuluh')
    else if (t === 1 && u === 1) parts.push('sebelas')
    else if (t === 1) parts.push(`${ID_ONES[u]} belas`)
    else {
      parts.push(`${ID_ONES[t]} puluh`)
      if (u > 0) parts.push(ID_ONES[u])
    }
  } else if (u > 0) {
    parts.push(ID_ONES[u])
  }
  return parts.join(' ')
}

function numberSpell(num) {
  if (!num) return ''
  let letter = ''
  let digits = ''
  const dash = num.indexOf('-')
  if (dash >= 0) {
    letter = num.slice(0, dash)
    digits = num.slice(dash + 1)
  } else {
    const m = num.match(/^[^\d]*/)
    letter = m ? m[0] : ''
    digits = num.slice(letter.length)
  }
  const trimmed = digits.replace(/^0+/, '') || '0'
  const parts = []
  if (letter) parts.push(spellChars(letter))
  const n = parseInt(trimmed, 10)
  parts.push(Number.isNaN(n) ? spellChars(trimmed) : numberToWords(n))
  return parts.join(', ')
}

const TEXT = (c) => `Nomor antrian ${numberSpell(c.queue_number)}, silahkan menuju ${c.gerai_name || 'gerai'}.`

const CHIME_TONES = [
  { start: 0, freq: 880 },
  { start: 0.22, freq: 1174 },
]

function playChimeWeb() {
  const Ctx = window.AudioContext || window.webkitAudioContext
  if (!Ctx) return
  const ctx = new Ctx()
  const now = ctx.currentTime
  CHIME_TONES.forEach(({ start, freq }, i) => {
    const osc = ctx.createOscillator()
    const gain = ctx.createGain()
    osc.frequency.value = freq
    gain.gain.setValueAtTime(0, now + start)
    gain.gain.linearRampToValueAtTime(0.18, now + start + 0.02)
    gain.gain.exponentialRampToValueAtTime(0.0001, now + start + 0.32)
    osc.connect(gain).connect(ctx.destination)
    osc.start(now + start)
    osc.stop(now + start + 0.36)
  })
  setTimeout(() => ctx.close().catch(() => {}), 800)
}

const FEMALE_VOICE_HINTS = /gadis|damayanti|putri|icha|noor|female|wanita/i

function pickIndonesianVoice(voices) {
  const list = voices || []
  const idVoices = list.filter(v => /^id/i.test(v.lang || ''))
  return (
    idVoices.find(v => FEMALE_VOICE_HINTS.test(v.name || '')) ||
    list.find(v => /^id[-_]ID$/i.test(v.lang || '')) ||
    idVoices[0] ||
    list.find(v => v.default) ||
    list[0]
  )
}

/**
 * Audio queue terpadu untuk Display Caller.
 *
 * engine 'go'  : playback via Go backend (Wails binding PlayCallAudio) —
 *                antrean & urutan chime→TTS ditangani Go (mutex, berurutan).
 * engine 'web' : fallback Web Speech API + Web Audio chime (untuk browser dev /
 *                bila audio player OS tidak tersedia). Antrean lokal berurutan.
 *
 * Selalu memutar satu panggilan dalam satu waktu — tidak pernah menimpa.
 */
export function useWailsAudioQueue({ onPlayStart, onPlayEnd, tts, chime } = {}) {
  const [engine, setEngine] = useState('none')
  const [isUnlocked, setIsUnlocked] = useState(false)
  const [status, setStatus] = useState('idle')

  const queueRef = useRef([])
  const pendingRef = useRef([])
  const playingRef = useRef(false)
  const announcedRef = useRef('')
  const callbacksRef = useRef({ onPlayStart, onPlayEnd })
  callbacksRef.current = { onPlayStart, onPlayEnd }
  const ttsRef = useRef({ enabled: true, rate: 0.9, pitch: 1, voice: 'google' })
  ttsRef.current = { ...ttsRef.current, ...(tts || {}) }
  const chimeRef = useRef('airport-3tone')
  chimeRef.current = chime || 'airport-3tone'

  const speakViaBrowser = useCallback((call, finish) => {
    const synth = window.speechSynthesis
    if (!synth) {
      finish()
      return
    }
    const utterance = new SpeechSynthesisUtterance(TEXT(call))
    utterance.lang = 'id-ID'
    utterance.rate = Number(ttsRef.current.rate) || 0.9
    utterance.pitch = Number(ttsRef.current.pitch) || 1
    const voice = pickIndonesianVoice(synth.getVoices?.() || [])
    if (voice) utterance.voice = voice
    utterance.onend = finish
    utterance.onerror = finish
    window.setTimeout(() => synth.speak(utterance), 350)
  }, [])

  const webNext = useCallback(() => {
    if (playingRef.current) return
    const call = queueRef.current.shift()
    if (!call) {
      setStatus('idle')
      return
    }
    playingRef.current = true
    setStatus('playing')
    callbacksRef.current.onPlayStart?.(call)

    const finish = () => {
      playingRef.current = false
      callbacksRef.current.onPlayEnd?.(call)
      webNext()
    }

    // Prioritaskan suara dari TTS backend (Google/Gadis/Ardi → base64) agar
    // konsisten di semua platform; browser speech hanya cadangan.
    const playSynth = () => {
      SynthSpeech(TEXT(call), ttsRef.current.voice || 'google', Number(ttsRef.current.rate) || 0.9, Number(ttsRef.current.pitch) || 1)
        .then(dataUrl => {
          if (!dataUrl) throw new Error('no audio')
          const audio = new Audio(dataUrl)
          audio.onended = finish
          audio.onerror = finish
          audio.play().catch(() => speakViaBrowser(call, finish))
        })
        .catch(() => speakViaBrowser(call, finish))
    }

    // Nada bel pilihan admin diputar dulu (data URL dari Go), lalu TTS.
    const playChime = () => new Promise((resolve) => {
      const name = chimeRef.current || 'airport-3tone'
      if (name === 'none') { resolve(); return }
      if (window.go?.main?.App?.GetChimeDataURL) {
        GetChimeDataURL(name)
          .then(url => {
            if (!url) { resolve(); return }
            const audio = new Audio(url)
            audio.onended = resolve
            audio.onerror = resolve
            audio.play().catch(resolve)
          })
          .catch(resolve)
      } else {
        playChimeWeb()
        window.setTimeout(resolve, 500)
      }
    })

    playChime().then(() => {
      if (window.go?.main?.App?.SynthSpeech) {
        playSynth()
      } else {
        speakViaBrowser(call, finish)
      }
    })
  }, [speakViaBrowser])

  const goPlay = useCallback((call) => {
    const payload = {
      queue_number: call.queue_number,
      gerai_name: call.gerai_name || '',
      agency: call.agency || '',
      timestamp: call.timestamp || Date.now(),
      voice: ttsRef.current.voice || 'google',
      rate: Number(ttsRef.current.rate) || 0.9,
      pitch: Number(ttsRef.current.pitch) || 1,
      chime_sound: chimeRef.current || 'airport-3tone',
    }
    return PlayCallAudio(payload).catch(() => {})
  }, [])

  const enqueue = useCallback((call) => {
    if (!call?.queue_number) return
    const key = `${call.queue_number}|${call.timestamp || ''}`
    if (key === announcedRef.current) return
    announcedRef.current = key

    if (!ttsRef.current.enabled) return

    if (engine === 'none') {
      pendingRef.current.push(call)
      return
    }
    if (engine === 'go') {
      goPlay(call)
      return
    }
    queueRef.current.push(call)
    webNext()
  }, [engine, goPlay, webNext])

  const unlock = useCallback(async () => {
    if (engine === 'go') {
      try {
        await PlayTestAudio()
      } catch {
        /* audio test tidak berhasil */
      }
    } else if (engine === 'web') {
      const Ctx = window.AudioContext || window.webkitAudioContext
      if (Ctx) {
        const ctx = new Ctx()
        if (ctx.state === 'suspended') ctx.resume().catch(() => {})
        playChimeWeb()
      }
      if (window.go?.main?.App?.SynthSpeech) {
        SynthSpeech('Test suara', ttsRef.current.voice || 'google', Number(ttsRef.current.rate) || 0.9, Number(ttsRef.current.pitch) || 1)
          .then(url => {
            if (url) new Audio(url).play().catch(() => {})
          }).catch(() => {})
      } else if (window.speechSynthesis) {
        const synth = window.speechSynthesis
        synth.cancel()
        const probe = new SpeechSynthesisUtterance('Test suara')
        probe.lang = 'id-ID'
        probe.volume = 0.01
        synth.speak(probe)
      }
    }
    setIsUnlocked(true)
  }, [engine])

  useEffect(() => {
    let cancelled = false

    async function detect() {
      if (window.go?.main?.App?.PlayCallAudio) {
        try {
          const available = await IsAudioAvailable()
          if (!cancelled) setEngine(available ? 'go' : 'web')
          return
        } catch {
          /* lanjut deteksi fallback */
        }
      }
      if (window.speechSynthesis) setEngine('web')
    }
    detect()

    return () => { cancelled = true }
  }, [])

  useEffect(() => {
    if (engine === 'none') return

    const pending = pendingRef.current
    pendingRef.current = []

    if (engine === 'go') {
      pending.forEach(goPlay)
      return
    }
    pending.forEach(call => queueRef.current.push(call))
    webNext()
  }, [engine, goPlay, webNext])

  useEffect(() => {
    if (engine !== 'go') return
    EventsOn('audio:start', (call) => callbacksRef.current.onPlayStart?.(call))
    EventsOn('audio:end', (call) => callbacksRef.current.onPlayEnd?.(call))
    return () => {
      EventsOff('audio:start')
      EventsOff('audio:end')
    }
  }, [engine])

  return { engine, isUnlocked, status, enqueue, unlock }
}
