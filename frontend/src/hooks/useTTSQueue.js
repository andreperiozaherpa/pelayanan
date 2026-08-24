import { useRef, useCallback } from 'react'

export function useTTSQueue() {
  const queueRef = useRef([])
  const speakingRef = useRef(false)
  const synthRef = useRef(window.speechSynthesis)

  const speakNext = useCallback(() => {
    if (speakingRef.current || queueRef.current.length === 0) return

    const item = queueRef.current.shift()
    speakingRef.current = true

    const text = `Nomor ${item.nomor}, dipanggil di ${item.tujuan}`
    const utterance = new SpeechSynthesisUtterance(text)
    utterance.lang = 'id-ID'
    utterance.rate = 0.9

    utterance.onend = () => {
      speakingRef.current = false
      speakNext()
    }

    synthRef.current.speak(utterance)
  }, [])

  const enqueue = useCallback((item) => {
    queueRef.current.push(item)
    if (!speakingRef.current) {
      speakNext()
    }
  }, [speakNext])

  return { enqueue }
}
