package main

import (
	"embed"
	"encoding/base64"
	"os"
	"strings"
)

//go:embed sounds/chimes
var chimeFS embed.FS

// chimeSoundDefault adalah nada bel yang dipakai bila tidak ada pilihan.
const chimeSoundDefault = "airport-3tone"

// chimeNames mendaftar nada bel yang tersedia (selain "none").
var chimeNames = map[string]bool{
	"ding-dong-2tone": true,
	"airport-3tone":   true,
	"tubular-bell":    true,
	"announcement":    true,
}

// resolveChimeName menormalkan nama nada bel; "none" tetap "none", nama tak
// dikenal / kosong diarahkan ke default.
func resolveChimeName(name string) string {
	name = strings.TrimSpace(name)
	if name == "none" {
		return "none"
	}
	if !chimeNames[name] {
		return chimeSoundDefault
	}
	return name
}

// writeChimeSound menulis file WAV nada bel terpilih ke `path`. Mengembalikan
// false bila pilihan "none" (tanpa bel). Nama kosong/asing memakai default.
func (p *AudioPlayer) writeChimeSound(name, path string) (bool, error) {
	name = resolveChimeName(name)
	if name == "none" {
		return false, nil
	}
	data, err := chimeFS.ReadFile("sounds/chimes/" + name + ".wav")
	if err != nil {
		return false, err
	}
	return true, os.WriteFile(path, data, 0o600)
}

// chimeDataURL mengembalikan nada bel sebagai data URL WAV untuk diputar
// engine web (fallback saat player OS tidak tersedia). "" bila "none".
func (p *AudioPlayer) chimeDataURL(name string) string {
	name = resolveChimeName(name)
	if name == "none" {
		return ""
	}
	data, err := chimeFS.ReadFile("sounds/chimes/" + name + ".wav")
	if err != nil {
		return ""
	}
	return "data:audio/wav;base64," + base64.StdEncoding.EncodeToString(data)
}
