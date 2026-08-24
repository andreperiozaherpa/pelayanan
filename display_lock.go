package main

import (
	"os"
	"path/filepath"
)

const (
	displayLockName    = "antrian-display.lock"
	displayLockFDEnv   = "ANTRIAN_DISPLAY_LOCK_FD"
	displayLockPathEnv = "ANTRIAN_DISPLAY_LOCK_PATH"
)

// childDisplayLock menahan kunci display seumur hidup proses display agar GC
// tidak menutup fd kunci lebih awal (Unix) / path tetap tercatat (Windows).
var childDisplayLock *displayLock

func displayLockPath() string {
	return filepath.Join(os.TempDir(), displayLockName)
}
