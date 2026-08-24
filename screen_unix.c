//go:build linux

#include <glib.h>
#include <gdk/gdk.h>

typedef struct MonitorInfo {
	int x;
	int y;
	int width;
	int height;
	int isPrimary;
} MonitorInfo;

extern gboolean goRunOnMainThread(void *);

int getMonitorCount(void) {
	GdkDisplay *display = gdk_display_get_default();
	if (display == NULL) return 0;
	return gdk_display_get_n_monitors(display);
}

MonitorInfo getMonitorGeometry(int index) {
	MonitorInfo info = {0, 0, 0, 0, 0};
	GdkDisplay *display = gdk_display_get_default();
	if (display == NULL) return info;
	GdkMonitor *monitor = gdk_display_get_monitor(display, index);
	if (monitor == NULL) return info;
	GdkRectangle geometry;
	gdk_monitor_get_geometry(monitor, &geometry);
	info.x = geometry.x;
	info.y = geometry.y;
	info.width = geometry.width;
	info.height = geometry.height;
	info.isPrimary = gdk_monitor_is_primary(monitor) ? 1 : 0;
	return info;
}

void triggerOnMainThread(void) {
	g_idle_add((GSourceFunc)goRunOnMainThread, NULL);
}

const char *getDisplayName(void) {
	GdkDisplay *display = gdk_display_get_default();
	if (display == NULL) return "none";
	return gdk_display_get_name(display);
}

int getCursorMonitorIndex(void) {
	GdkDisplay *display = gdk_display_get_default();
	if (display == NULL) return -1;
	GdkSeat *seat = gdk_display_get_default_seat(display);
	if (seat == NULL) return -1;
	GdkDevice *pointer = gdk_seat_get_pointer(seat);
	if (pointer == NULL) return -1;
	int x, y;
	gdk_device_get_position(pointer, NULL, &x, &y);
	GdkMonitor *m = gdk_display_get_monitor_at_point(display, x, y);
	int n = gdk_display_get_n_monitors(display);
	for (int i = 0; i < n; i++) {
		if (gdk_display_get_monitor(display, i) == m) return i;
	}
	return -1;
}
