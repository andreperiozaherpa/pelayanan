package main

import (
	"embed"
	"log"
	"os"

	"github.com/wailsapp/wails/v2"
	"github.com/wailsapp/wails/v2/pkg/options"
	"github.com/wailsapp/wails/v2/pkg/options/assetserver"
)

//go:embed all:frontend/dist
var assets embed.FS

func main() {
	// On Wayland compositors, top-level windows cannot be positioned by the
	// app (gtk_window_move is ignored). Force the X11/XWayland backend so
	// WindowSetPosition works as expected.
	//
	// Namun di XWayland, WebKitGTK tidak bisa memakai jalur video zero-copy
	// (dmabuf) sehingga frame video besar di-drop (terukur). Set
	// DISPLAY_BACKEND=wayland di .env untuk memakai Wayland native: video
	// mulus (0 frame drop), dengan konsekuensi posisi window display diatur
	// compositor (bukan oleh aplikasi).
	if os.Getenv("WAYLAND_DISPLAY") != "" && os.Getenv("GDK_BACKEND") != "x11" && os.Getenv("DISPLAY_BACKEND") != "wayland" {
		os.Setenv("GDK_BACKEND", "x11")
	}

	// Paksa WebKitGTK memakai decode video VA-API untuk semua driver (AMD/Intel
	// mesa). Tanpa ini WebKit bisa jatuh ke decoder software (avdec_h264/vp9dec)
	// yang memicu lonjakan CPU & frame drop saat video diputar. Diwarisi oleh
	// proses WebKitWebProcess. Tidak berpengaruh di Windows (WebView2).
	os.Setenv("WEBKIT_GST_VAAPI_ALL_DRIVERS", "1")

	log.Printf("GDK_BACKEND=%s (WAYLAND_DISPLAY=%q DISPLAY=%q)", os.Getenv("GDK_BACKEND"), os.Getenv("WAYLAND_DISPLAY"), os.Getenv("DISPLAY"))

	displayMode := os.Getenv("ANTRIAN_MODE") == "display"

	if displayMode {
		lock, err := adoptInheritedDisplayLock()
		if err != nil {
			log.Fatal("Error:", err.Error())
		}
		if lock == nil {
			lock, err = acquireDisplayLock()
			if err != nil {
				log.Fatal("Error:", err.Error())
			}
		}
		childDisplayLock = lock
	}

	cfg := LoadConfig()

	app := NewApp(cfg, displayMode)

	title := "Sistem Antrian"
	if displayMode {
		title = "Display Caller - Sistem Antrian"
	}

	err := wails.Run(&options.App{
		Title:      title,
		Width:      1280,
		Height:     900,
		Fullscreen: false,
		Frameless:  displayMode,
		AssetServer: &assetserver.Options{
			Assets: assets,
		},
		BackgroundColour: &options.RGBA{R: 27, G: 38, B: 54, A: 1},
		OnStartup:        app.startup,
		Bind: []interface{}{
			app,
		},
	})

	if err != nil {
		log.Fatal("Error:", err.Error())
	}

	if displayMode && childDisplayLock != nil {
		childDisplayLock.release()
	}
}
