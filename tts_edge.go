package main

import (
	"bytes"
	"compress/gzip"
	"crypto/sha256"
	"encoding/binary"
	"encoding/hex"
	"encoding/json"
	"fmt"
	"io"
	"math"
	"net/http"
	"os"
	"strings"
	"time"

	"github.com/google/uuid"
	"github.com/gorilla/websocket"
)

const (
	edgeTrustedToken  = "6A5AA1D4EAFF4E9FB37E23D68491D6F4"
	edgeWSSURL        = "wss://api.msedgeservices.com/tts/cognitiveservices/websocket/v1"
	edgeOutputFormat  = "audio-24khz-48kbitrate-mono-mp3"
	edgeSecGECVersion = "1-140.0.3485.14"
	edgeOrigin        = "chrome-extension://jdiccldimpdaibmpdkjnbmckianbfold"
	edgeUserAgent     = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0"
	edgeHandshakeTO   = 15 * time.Second
	edgeReadTimeout   = 25 * time.Second
)

var edgeVoices = map[string]string{
	"gadis": "id-ID-GadisNeural",
	"ardi":  "id-ID-ArdiNeural",
}

// synthEdgeTTS menghasilkan MP3 suara Microsoft Edge (free, tanpa API key)
// menggunakan voice Gadis (wanita formal) / Ardi (pria) lalu menulis ke `out`.
func synthEdgeTTS(out, voice, text string, rate, pitch float64) error {
	short, ok := edgeVoices[voice]
	if !ok {
		return fmt.Errorf("edge voice tidak dikenal: %s", voice)
	}

	conn, err := dialEdge()
	if err != nil {
		return err
	}
	defer conn.Close()

	config := map[string]any{
		"context": map[string]any{
			"synthesis": map[string]any{
				"audio": map[string]any{
					"metadataoptions": map[string]any{
						"sentenceBoundaryEnabled": "false",
						"wordBoundaryEnabled":     "true",
					},
					"outputFormat": edgeOutputFormat,
				},
			},
		},
	}
	cfg, _ := json.Marshal(config)
	ts := time.Now().UTC().Format(http.TimeFormat)
	rid := strings.ReplaceAll(uuid.NewString(), "-", "")
	if err := conn.WriteMessage(websocket.TextMessage,
		[]byte("X-RequestId:"+rid+"\r\nX-Timestamp:"+ts+"\r\nContent-Type:application/json; charset=utf-8\r\nPath:speech.config\r\n\r\n"+string(cfg))); err != nil {
		return err
	}

	ssml := fmt.Sprintf(
		"<speak version='1.0' xml:lang='id-ID'><voice name='%s'><prosody pitch='%s' rate='%s' volume='+0%%'>%s</prosody></voice></speak>",
		short, edgeProsody(pitch), edgeRate(rate), escapeXML(text),
	)
	if err := conn.WriteMessage(websocket.TextMessage,
		[]byte("X-RequestId:"+rid+"\r\nX-Timestamp:"+ts+"\r\nContent-Type:application/ssml+xml\r\nPath:ssml\r\n\r\n"+ssml)); err != nil {
		return err
	}

	audio := bytes.NewBuffer(nil)
	_ = conn.SetReadDeadline(time.Now().Add(edgeReadTimeout))
	for {
		mt, payload, err := conn.ReadMessage()
		if err != nil {
			return err
		}
		switch mt {
		case websocket.TextMessage:
			if strings.Contains(string(payload), "turn.end") {
				return writeEdgeAudio(out, audio.Bytes())
			}
		case websocket.BinaryMessage:
			body, path := parseEdgeFrame(payload)
			if len(body) > 0 {
				audio.Write(body)
			}
			if path == "turn.end" {
				return writeEdgeAudio(out, audio.Bytes())
			}
		}
	}
}

func writeEdgeAudio(out string, data []byte) error {
	if len(data) == 0 {
		return fmt.Errorf("edge tts audio kosong")
	}
	if !isMP3(data) {
		if dec, err := gzipDecode(data); err == nil && isMP3(dec) {
			data = dec
		}
	}
	if !isMP3(data) {
		return fmt.Errorf("edge tts audio bukan mp3")
	}
	return os.WriteFile(out, data, 0o600)
}

func dialEdge() (*websocket.Conn, error) {
	u := edgeWSSURL +
		"?Ocp-Apim-Subscription-Key=" + edgeTrustedToken +
		"&ConnectionId=" + strings.ReplaceAll(uuid.NewString(), "-", "") +
		"&Sec-MS-GEC=" + edgeSecGEC() +
		"&Sec-MS-GEC-Version=" + edgeSecGECVersion

	hdr := http.Header{
		"Pragma":                 {"no-cache"},
		"Cache-Control":          {"no-cache"},
		"Origin":                 {edgeOrigin},
		"Accept-Encoding":        {"gzip, deflate, br"},
		"Accept-Language":        {"en-US,en;q=0.9"},
		"User-Agent":             {edgeUserAgent},
		"Sec-WebSocket-Protocol": {"synthesize"},
		"Sec-WebSocket-Version":  {"13"},
	}

	dialer := websocket.Dialer{
		EnableCompression: true,
		HandshakeTimeout:  edgeHandshakeTO,
	}

	conn, resp, err := dialer.Dial(u, hdr)
	if err != nil {
		if resp != nil {
			return nil, fmt.Errorf("edge dial status %d: %w", resp.StatusCode, err)
		}
		return nil, fmt.Errorf("edge dial: %w", err)
	}
	return conn, nil
}

// edgeSecGEC menghitung token Sec-MS-GEC: SHA-256(ticks FILETIME dibulatkan
// ke 5 menit + trusted client token), dikembalikan sebagai hex uppercase.
func edgeSecGEC() string {
	ft := time.Now().UnixNano()/100 + 116444736000000000
	ft -= ft % 3000000000
	sum := sha256.Sum256([]byte(fmt.Sprintf("%d%s", ft, edgeTrustedToken)))
	return strings.ToUpper(hex.EncodeToString(sum[:]))
}

// parseEdgeFrame membaca bingkai biner Edge TTS: 2 byte panjang header
// (big-endian), blok header ala HTTP (berisi Path:...), lalu body audio.
func parseEdgeFrame(payload []byte) (body []byte, path string) {
	if len(payload) < 2 {
		return nil, ""
	}
	hl := int(binary.BigEndian.Uint16(payload[:2]))
	if hl <= 0 || 2+hl > len(payload) {
		return payload, ""
	}
	for _, line := range strings.Split(string(payload[2:2+hl]), "\r\n") {
		if strings.HasPrefix(line, "Path:") {
			path = strings.TrimSpace(strings.TrimPrefix(line, "Path:"))
			break
		}
	}
	return payload[2+hl:], path
}

// edgeRate mengubah rate (0.5–2) menjadi prosodi "+X%" untuk SSML.
func edgeRate(rate float64) string {
	if rate <= 0 {
		rate = 0.9
	}
	pct := int(math.Round((rate - 1) * 100))
	if pct < -50 {
		pct = -50
	}
	if pct > 300 {
		pct = 300
	}
	return signPercent(pct)
}

// edgeProsody mengubah pitch (0.5–2) menjadi prosodi "+X%" untuk SSML.
func edgeProsody(pitch float64) string {
	if pitch <= 0 {
		pitch = 1
	}
	pct := int(math.Round((pitch - 1) * 100))
	if pct < -50 {
		pct = -50
	}
	if pct > 100 {
		pct = 100
	}
	return signPercent(pct)
}

func signPercent(pct int) string {
	if pct > 0 {
		return fmt.Sprintf("+%d%%", pct)
	}
	return fmt.Sprintf("%d%%", pct)
}

func escapeXML(s string) string {
	r := strings.NewReplacer(
		"&", "&amp;",
		"<", "&lt;",
		">", "&gt;",
		`"`, "&quot;",
		"'", "&apos;",
	)
	return r.Replace(s)
}

func isMP3(data []byte) bool {
	if len(data) < 3 {
		return false
	}
	if data[0] == 0x49 && data[1] == 0x44 && data[2] == 0x33 {
		return true
	}
	if data[0] == 0xFF && (data[1]&0xE0) == 0xE0 {
		return true
	}
	return false
}

func gzipDecode(data []byte) ([]byte, error) {
	r, err := gzip.NewReader(bytes.NewReader(data))
	if err != nil {
		return nil, err
	}
	defer r.Close()
	return io.ReadAll(r)
}
