//go:build windows

package main

import (
	"sync"
	"syscall"
	"unsafe"
)

type MonitorInfo struct {
	X, Y, Width, Height int
	IsPrimary           bool
}

var (
	user32             = syscall.NewLazyDLL("user32.dll")
	procEnumMonitors   = user32.NewProc("EnumDisplayMonitors")
	procGetMonitorInfo = user32.NewProc("GetMonitorInfoW")
	procMonitorFromPt  = user32.NewProc("MonitorFromPoint")
	procGetCursorPos   = user32.NewProc("GetCursorPos")
)

const (
	monitorInfoFPrimary     = 1
	monitorDefaultToNearest = 2
)

type winRect struct {
	Left, Top, Right, Bottom int32
}

type winPoint struct {
	X, Y int32
}

type monitorInfo struct {
	Size    uint32
	Monitor winRect
	Work    winRect
	Flags   uint32
}

var (
	enumMu      sync.Mutex
	enumHandles []uintptr
)

func enumProc(hMonitor, _, _, _ uintptr) uintptr {
	enumHandles = append(enumHandles, hMonitor)
	return 1
}

func enumMonitors() ([]MonitorInfo, []uintptr) {
	enumMu.Lock()
	defer enumMu.Unlock()

	monitors := []MonitorInfo{}
	handles := []uintptr{}

	enumHandles = enumHandles[:0]
	cb := syscall.NewCallback(enumProc)
	r, _, _ := procEnumMonitors.Call(0, 0, cb, 0)
	if r == 0 {
		return monitors, handles
	}

	handles = append(handles, enumHandles...)
	for _, h := range enumHandles {
		mi := &monitorInfo{Size: uint32(unsafe.Sizeof(monitorInfo{}))}
		r, _, _ := procGetMonitorInfo.Call(h, uintptr(unsafe.Pointer(mi)))
		if r == 0 {
			continue
		}
		monitors = append(monitors, MonitorInfo{
			X:         int(mi.Monitor.Left),
			Y:         int(mi.Monitor.Top),
			Width:     int(mi.Monitor.Right - mi.Monitor.Left),
			Height:    int(mi.Monitor.Bottom - mi.Monitor.Top),
			IsPrimary: mi.Flags&monitorInfoFPrimary != 0,
		})
	}
	return monitors, handles
}

func getMonitors() []MonitorInfo {
	monitors, _ := enumMonitors()
	return monitors
}

func getCursorMonitor() *MonitorInfo {
	monitors, handles := enumMonitors()
	if len(monitors) == 0 {
		return nil
	}

	var pt winPoint
	r, _, _ := procGetCursorPos.Call(uintptr(unsafe.Pointer(&pt)))
	if r == 0 {
		return &monitors[0]
	}

	h, _, _ := procMonitorFromPt.Call(uintptr(unsafe.Pointer(&pt)), monitorDefaultToNearest)
	for i, hh := range handles {
		if hh == h && i < len(monitors) {
			return &monitors[i]
		}
	}
	return &monitors[0]
}

func displayBackend() string {
	return "windows"
}
