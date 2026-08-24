package main

import (
	"context"
	"fmt"
	"log"
	"os"
	"os/exec"
	"path/filepath"
	"time"

	"github.com/wailsapp/wails/v2/pkg/runtime"
)

type App struct {
	ctx   context.Context
	cfg   AppConfig
	mode  string
	audio *AudioPlayer
}

func NewApp(cfg AppConfig, displayMode bool) *App {
	mode := "normal"
	if displayMode {
		mode = "display"
	}
	return &App{
		cfg:   cfg,
		mode:  mode,
		audio: NewAudioPlayer(),
	}
}

func (a *App) startup(ctx context.Context) {
	a.ctx = ctx
	a.audio.SetContext(ctx)
	if a.mode != "display" {
		return
	}

	go func() {
		time.Sleep(500 * time.Millisecond)

		monitors := getMonitors()
		if len(monitors) < 2 {
			return
		}
		var target *MonitorInfo
		for i := range monitors {
			if !monitors[i].IsPrimary {
				target = &monitors[i]
				break
			}
		}
		if target == nil {
			return
		}

		runtime.WindowSetPosition(ctx, target.X+10, target.Y+10)
		time.Sleep(150 * time.Millisecond)
		runtime.WindowSetSize(ctx, target.Width, target.Height)
		time.Sleep(150 * time.Millisecond)
		runtime.WindowFullscreen(ctx)
	}()
}

func (a *App) GetMode() string {
	return a.mode
}

func (a *App) SetFullscreen(enabled bool) {
	if a.ctx == nil {
		return
	}
	if enabled {
		runtime.WindowFullscreen(a.ctx)
	} else {
		runtime.WindowUnfullscreen(a.ctx)
	}
}

// primaryMonitor returns the primary monitor, falling back to the first
// detected monitor. Monitor kedua dikhususkan untuk display caller.
func (a *App) primaryMonitor() *MonitorInfo {
	monitors := getMonitors()
	if len(monitors) == 0 {
		return nil
	}
	for i := range monitors {
		if monitors[i].IsPrimary {
			return &monitors[i]
		}
	}
	return &monitors[0]
}

// SetStaffWindow places the window on the right side of the primary monitor
// at 1/4 screen width and 3/4 screen height.
func (a *App) SetStaffWindow() {
	if a.ctx == nil {
		return
	}

	primary := a.primaryMonitor()
	if primary == nil {
		return
	}

	width := primary.Width / 4
	height := primary.Height * 3 / 4
	x := primary.X + primary.Width - width
	y := primary.Y + primary.Height - height

	log.Printf("SetStaffWindow: primary=(%d,%d,%dx%d) window=(%d,%d,%dx%d) backend=%s",
		primary.X, primary.Y, primary.Width, primary.Height, x, y, width, height, displayBackend())

	runtime.WindowUnfullscreen(a.ctx)
	time.Sleep(120 * time.Millisecond)
	runtime.WindowSetSize(a.ctx, width, height)
	time.Sleep(120 * time.Millisecond)
	runtime.WindowSetPosition(a.ctx, x, y)

	time.Sleep(200 * time.Millisecond)
	wx, wy := runtime.WindowGetPosition(a.ctx)
	ww, wh := runtime.WindowGetSize(a.ctx)
	log.Printf("SetStaffWindow verify: pos=(%d,%d) size=(%d,%d)", wx, wy, ww, wh)
}

// SetHomeWindow restores the window to its default size (1280x900) and
// centers it on the primary monitor.
func (a *App) SetHomeWindow() {
	if a.ctx == nil {
		return
	}

	primary := a.primaryMonitor()
	if primary == nil {
		return
	}

	const width = 1280
	const height = 900
	x := primary.X + (primary.Width-width)/2
	y := primary.Y + (primary.Height-height)/2

	log.Printf("SetHomeWindow: primary=(%d,%d,%dx%d) window=(%d,%d,%dx%d) backend=%s",
		primary.X, primary.Y, primary.Width, primary.Height, x, y, width, height, displayBackend())

	runtime.WindowUnfullscreen(a.ctx)
	time.Sleep(120 * time.Millisecond)
	runtime.WindowSetSize(a.ctx, width, height)
	time.Sleep(120 * time.Millisecond)
	runtime.WindowSetPosition(a.ctx, x, y)

	time.Sleep(200 * time.Millisecond)
	wx, wy := runtime.WindowGetPosition(a.ctx)
	ww, wh := runtime.WindowGetSize(a.ctx)
	log.Printf("SetHomeWindow verify: pos=(%d,%d) size=(%d,%d)", wx, wy, ww, wh)
}

func (a *App) GetConfig() AppConfig {
	return a.cfg
}

// PlayCallAudio mengantre panggilan suara (chime + TTS) dan memutarnya
// berurutan di sisi Go. Binding dipanggil dari React.
func (a *App) PlayCallAudio(payload CallPayload) string {
	if a.audio == nil {
		return "unavailable"
	}
	a.audio.enqueue(payload)
	return fmt.Sprintf("queued:%s", payload.QueueNumber)
}

// PlayTestAudio memutar chime lalu mengantre pengumuman uji saat petugas
// menekan tombol "Aktifkan Suara" pada layar display.
func (a *App) PlayTestAudio() string {
	if a.audio == nil {
		return "unavailable"
	}
	chime := filepath.Join(a.audio.tmpDir, "chime.wav")
	_ = a.audio.writeChime(chime)
	_ = a.audio.playFile(chime)
	a.audio.enqueue(CallPayload{
		QueueNumber: "TEST",
		GeraiName:   "Layar Display",
		Agency:      "Sistem Antrian",
	})
	return "ok"
}

// SynthSpeech menghasilkan audio pengumuman TTS sesuai voice terpilih dan
// mengembalikannya sebagai data URL. Dipakai frontend saat audio player OS
// tidak tersedia sehingga suara tetap konsisten di semua platform.
func (a *App) SynthSpeech(text, voice string, rate, pitch float64) (string, error) {
	if a.audio == nil {
		return "", fmt.Errorf("audio unavailable")
	}
	return a.audio.synthDataURL(text, voice, rate, pitch)
}

// GetChimeDataURL mengembalikan nada bel terpilih sebagai data URL WAV untuk
// diputar engine web (fallback saat player OS tidak tersedia).
func (a *App) GetChimeDataURL(name string) string {
	if a.audio == nil {
		return ""
	}
	return a.audio.chimeDataURL(name)
}

func (a *App) IsAudioAvailable() bool {
	return a.audio != nil && a.audio.Available()
}

func (a *App) UnlockAudio() string {
	if a.audio == nil {
		return "unavailable"
	}
	return "ok"
}

func (a *App) OpenDisplay() (int, error) {
	monitors := getMonitors()
	if monitors == nil {
		return 0, fmt.Errorf("gagal mendeteksi monitor")
	}

	lock, err := acquireDisplayLock()
	if err != nil {
		return len(monitors), err
	}

	if len(monitors) < 2 {
		lock.release()
		return len(monitors), fmt.Errorf("Monitor kedua tidak ditemukan — hanya %d monitor terdeteksi", len(monitors))
	}

	var target *MonitorInfo
	for i := range monitors {
		if !monitors[i].IsPrimary {
			target = &monitors[i]
			break
		}
	}
	if target == nil {
		lock.release()
		return len(monitors), fmt.Errorf("Monitor kedua tidak ditemukan — hanya 1 monitor (primary) terdeteksi")
	}

	bin, err := os.Executable()
	if err != nil {
		lock.release()
		return len(monitors), fmt.Errorf("gagal mendapatkan path binary: %w", err)
	}

	env := os.Environ()
	env = append(env, "ANTRIAN_MODE=display")
	env = append(env,
		fmt.Sprintf("ANTRIAN_DISPLAY_LEFT=%d", target.X),
		fmt.Sprintf("ANTRIAN_DISPLAY_TOP=%d", target.Y),
		fmt.Sprintf("ANTRIAN_DISPLAY_WIDTH=%d", target.Width),
		fmt.Sprintf("ANTRIAN_DISPLAY_HEIGHT=%d", target.Height),
	)

	cmd := exec.Command(bin)
	cmd.Env = env
	cmd.Stdout = os.Stdout
	cmd.Stderr = os.Stderr
	if child := lock.childExtra(); child != nil {
		cmd.ExtraFiles = []*os.File{child}
		cmd.Env = append(cmd.Env, displayLockFDEnv+"=3")
	} else if path := lock.heldPath(); path != "" {
		cmd.Env = append(cmd.Env, displayLockPathEnv+"="+path)
	}

	if err := cmd.Start(); err != nil {
		lock.release()
		return len(monitors), err
	}

	lock.handoff()
	return len(monitors), nil
}
