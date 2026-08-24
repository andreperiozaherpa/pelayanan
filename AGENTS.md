# Sistem Antrian — AI Agent Context

## Project Overview
Aplikasi desktop antrian pelayanan publik (Wails v2 + Go + React).
3 titik layanan: Anjungan (kiosk), FO (Front Office), Gerai (counter).

**Arsitektur: Wails App = Pure Desktop Client.**
Single Source of Truth ada di External Central API terpisah.
Aplikasi Wails ini TIDAK mengelola database atau HTTP server internal.

## Tech Stack
- **Backend (Native):** Go 1.25, Wails v2.13.0
- **Frontend:** React 19, Vite 7, JavaScript (JSX, no TypeScript)
- **API Communication:** HTTP REST + WebSocket ke External Central API
- **Build tag:** `webkit2_41` (Linux webview)

## Color Palette (CSS Variables in `style.css`)
```
--primary:        #149fb5   (teal)
--secondary:      #2b515d   (dark teal / bg-card)
--accent:         #f8ab3a   (orange)
--accent-light:   #f9c476   (light orange)
--light:          #eafcff   (ice cyan)
--dark:           #1D2128   (charcoal, main bg)

--bg-primary   = var(--primary)
--bg-secondary = var(--secondary)  alias --bg-card
--bg-accent    = var(--accent)
--bg-light     = var(--light)
--bg-dark      = var(--dark)

--text-primary   #ffffff
--text-secondary #c8d6e5
--text-muted     #8899a6

--border-primary rgba(20,159,181,0.2)  alias --border
--border-accent  rgba(248,171,58,0.3)

--primary-hover  #17b8d1
--radius         16px
--radius-sm      10px
```

### Glassmorphism Design Tokens (in `App.css`)
```
Page bg:         linear-gradient(145deg, #FFF5E6, #FFE8CC, #FFDAB3)
Glass card:      rgba(255,255,255,0.18) + backdrop-filter: blur(24px)
Glass border:    rgba(255,255,255,0.3)
Text primary:    #2d2d2d
Text muted:      rgba(0,0,0,0.45)
Icons:           stroke line-art (strokeWidth: 1.8, fill: none, currentColor)
Buttons:         .btn-glass with variants: primary(teal), accent(orange), danger(red),
                 warning(amber), secondary(glass outline)
Inputs:          .glass-input-wrap (flex row with icon) + .glass-input (transparent)
```

## Konfigurasi (file `.env`)
```
API_BASE_URL=http://api-pusat.domain.com/api/v1
WS_URL=ws://api-pusat.domain.com/ws
```
Nilai default (dev): `http://localhost:8080/api/v1` dan `ws://localhost:8080/ws`.
Dibaca oleh Go backend pada startup dan diekspos ke frontend via `GetConfig()`.

## Architecture
```
┌──────────────────────────────────┐      HTTP/WS (JWT)      ┌──────────────────────┐
│  Wails Desktop Client (Go+React) │ ──────────────────────→ │  External Central API │
│  ┌────────────────────────────┐  │  ←────────────────────── │  (Single Source of   │
│  │  Go Backend (Native Only): │  │                          │   Truth)             │
│  │  • Multi-window/monitor    │  │                          └──────────────────────┘
│  │  • Local config (.env)    │  │
│  │  • Hardware integration    │  │
│  │  • Bindings (tabel di bawah)  │  │
│  │    OpenDisplay, SetStaffWindow │  │
│  └────────────────────────────┘  │
│  ┌────────────────────────────┐  │
│  │  React Frontend:           │  │
│  │  • UI/UX (Glassmorphism)   │  │
│  │  • API Client → External   │  │
│  │  • WebSocket → External    │  │
│  │  • JWT Auth via External   │  │
│  │  • TTS Browser Native      │  │
│  └────────────────────────────┘  │
└──────────────────────────────────┘
```

## Project Structure
```
antrian/
├── main.go                     # Entry: load config → paksa GDK_BACKEND=x11 (Wayland)
├── app.go                      # Wails bindings: GetMode, GetConfig, OpenDisplay, SetFullscreen, SetStaffWindow, SetHomeWindow
├── config.go                   # .env loader + AppConfig struct
├── screen_unix.go              # Multi-monitor GTK3 + deteksi kursor (CGO, Linux)
├── screen_windows.go           # Multi-monitor Win32 user32 (Windows)
├── wails.json                  # build:tags = webkit2_41
├── .env.example                # Template config file
├── AGENTS.md                   # This file
└── frontend/
    └── src/
        ├── main.jsx            # React entry
        ├── App.jsx             # Root: load config, mode routing
        ├── App.css             # All component styles
        ├── style.css           # Global styles + CSS variables
        ├── components/
        │   ├── CallCenterIllustration.jsx
        │   └── ui/
        │       ├── index.js
        │       ├── BackButton.jsx
        │       ├── GlassBadge.jsx
        │       ├── GlassButton.jsx
        │       ├── GlassCard.jsx
        │       ├── GlassIconWrap.jsx
        │       ├── GlassInput.jsx
        │       ├── GlassModal.jsx
        │       └── GlassQueueItem.jsx
        ├── contexts/
        │   └── AppContext.jsx  # Global state: mode, petugas, config
        ├── pages/
        │   ├── Home.jsx        # Mode selection (4 glass cards)
        │   ├── Login.jsx       # Login via External API
        │   ├── Anjungan.jsx    # Pick service → get ticket
        │   ├── FO.jsx          # Call, verify, forward/reject
        │   ├── Gerai.jsx       # Call, complete service
        │   └── CallerDisplay.jsx # Fullscreen TV display + TTS
        ├── hooks/
        │   ├── useWebSocket.js # WS client → External Central API
        │   └── useTTSQueue.js  # Browser SpeechSynthesis queue
        └── services/
            └── api.js          # HTTP client → External Central API
```

## Key Go→JS Bindings (in `app.go`)
| Method | Returns | Used By |
|---|---|---|
| `GetMode()` | `string` | App.jsx (display auto-detect) |
| `GetConfig()` | `AppConfig` | App.jsx, api.js |
| `OpenDisplay()` | `(int, error)` | Home.jsx (spawn display window) |
| `SetFullscreen()` | `void` | App.jsx (display mode) |
| `SetStaffWindow()` | `void` | FO.jsx, Gerai.jsx (window ke pojok kanan-bawah) |
| `SetHomeWindow()` | `void` | App.jsx (kembali tengah 1280×900) |

## Window Positioning
- Posisi window mengikuti **monitor tempat kursor berada** (`targetMonitor()`: kursor → primary → monitors[0]).
- FO/Gerai: pojok kanan-bawah, ukuran ¼×¾ layar (`SetStaffWindow`).
- Home: tengah monitor, 1280×900 (`SetHomeWindow`).
- Urutan **`SetSize` → `SetPosition`** wajib — bila posisi di-set sebelum ukuran, window lama yang besar meluber keluar monitor sehingga Mutter meng-clamp posisinya.
- **Wayland**: `gtk_window_move` diabaikan compositor → `main.go` memaksa `GDK_BACKEND=x11` (XWayland) saat `WAYLAND_DISPLAY` ter-set.
- **Windows**: tanpa masalah (Wails pakai `SetWindowPos`); `screen_windows.go` memakai user32 (`EnumDisplayMonitors`, `GetMonitorInfoW`, `GetCursorPos`, `MonitorFromPoint`).

## Frontend API Client (`api.js`)
Semua CRUD + Auth request dikirim langsung ke External Central API via HTTP fetch dengan JWT Bearer token.
Base URL dan WS URL diambil dari `GetConfig()` (Go config) saat startup.

## WebSocket Channels (dari External API)
| Channel | Broadcasts |
|---|---|
| `all` | Every connected client |
| `fo` | FO updates (new ticket, call, reject) |
| `gerai` | Gerai updates |
| `display` | Display:call events |

## Display Window Behavior
- Klik "Display Caller" → `app.go:OpenDisplay()` spawns binary with `ANTRIAN_MODE=display` env
- New Wails window: `Fullscreen: true`, title "Display Caller"
- Frontend calls `GetMode()` on mount → auto-enters display mode
- TTS menggunakan browser native `window.speechSynthesis` (purely local)

## Common Commands
```bash
wails dev           # Development mode (hot-reload frontend)
wails build         # Production build
wails generate module  # Regenerate JS bindings after Go changes
go build -tags webkit2_41 ./...  # Just compile Go (Linux)
GOOS=windows go build -tags "webkit2_41 production" ./...  # Cross-compile check (Windows)
npm run build       # Build frontend only
```

## Seed Accounts (password: `lerd123`)
| Mode | Username | Role |
|---|---|---|
| FO | `fo1`, `fo2` | Front Office |
| Gerai | `gerai1`, `gerai2`, `gerai3` | Gerai 1, 2, 3 |

Data akun dan konfigurasi dikelola oleh External Central API.
Daftar di atas adalah referensi seed data lokal lama (untuk development).

## Coding Conventions
- **Go:** No comments in production code unless necessary. Backend hanya menangani native OS features.
- **React:** Functional components + hooks. No TypeScript. CSS in `App.css` (single file). Palette via CSS variables in `style.css`.
- **UI Components:** Use primitives from `components/ui/`.
- **Glassmorphism UI:** Warm cream/orange gradient bg. Glass cards with backdrop-filter blur.
- **Icons:** Pure line-art style — `fill="none"`, `stroke="currentColor"`, `strokeWidth="1.8"`.
- **API Client:** All requests via `api.js` using JWT Bearer auth. API URLs from `GetConfig()`.
- **WebSocket:** Connects to external API's WS server. Auto-reconnect every 2s.
- **TTS:** Browser native `SpeechSynthesis`, purely local, no external ack.
