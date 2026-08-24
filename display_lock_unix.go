//go:build linux
// +build linux

package main

import (
	"fmt"
	"os"

	"golang.org/x/sys/unix"
)

type displayLock struct {
	f *os.File
}

// acquireDisplayLock mengunci display secara eksklusif (non-blokir).
func acquireDisplayLock() (*displayLock, error) {
	f, err := os.OpenFile(displayLockPath(), os.O_CREATE|os.O_RDWR, 0o600)
	if err != nil {
		return nil, err
	}
	if err := unix.Flock(int(f.Fd()), unix.LOCK_EX|unix.LOCK_NB); err != nil {
		_ = f.Close()
		return nil, fmt.Errorf("layar display sudah terbuka")
	}
	return &displayLock{f: f}, nil
}

// adoptInheritedDisplayLock mengambil alih fd kunci yang diwariskan dari
// proses utama (OpenDisplay). Mengembalikan nil bila tidak ada warisan.
func adoptInheritedDisplayLock() (*displayLock, error) {
	raw := os.Getenv(displayLockFDEnv)
	if raw == "" {
		return nil, nil
	}
	var fd int
	if _, err := fmt.Sscanf(raw, "%d", &fd); err != nil || fd < 3 {
		return nil, fmt.Errorf("fd kunci display tidak valid: %q", raw)
	}
	return &displayLock{f: os.NewFile(uintptr(fd), displayLockName)}, nil
}

// childExtra mengembalikan fd kunci untuk diwariskan ke proses display.
func (l *displayLock) childExtra() *os.File {
	if l == nil {
		return nil
	}
	return l.f
}

// heldPath mengembalikan path kunci — tidak dipakai di Unix.
func (l *displayLock) heldPath() string {
	return ""
}

// handoff menyerahkan kunci ke proses child tanpa melepas flock.
func (l *displayLock) handoff() {
	if l == nil || l.f == nil {
		return
	}
	_ = l.f.Close()
}

// release melepas kunci sepenuhnya.
func (l *displayLock) release() {
	if l == nil || l.f == nil {
		return
	}
	_ = unix.Flock(int(l.f.Fd()), unix.LOCK_UN)
	_ = l.f.Close()
}
