package main

import (
	"context"
	"encoding/base64"
	"encoding/binary"
	"fmt"
	"io"
	"math"
	"net/http"
	"net/url"
	"os"
	"os/exec"
	"path/filepath"
	"strconv"
	"strings"
	"sync"
	"time"
	"unicode"

	"github.com/wailsapp/wails/v2/pkg/runtime"
)

type CallPayload struct {
	QueueNumber string  `json:"queue_number"`
	GeraiName   string  `json:"gerai_name"`
	Agency      string  `json:"agency"`
	Timestamp   int64   `json:"timestamp"`
	Voice       string  `json:"voice"`
	Rate        float64 `json:"rate"`
	Pitch       float64 `json:"pitch"`
	ChimeSound  string  `json:"chime_sound"`
}

type AudioPlayer struct {
	mu      sync.Mutex
	queue   []CallPayload
	playing bool
	lastID  string

	ctx    context.Context
	player string
	tmpDir string
	play   func(path string) error
	synth  func(text, voice string, rate, pitch float64) (string, error)
}

func NewAudioPlayer() *AudioPlayer {
	p := &AudioPlayer{player: detectAudioPlayer()}
	if dir, err := os.MkdirTemp("", "antrian-audio-*"); err == nil {
		p.tmpDir = dir
	}
	p.play = p.playFile
	p.synth = p.synthTTS
	return p
}

func detectAudioPlayer() string {
	for _, name := range []string{"ffplay", "gst-launch-1.0", "aplay"} {
		if _, err := exec.LookPath(name); err == nil {
			return name
		}
	}
	return ""
}

func (p *AudioPlayer) SetContext(ctx context.Context) {
	p.ctx = ctx
}

func (p *AudioPlayer) Available() bool {
	return p.player != ""
}

func (p *AudioPlayer) enqueue(call CallPayload) {
	id := fmt.Sprintf("%s|%d", call.QueueNumber, call.Timestamp)
	p.mu.Lock()
	defer p.mu.Unlock()
	if call.QueueNumber != "" && id == p.lastID {
		return
	}
	p.lastID = id
	p.queue = append(p.queue, call)
	if !p.playing {
		p.playing = true
		go p.worker()
	}
}

func (p *AudioPlayer) worker() {
	for {
		p.mu.Lock()
		if len(p.queue) == 0 {
			p.playing = false
			p.mu.Unlock()
			return
		}
		call := p.queue[0]
		p.queue = p.queue[1:]
		p.mu.Unlock()

		p.playCall(call)
	}
}

func (p *AudioPlayer) playCall(call CallPayload) {
	p.emit("audio:start", call)
	defer p.emit("audio:end", call)

	// Nada bel pilihan admin terlebih dahulu, lalu pengumuman TTS.
	if call.ChimeSound != "none" {
		chime := filepath.Join(p.tmpDir, "chime.wav")
		if ok, err := p.writeChimeSound(call.ChimeSound, chime); err == nil && ok {
			_ = p.play(chime)
		}
	}

	if call.QueueNumber == "" {
		return
	}

	text := fmt.Sprintf("Nomor antrian %s, silahkan menuju %s.", announceNumber(call.QueueNumber), call.GeraiName)
	if speech, err := p.synth(text, call.Voice, call.Rate, call.Pitch); err == nil {
		_ = p.play(speech)
		_ = os.Remove(speech)
	}
}

// spellChars menyisipkan jeda (koma) di antara setiap karakter agar TTS
// membacanya huruf demi huruf / angka demi angka, tidak terburu-buru.
func spellChars(s string) string {
	parts := make([]string, 0, len(s))
	for _, r := range s {
		parts = append(parts, string(r))
	}
	return strings.Join(parts, ", ")
}

// announceNumber memformat nomor tiket untuk pengumuman: huruf dibaca
// terpisah dengan jeda, sedangkan angka dibaca sebagai bilangan (puluhan /
// ratusan). Contoh: "A-001" → "A, satu", "B-010" → "B, sepuluh",
// "GR-100" → "G, R, seratus".
func announceNumber(num string) string {
	if num == "" {
		return ""
	}
	var letter, digits string
	if idx := strings.Index(num, "-"); idx >= 0 {
		letter, digits = num[:idx], num[idx+1:]
	} else {
		i := 0
		for i < len(num) && !unicode.IsDigit(rune(num[i])) {
			i++
		}
		letter, digits = num[:i], num[i:]
	}
	digits = strings.TrimLeft(digits, "0")
	var parts []string
	if letter != "" {
		parts = append(parts, spellChars(letter))
	}
	if digits == "" {
		digits = "0"
	}
	if n, err := strconv.Atoi(digits); err == nil {
		parts = append(parts, numberToWords(n))
	} else {
		parts = append(parts, spellChars(digits))
	}
	return strings.Join(parts, ", ")
}

// numberToWords mengubah bilangan menjadi kata bahasa Indonesia.
// Contoh: 0→"nol", 10→"sepuluh", 25→"dua puluh lima", 100→"seratus".
func numberToWords(n int) string {
	if n == 0 {
		return "nol"
	}
	if n < 0 {
		return numberToWords(-n)
	}
	ones := []string{"nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan"}
	var parts []string

	if th := n / 1000; th > 0 {
		if th == 1 {
			parts = append(parts, "seribu")
		} else {
			parts = append(parts, numberToWords(th)+" ribu")
		}
		n %= 1000
	}
	if h := n / 100; h > 0 {
		if h == 1 {
			parts = append(parts, "seratus")
		} else {
			parts = append(parts, ones[h]+" ratus")
		}
		n %= 100
	}
	t, u := n/10, n%10
	if t > 0 {
		switch {
		case t == 1 && u == 0:
			parts = append(parts, "sepuluh")
		case t == 1 && u == 1:
			parts = append(parts, "sebelas")
		case t == 1:
			parts = append(parts, ones[u]+" belas")
		default:
			parts = append(parts, ones[t]+" puluh")
			if u > 0 {
				parts = append(parts, ones[u])
			}
		}
	} else if u > 0 {
		parts = append(parts, ones[u])
	}
	return strings.Join(parts, " ")
}

func (p *AudioPlayer) synthTTS(text, voice string, rate, pitch float64) (string, error) {
	if p.tmpDir == "" {
		return "", fmt.Errorf("tmp dir unavailable")
	}
	out := filepath.Join(p.tmpDir, fmt.Sprintf("tts-%d.mp3", time.Now().UnixNano()))

	// Suara formal (Microsoft Gadis/Ardi) via Edge TTS gratis sehingga
	// kecepatan & nada (SSML prosody) selalu berfungsi; bila Edge gagal
	// otomatis kembali ke Google Translate (wanita) yang lebih andal.
	// Suara "google" langsung memakai Google Translate (tanpa rate/pitch).
	if voice == "gadis" || voice == "ardi" {
		if err := synthEdgeTTS(out, voice, text, rate, pitch); err == nil {
			return out, nil
		}
	}

	ttsURL := "https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&tl=id&total=1&idx=0&textlen=" +
		url.QueryEscape(strconv.Itoa(len([]rune(text)))) +
		"&ttspeed=" + url.QueryEscape(fmt.Sprintf("%.2f", clampRate(rate))) +
		"&q=" + url.QueryEscape(text)
	if err := downloadTTS(ttsURL, out, ""); err == nil {
		return out, nil
	}

	return "", fmt.Errorf("tts synthesis gagal")
}

func clampRate(rate float64) float64 {
	if rate <= 0 {
		return 0.9
	}
	if rate < 0.24 {
		return 0.24
	}
	if rate > 4 {
		return 4
	}
	return rate
}

// synthDataURL menghasilkan pengumuman TTS dan mengembalikannya sebagai
// data URL audio untuk diputar oleh frontend (dipakai saat player OS tidak ada,
// mis. Windows tanpa ffplay — suara tetap konsisten dengan voice terpilih).
func (p *AudioPlayer) synthDataURL(text, voice string, rate, pitch float64) (string, error) {
	path, err := p.synth(text, voice, rate, pitch)
	if err != nil {
		return "", err
	}
	defer os.Remove(path)

	data, err := os.ReadFile(path)
	if err != nil {
		return "", err
	}

	return "data:audio/mpeg;base64," + base64.StdEncoding.EncodeToString(data), nil
}

func downloadTTS(rawURL, out, wantMime string) error {
	req, err := http.NewRequest(http.MethodGet, rawURL, nil)
	if err != nil {
		return err
	}
	req.Header.Set("User-Agent", "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36")
	req.Header.Set("Referer", "https://www.google.com/")

	client := &http.Client{Timeout: 20 * time.Second}
	resp, err := client.Do(req)
	if err != nil {
		return err
	}
	defer resp.Body.Close()

	if resp.StatusCode != http.StatusOK {
		return fmt.Errorf("tts status %d", resp.StatusCode)
	}
	if wantMime != "" {
		if ct := resp.Header.Get("Content-Type"); !strings.HasPrefix(ct, wantMime) {
			return fmt.Errorf("tts mime %s", ct)
		}
	}

	f, err := os.Create(out)
	if err != nil {
		return err
	}
	defer f.Close()

	_, err = io.Copy(f, resp.Body)
	return err
}

func (p *AudioPlayer) playFile(path string) error {
	if p.player == "" {
		return fmt.Errorf("tidak ada audio player")
	}
	switch p.player {
	case "ffplay":
		return exec.Command("ffplay", "-nodisp", "-autoexit", "-loglevel", "quiet", "-i", path).Run()
	case "gst-launch-1.0":
		return exec.Command("gst-launch-1.0", "-q", "playbin", "uri=file://"+filepath.ToSlash(path)).Run()
	default:
		return exec.Command("aplay", "-q", path).Run()
	}
}

func (p *AudioPlayer) writeChime(path string) error {
	const sampleRate = 44100
	duration := 0.42
	n := int(sampleRate * duration)
	buf := make([]byte, 44+n*2)

	copy(buf[0:4], "RIFF")
	binary.LittleEndian.PutUint32(buf[4:8], uint32(36+n*2))
	copy(buf[8:12], "WAVE")
	copy(buf[12:16], "fmt ")
	binary.LittleEndian.PutUint32(buf[16:20], 16)
	binary.LittleEndian.PutUint16(buf[20:22], 1)
	binary.LittleEndian.PutUint16(buf[22:24], 1)
	binary.LittleEndian.PutUint32(buf[24:28], sampleRate)
	binary.LittleEndian.PutUint32(buf[28:32], sampleRate*2)
	binary.LittleEndian.PutUint16(buf[32:34], 2)
	binary.LittleEndian.PutUint16(buf[34:36], 16)
	copy(buf[36:40], "data")
	binary.LittleEndian.PutUint32(buf[40:44], uint32(n*2))

	tones := [][2]float64{{0, 880}, {0.22, 1174}}
	for i := 0; i < n; i++ {
		t := float64(i) / sampleRate
		var sample float64
		for _, tone := range tones {
			start, freq := tone[0], tone[1]
			if t >= start && t < start+0.2 {
				env := math.Sin(math.Pi * (t - start) / 0.2)
				sample += 0.5 * env * math.Sin(2*math.Pi*freq*(t-start))
			}
		}
		val := int16(sample * 32767)
		binary.LittleEndian.PutUint16(buf[44+i*2:], uint16(val))
	}

	return os.WriteFile(path, buf, 0o600)
}

func (p *AudioPlayer) emit(name string, call CallPayload) {
	if p.ctx == nil {
		return
	}
	runtime.EventsEmit(p.ctx, name, call)
}
