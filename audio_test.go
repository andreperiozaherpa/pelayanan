package main

import (
	"path/filepath"
	"sync"
	"testing"
	"time"
)

func TestAudioQueueSerial(t *testing.T) {
	p := NewAudioPlayer()
	p.player = "test"
	p.tmpDir = t.TempDir()

	var mu sync.Mutex
	var calls []string
	p.play = func(path string) error {
		mu.Lock()
		calls = append(calls, path)
		mu.Unlock()
		time.Sleep(5 * time.Millisecond)
		return nil
	}
	p.synth = func(text, voice string, rate, pitch float64) (string, error) {
		return t.TempDir() + "/tts.mp3", nil
	}

	p.enqueue(CallPayload{QueueNumber: "A001", GeraiName: "Gerai 1", Agency: "Kantor", Timestamp: 1})
	p.enqueue(CallPayload{QueueNumber: "B002", GeraiName: "Gerai 2", Agency: "Kantor", Timestamp: 2})
	p.enqueue(CallPayload{QueueNumber: "C003", GeraiName: "Gerai 3", Agency: "Kantor", Timestamp: 3})

	waitUntil(t, func() bool {
		mu.Lock()
		defer mu.Unlock()
		return len(calls) == 6
	})

	mu.Lock()
	defer mu.Unlock()
	want := []string{"chime.wav", "tts.mp3", "chime.wav", "tts.mp3", "chime.wav", "tts.mp3"}
	if len(calls) != len(want) {
		t.Fatalf("calls=%d want %d (%v)", len(calls), len(want), calls)
	}
	for i := range want {
		if filepath.Base(calls[i]) != want[i] {
			t.Fatalf("urutan %d = %v, want %v (total %v)", i, filepath.Base(calls[i]), want[i], calls)
		}
	}
}

func TestAudioQueueDedup(t *testing.T) {
	p := NewAudioPlayer()
	p.player = "test"
	p.tmpDir = t.TempDir()

	var mu sync.Mutex
	count := 0
	p.play = func(path string) error {
		mu.Lock()
		count++
		mu.Unlock()
		return nil
	}

	p.enqueue(CallPayload{QueueNumber: "A001", GeraiName: "Gerai 1", Agency: "K", Timestamp: 1})
	p.enqueue(CallPayload{QueueNumber: "A001", GeraiName: "Gerai 1", Agency: "K", Timestamp: 1})
	p.enqueue(CallPayload{QueueNumber: "A001", GeraiName: "Gerai 1", Agency: "K", Timestamp: 1})

	waitUntil(t, func() bool {
		mu.Lock()
		defer mu.Unlock()
		return count == 2
	})
	mu.Lock()
	defer mu.Unlock()
	if count != 2 {
		t.Fatalf("dedup gagal: count=%d (harus 2: chime + tts untuk 1 panggilan)", count)
	}
}

func TestAudioQueueChimeOnly(t *testing.T) {
	p := NewAudioPlayer()
	p.player = "test"
	p.tmpDir = t.TempDir()

	var mu sync.Mutex
	var calls []string
	p.play = func(path string) error {
		mu.Lock()
		calls = append(calls, path)
		mu.Unlock()
		return nil
	}

	p.enqueue(CallPayload{QueueNumber: "", GeraiName: "", Agency: "", Timestamp: 0})

	waitUntil(t, func() bool {
		mu.Lock()
		defer mu.Unlock()
		return len(calls) == 1
	})
	mu.Lock()
	defer mu.Unlock()
	if len(calls) != 1 || filepath.Base(calls[0]) != "chime.wav" {
		t.Fatalf("chime-only gagal: %v", calls)
	}
}

func TestAnnounceNumber(t *testing.T) {
	cases := []struct{ in, want string }{
		{"A-001", "A, satu"},
		{"A-123", "A, seratus dua puluh tiga"},
		{"GR-007", "G, R, tujuh"},
		{"GR-000", "G, R, nol"},
		{"B-010", "B, sepuluh"},
		{"A-011", "A, sebelas"},
		{"AB-11", "A, B, sebelas"},
		{"A-015", "A, lima belas"},
		{"A-112", "A, seratus dua belas"},
		{"A-025", "A, dua puluh lima"},
		{"A-100", "A, seratus"},
		{"A-210", "A, dua ratus sepuluh"},
		{"A-115", "A, seratus lima belas"},
		{"001", "satu"},
		{"A001", "A, satu"},
		{"A-", "A, nol"},
		{"", ""},
	}
	for _, c := range cases {
		if got := announceNumber(c.in); got != c.want {
			t.Errorf("announceNumber(%q) = %q, want %q", c.in, got, c.want)
		}
	}
}

func waitUntil(t *testing.T, cond func() bool) {
	t.Helper()
	deadline := time.Now().Add(3 * time.Second)
	for time.Now().Before(deadline) {
		if cond() {
			return
		}
		time.Sleep(10 * time.Millisecond)
	}
}
