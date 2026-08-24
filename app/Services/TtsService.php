<?php

namespace App\Services;

/**
 * Sintesis suara bahasa Indonesia secara gratis tanpa API key.
 *
 * Logika ini adalah cerminan dari Go backend (antrian/audio.go + tts_edge.go)
 * agar preview "Uji Suara" di admin dan suara yang diputar di aplikasi Display
 * benar-benar identik:
 *   - voice "gadis" / "ardi"  → Microsoft Edge TTS (id-ID Gadis/Ardi Neural)
 *   - voice "google"          → Google Translate TTS (wanita)
 *   - bila Edge gagal, otomatis jatuh ke Google Translate.
 */
class TtsService
{
    private const EDGE_VOICES = [
        'gadis' => 'id-ID-GadisNeural',
        'ardi' => 'id-ID-ArdiNeural',
    ];

    private const EDGE_TRUSTED_TOKEN = '6A5AA1D4EAFF4E9FB37E23D68491D6F4';

    private const EDGE_WSS_HOST = 'api.msedgeservices.com';

    private const EDGE_WSS_PATH = '/tts/cognitiveservices/websocket/v1';

    private const EDGE_OUTPUT_FORMAT = 'audio-24khz-48kbitrate-mono-mp3';

    private const EDGE_SEC_GEC_VERSION = '1-140.0.3485.14';

    private const EDGE_ORIGIN = 'chrome-extension://jdiccldimpdaibmpdkjnbmckianbfold';

    private const EDGE_USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0';

    private string $buffer = '';

    /**
     * Hasilkan audio pengumuman sebagai byte MP3, atau null bila gagal.
     *
     * @param  string  $voice  google|gadis|ardi
     */
    public function synthesize(string $text, string $voice, float $rate = 0.9, float $pitch = 1): ?string
    {
        $text = trim($text);
        if ($text === '') {
            return null;
        }

        if (isset(self::EDGE_VOICES[$voice])) {
            $audio = $this->synthesizeEdge($text, $voice, $rate, $pitch);
            if ($audio !== null) {
                return $audio;
            }
        }

        return $this->synthesizeGoogle($text, $rate);
    }

    private function synthesizeEdge(string $text, string $voice, float $rate, float $pitch): ?string
    {
        $socket = $this->dialEdge();
        if ($socket === false) {
            return null;
        }

        try {
            $requestId = $this->newRequestId();
            $this->sendFrame($socket, $this->speechConfigFrame($requestId));

            $short = self::EDGE_VOICES[$voice];
            $ssml = sprintf(
                "<speak version='1.0' xml:lang='id-ID'><voice name='%s'><prosody pitch='%s' rate='%s' volume='+0%%'>%s</prosody></voice></speak>",
                $short,
                $this->edgeProsody($pitch),
                $this->edgeRate($rate),
                $this->escapeXml($text),
            );
            $this->sendFrame($socket, $this->textFrame($requestId, 'application/ssml+xml', 'ssml', $ssml));

            $audio = '';
            while (true) {
                $frame = $this->readFrame($socket);
                if ($frame === null) {
                    break;
                }

                if ($frame['opcode'] === 0x01) {
                    if (str_contains($frame['payload'], 'turn.end')) {
                        break;
                    }
                } elseif ($frame['opcode'] === 0x02 || $frame['opcode'] === 0x00) {
                    [$body, $path] = $this->parseEdgeFrame($frame['payload']);
                    if ($body !== '') {
                        $audio .= $body;
                    }
                    if ($path === 'turn.end') {
                        break;
                    }
                }
            }

            if ($audio === '') {
                return null;
            }
            if (! $this->isMp3($audio)) {
                $decoded = @gzdecode($audio);
                if ($decoded !== false && $this->isMp3($decoded)) {
                    $audio = $decoded;
                }
            }

            return $this->isMp3($audio) ? $audio : null;
        } finally {
            @fclose($socket);
            $this->buffer = '';
        }
    }

    private function synthesizeGoogle(string $text, float $rate): ?string
    {
        $url = 'https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&tl=id&total=1&idx=0&textlen='
            .mb_strlen($text)
            .'&ttspeed='.number_format($this->clampRate($rate), 2)
            .'&q='.rawurlencode($text);

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 20,
                'ignore_errors' => true,
                'header' => "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36\r\nReferer: https://www.google.com/\r\n",
            ],
        ]);

        $data = @file_get_contents($url, false, $context);
        if ($data === false || $data === '') {
            return null;
        }

        return $this->isMp3($data) ? $data : null;
    }

    private function dialEdge(): mixed
    {
        $query = '?Ocp-Apim-Subscription-Key='.self::EDGE_TRUSTED_TOKEN
            .'&ConnectionId='.$this->newRequestId()
            .'&Sec-MS-GEC='.$this->edgeSecGec()
            .'&Sec-MS-GEC-Version='.self::EDGE_SEC_GEC_VERSION;

        $key = base64_encode(random_bytes(16));
        $head = 'GET '.self::EDGE_WSS_PATH.$query." HTTP/1.1\r\n"
            .'Host: '.self::EDGE_WSS_HOST."\r\n"
            ."Upgrade: websocket\r\n"
            ."Connection: Upgrade\r\n"
            .'Sec-WebSocket-Key: '.$key."\r\n"
            ."Sec-WebSocket-Version: 13\r\n"
            ."Sec-WebSocket-Protocol: synthesize\r\n"
            ."Pragma: no-cache\r\n"
            ."Cache-Control: no-cache\r\n"
            .'Origin: '.self::EDGE_ORIGIN."\r\n"
            ."Accept-Encoding: gzip, deflate, br\r\n"
            ."Accept-Language: en-US,en;q=0.9\r\n"
            .'User-Agent: '.self::EDGE_USER_AGENT."\r\n\r\n";

        $errno = 0;
        $errstr = '';
        $socket = @stream_socket_client('tls://'.self::EDGE_WSS_HOST.':443', $errno, $errstr, 15);
        if ($socket === false) {
            return false;
        }

        stream_set_timeout($socket, 25);
        fwrite($socket, $head);

        $response = '';
        while (strpos($response, "\r\n\r\n") === false) {
            $chunk = @fread($socket, 8192);
            if ($chunk === false || $chunk === '') {
                return false;
            }
            $response .= $chunk;
        }

        if (! preg_match('#^HTTP/1\.1\s+101#', $response)) {
            return false;
        }

        // Sisa byte setelah header handshake adalah awal frame WebSocket.
        $pos = strpos($response, "\r\n\r\n") + 4;
        $this->buffer = substr($response, $pos);

        return $socket;
    }

    private function sendFrame($socket, string $payload): void
    {
        $len = strlen($payload);
        if ($len <= 125) {
            $head = chr(0x81).chr(0x80 | $len);
        } elseif ($len <= 65535) {
            $head = chr(0x81).chr(0x80 | 126).pack('n', $len);
        } else {
            $head = chr(0x81).chr(0x80 | 127).pack('J', $len);
        }

        $mask = random_bytes(4);
        $masked = $payload ^ str_repeat($mask, intdiv(strlen($payload) + 3, 4));

        fwrite($socket, $head.$mask.$masked);
    }

    private function readFrame($socket): ?array
    {
        $b1 = $this->readExact($socket, 1);
        $b2 = $this->readExact($socket, 1);
        if ($b1 === null || $b2 === null) {
            return null;
        }

        $b1 = ord($b1);
        $b2 = ord($b2);
        $opcode = $b1 & 0x0F;
        $len = $b2 & 0x7F;

        if ($len === 126) {
            $d = $this->readExact($socket, 2);
            if ($d === null) {
                return null;
            }
            $len = unpack('n', $d)[1];
        } elseif ($len === 127) {
            $d = $this->readExact($socket, 8);
            if ($d === null) {
                return null;
            }
            $len = unpack('J', $d)[1];
        }

        $payload = $this->readExact($socket, $len);
        if ($payload === null) {
            return null;
        }

        if (($b2 & 0x80) !== 0) {
            $mask = $this->readExact($socket, 4);
            if ($mask === null) {
                return null;
            }
            $payload = $payload ^ str_repeat($mask, intdiv(strlen($payload) + 3, 4));
        }

        return ['opcode' => $opcode, 'payload' => $payload];
    }

    private function readExact($socket, int $len): ?string
    {
        while (strlen($this->buffer) < $len) {
            $chunk = @fread($socket, 65536);
            if ($chunk === false || $chunk === '') {
                $meta = stream_get_meta_data($socket);
                if (! empty($meta['timed_out'])) {
                    return null;
                }

                return null;
            }
            $this->buffer .= $chunk;
        }

        $out = substr($this->buffer, 0, $len);
        $this->buffer = substr($this->buffer, $len);

        return $out;
    }

    private function speechConfigFrame(string $requestId): string
    {
        $config = json_encode([
            'context' => [
                'synthesis' => [
                    'audio' => [
                        'metadataoptions' => [
                            'sentenceBoundaryEnabled' => 'false',
                            'wordBoundaryEnabled' => 'true',
                        ],
                        'outputFormat' => self::EDGE_OUTPUT_FORMAT,
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES);

        return $this->textFrame($requestId, 'application/json; charset=utf-8', 'speech.config', $config);
    }

    private function textFrame(string $requestId, string $contentType, string $path, string $body): string
    {
        $ts = gmdate('D, d M Y H:i:s \G\M\T');

        return "X-RequestId:{$requestId}\r\nX-Timestamp:{$ts}\r\nContent-Type:{$contentType}\r\nPath:{$path}\r\n\r\n{$body}";
    }

    private function parseEdgeFrame(string $payload): array
    {
        if (strlen($payload) < 2) {
            return [$payload, ''];
        }

        $headerLength = unpack('n', substr($payload, 0, 2))[1];
        if ($headerLength <= 0 || 2 + $headerLength > strlen($payload)) {
            return [$payload, ''];
        }

        $path = '';
        foreach (explode("\r\n", substr($payload, 2, $headerLength)) as $line) {
            if (str_starts_with($line, 'Path:')) {
                $path = trim(substr($line, 5));
                break;
            }
        }

        return [substr($payload, 2 + $headerLength), $path];
    }

    private function edgeSecGec(): string
    {
        $ft = (int) floor(microtime(true) * 1e7) + 116444736000000000;
        $ft -= $ft % 3000000000;

        return strtoupper(hash('sha256', $ft.self::EDGE_TRUSTED_TOKEN));
    }

    /**
     * Nama voice Microsoft Neural untuk pilihan suara admin, atau null bila
     * pilihan memakai Google Translate (suara "google") yang tidak mendukung
     * kecepatan maupun nada.
     */
    public function voiceName(string $voice): ?string
    {
        return self::EDGE_VOICES[$voice] ?? null;
    }

    public function edgeRate(float $rate): string
    {
        if ($rate <= 0) {
            $rate = 0.9;
        }

        $pct = (int) round(($rate - 1) * 100);

        return $this->signPercent(max(-50, min(300, $pct)));
    }

    public function edgeProsody(float $pitch): string
    {
        if ($pitch <= 0) {
            $pitch = 1;
        }

        $pct = (int) round(($pitch - 1) * 100);

        return $this->signPercent(max(-50, min(100, $pct)));
    }

    public function clampRate(float $rate): float
    {
        if ($rate <= 0) {
            return 0.9;
        }

        return max(0.24, min(4, $rate));
    }

    public function isMp3(string $data): bool
    {
        if (strlen($data) < 3) {
            return false;
        }

        return substr($data, 0, 3) === 'ID3'
            || (ord($data[0]) === 0xFF && (ord($data[1]) & 0xE0) === 0xE0);
    }

    private function signPercent(int $pct): string
    {
        return ($pct > 0 ? '+' : '').$pct.'%';
    }

    private function escapeXml(string $text): string
    {
        return str_replace(['&', '<', '>', '"', "'"], ['&amp;', '&lt;', '&gt;', '&quot;', '&apos;'], $text);
    }

    private function newRequestId(): string
    {
        return strtolower(bin2hex(random_bytes(16)));
    }
}
