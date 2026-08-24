package main

import (
	"os"
	"strings"
	"testing"
)

func TestResolveChimeName(t *testing.T) {
	cases := map[string]string{
		"ding-dong-2tone": "ding-dong-2tone",
		"airport-3tone":   "airport-3tone",
		"tubular-bell":    "tubular-bell",
		"announcement":    "announcement",
		"none":            "none",
		"":                chimeSoundDefault,
		"  ":              chimeSoundDefault,
		"bogus":           chimeSoundDefault,
	}
	for in, want := range cases {
		if got := resolveChimeName(in); got != want {
			t.Errorf("resolveChimeName(%q) = %q, want %q", in, got, want)
		}
	}
}

func TestWriteChimeSound(t *testing.T) {
	p := NewAudioPlayer()
	dir := t.TempDir()

	for _, name := range []string{"ding-dong-2tone", "airport-3tone", "tubular-bell", "announcement"} {
		path := dir + "/" + name + ".wav"
		ok, err := p.writeChimeSound(name, path)
		if err != nil || !ok {
			t.Fatalf("writeChimeSound(%q): ok=%v err=%v", name, ok, err)
		}
		data, err := os.ReadFile(path)
		if err != nil {
			t.Fatal(err)
		}
		if !strings.HasPrefix(string(data), "RIFF") {
			t.Errorf("%s bukan WAV", name)
		}
	}

	// "none" tidak menulis file.
	ok, err := p.writeChimeSound("none", dir+"/none.wav")
	if err != nil || ok {
		t.Fatalf("writeChimeSound(none): ok=%v err=%v (harus ok=false)", ok, err)
	}
	if _, err := os.Stat(dir + "/none.wav"); !os.IsNotExist(err) {
		t.Error("none seharusnya tidak menulis file")
	}
}

func TestChimeDataURL(t *testing.T) {
	p := NewAudioPlayer()

	for _, name := range []string{"ding-dong-2tone", "airport-3tone", "tubular-bell", "announcement"} {
		url := p.chimeDataURL(name)
		if !strings.HasPrefix(url, "data:audio/wav;base64,") {
			t.Errorf("chimeDataURL(%q) tidak berawalan data URL: %q", name, url[:min(len(url), 20)])
		}
	}

	if got := p.chimeDataURL("none"); got != "" {
		t.Errorf("chimeDataURL(none) = %q, ingin kosong", got)
	}
	if got := p.chimeDataURL(""); !strings.HasPrefix(got, "data:audio/wav;base64,") {
		t.Errorf("chimeDataURL('') tidak fallback ke default")
	}
}
