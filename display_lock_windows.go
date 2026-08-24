//go:build windows
// +build windows

package main

import (
	"fmt"
	"os"
)

type displayLock struct {
	path string
}

// acquireDisplayLock membuat file kunci eksklusif (non-blokir).
func acquireDisplayLock() (*displayLock, error) {
	path := displayLockPath()
	f, err := os.OpenFile(path, os.O_CREATE|os.O_EXCL|os.O_WRONLY, 0o600)
	if err != nil {
		return nil, fmt.Errorf("layar display sudah terbuka")
	}
	_ = f.Close()
	return &displayLock{path: path}, nil
}

// adoptInheritedDisplayLock mengambil alih path kunci dari proses utama.
func adoptInheritedDisplayLock() (*displayLock, error) {
	path := os.Getenv(displayLockPathEnv)
	if path == "" {
		return nil, nil
	}
	return &displayLock{path: path}, nil
}

// childExtra tidak dipakai di Windows.
func (l *displayLock) childExtra() *os.File {
	return nil
}

// heldPath mengembalikan path kunci untuk diteruskan ke proses display.
func (l *displayLock) heldPath() string {
	if l == nil {
		return ""
	}
	return l.path
}

// handoff menyerahkan kunci ke proses display: file tetap ada dan proses
// display yang menghapusnya saat keluar.
func (l *displayLock) handoff() {
}

// release melepas kunci sepenuhnya.
func (l *displayLock) release() {
	if l == nil || l.path == "" {
		return
	}
	_ = os.Remove(l.path)
}
