//go:build linux
// +build linux

package main

/*
#cgo linux pkg-config: gtk+-3.0
#cgo CFLAGS: -w
#include <glib.h>
#include <gdk/gdk.h>

typedef struct MonitorInfo {
	int x;
	int y;
	int width;
	int height;
	int isPrimary;
} MonitorInfo;

extern int getMonitorCount(void);
extern MonitorInfo getMonitorGeometry(int);
extern void triggerOnMainThread(void);
extern int getCursorMonitorIndex(void);
extern const char *getDisplayName(void);
*/
import "C"

import (
	"runtime"
	"sync"
	"unsafe"

	"golang.org/x/sys/unix"
)

type MonitorInfo struct {
	X, Y, Width, Height int
	IsPrimary           bool
}

var (
	mtMutex   sync.Mutex
	mtMainTID int
	mtJob     func()
	mtSignal  chan struct{}
)

//export goRunOnMainThread
func goRunOnMainThread(_ unsafe.Pointer) C.gboolean {
	runtime.LockOSThread()
	defer runtime.UnlockOSThread()

	mtMutex.Lock()
	if mtMainTID == 0 {
		mtMainTID = unix.Gettid()
	}
	job := mtJob
	mtJob = nil
	done := mtSignal
	mtSignal = nil
	mtMutex.Unlock()

	if job != nil {
		job()
	}
	if done != nil {
		close(done)
	}
	return C.G_SOURCE_REMOVE
}

func runOnMainThread(f func()) {
	runtime.LockOSThread()
	defer runtime.UnlockOSThread()

	mtMutex.Lock()
	tid := mtMainTID
	mtMutex.Unlock()

	if tid != 0 && tid == unix.Gettid() {
		f()
		return
	}

	done := make(chan struct{})

	mtMutex.Lock()
	mtJob = f
	mtSignal = done
	mtMutex.Unlock()

	C.triggerOnMainThread()

	<-done
}

func getMonitors() []MonitorInfo {
	monitors := []MonitorInfo{}
	runOnMainThread(func() {
		count := int(C.getMonitorCount())
		if count == 0 {
			return
		}
		for i := 0; i < count; i++ {
			m := C.getMonitorGeometry(C.int(i))
			monitors = append(monitors, MonitorInfo{
				X:         int(m.x),
				Y:         int(m.y),
				Width:     int(m.width),
				Height:    int(m.height),
				IsPrimary: m.isPrimary == 1,
			})
		}
	})
	return monitors
}

func displayBackend() string {
	name := ""
	runOnMainThread(func() {
		cname := C.getDisplayName()
		if cname != nil {
			name = C.GoString(cname)
		}
	})
	return name
}
