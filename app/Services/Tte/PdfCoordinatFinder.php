<?php

namespace App\Services\Tte;

use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Page;
use Smalot\PdfParser\Parser;

class PdfCoordinatFinder
{
    private const A4_HEIGHT_MM = 297;

    private const A4_WIDTH_MM = 210;

    private const PT_TO_MM = 0.3528; // 25.4 / 72

    /**
     * Find a text marker in a PDF and return its position in mm (A4).
     *
     * Uses raw PDF command parsing with correct CTM matrix multiplication
     * to produce accurate page-space coordinates.
     *
     * @param  array<int, string>  $fallbackKeywords
     * @return array{page: int, x: float, y: float, found_via: string, debug_pt: array{x: float, y: float}}|null
     */
    public static function find(string $pdfPath, string $marker = 'TTEMARKERS', array $fallbackKeywords = ['NIP.', 'Kepala Desa']): ?array
    {
        $parser = new Parser;
        $pdf = $parser->parseFile($pdfPath);
        $cleanMarker = trim($marker);
        $found = null;
        $pageIndex = 0;

        foreach ($pdf->getPages() as $pageIndex => $page) {
            $entries = self::getCorrectDataTm($page);

            // Pass 1: Search for marker
            $buffer = '';
            $charPositions = [];

            foreach ($entries as $entry) {
                $text = trim($entry['text']);
                $xPt = $entry['x'];
                $yPt = $entry['y'];

                // Direct match: strip non-alpha chars to handle CID font encoding artifacts
                // Bidirectional check handles truncated markers (e.g., TTEMARKERS → TTEMARKER)
                $cleanText = preg_replace('/[^a-zA-Z0-9]/', '', $text);
                $cleanMarkerAlpha = preg_replace('/[^a-zA-Z0-9]/', '', $cleanMarker);
                $minMatchLen = min(6, strlen($cleanMarkerAlpha));
                $isDirectMatch = $cleanMarkerAlpha !== ''
                    && strlen($cleanText) >= $minMatchLen
                    && (stripos($cleanText, $cleanMarkerAlpha) !== false
                        || stripos($cleanMarkerAlpha, $cleanText) !== false);
                if ($isDirectMatch) {
                    Log::info('✅ MARKER FOUND (direct)', [
                        'marker' => $cleanMarker,
                        'raw_text' => $text,
                        'x_pt' => round($xPt, 2),
                        'y_pt' => round($yPt, 2),
                    ]);

                    $found = ['type' => 'marker', 'x' => $xPt, 'y' => $yPt];

                    break;
                }

                // Buffer-based marker search
                $len = mb_strlen($text, 'UTF-8');
                for ($i = 0; $i < $len; $i++) {
                    $buffer .= mb_substr($text, $i, 1, 'UTF-8');
                    $charPositions[] = ['x' => $xPt, 'y' => $yPt];
                }

                $bufferClean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $buffer));
                $markerClean = strtolower($cleanMarkerAlpha);
                $pos = strpos($bufferClean, $markerClean);

                if ($pos !== false && $pos < count($charPositions)) {
                    $coord = $charPositions[$pos];

                    Log::info('✅ MARKER FOUND (buffer)', [
                        'marker' => $cleanMarker,
                        'x_pt' => round($coord['x'], 2),
                        'y_pt' => round($coord['y'], 2),
                    ]);

                    $found = ['type' => 'marker', 'x' => $coord['x'], 'y' => $coord['y']];

                    break;
                }

                // Trim buffer to prevent memory bloat
                if (count($charPositions) > 500) {
                    $buffer = mb_substr($buffer, -200, null, 'UTF-8');
                    $charPositions = array_slice($charPositions, -200);
                }
            }

            if ($found) {
                break;
            }

            // Pass 2: Fallback keyword search (only if marker not found on this page)
            $buffer = '';
            $charPositions = [];

            foreach ($entries as $entry) {
                $text = trim($entry['text']);
                $len = mb_strlen($text, 'UTF-8');
                for ($i = 0; $i < $len; $i++) {
                    $buffer .= mb_substr($text, $i, 1, 'UTF-8');
                    $charPositions[] = ['x' => $entry['x'], 'y' => $entry['y']];
                }

                $bufferLower = strtolower($buffer);
                foreach ($fallbackKeywords as $kw) {
                    $posKw = strpos($bufferLower, strtolower($kw));
                    if ($posKw !== false && $posKw < count($charPositions)) {
                        $coord = $charPositions[$posKw];

                        Log::info('✅ FALLBACK FOUND', [
                            'keyword' => $kw,
                            'x_pt' => round($coord['x'], 2),
                            'y_pt' => round($coord['y'], 2),
                        ]);

                        $found = ['type' => 'fallback', 'x' => $coord['x'], 'y' => $coord['y']];

                        break 2;
                    }
                }

                if (count($charPositions) > 500) {
                    $buffer = mb_substr($buffer, -200, null, 'UTF-8');
                    $charPositions = array_slice($charPositions, -200);
                }
            }

            if ($found) {
                break;
            }
        }

        if (! $found) {
            Log::warning('❌ Marker tidak ditemukan', ['marker' => $marker]);

            return null;
        }

        $xPt = $found['x'];
        $yPt = $found['y'];

        return [
            'page' => $pageIndex + 1,
            'x' => round($xPt, 2),
            'y' => round($yPt, 2),
            'width' => 0,
            'height' => 0,
            'found_via' => $found['type'],
        ];
    }

    /**
     * Compute correct text positions by processing raw PDF commands
     * with proper CTM matrix multiplication order (M × CTM per PDF spec).
     *
     * smalot/pdfparser's getDataTm() uses wrong multiplication order for cm,
     * causing incorrect translation values while scale factors remain correct.
     *
     * @return array<int, array{text: string, x: float, y: float}>
     */
    private static function getCorrectDataTm(Page $page): array
    {
        $dataCommands = $page->getDataCommands();
        $textArray = $page->getTextArray();

        $ctm = [1, 0, 0, 1, 0, 0];
        $ctmStack = [];
        $tm = [1, 0, 0, 1, 0, 0];
        $tl = 0.0;
        $tx = 0.0;
        $ty = 0.0;

        $results = [];
        $textIdx = 0;

        foreach ($dataCommands as $cmd) {
            if ($textIdx >= count($textArray)) {
                break;
            }

            $op = $cmd['o'];
            $c = $cmd['c'] ?? '';

            switch ($op) {
                case 'q':
                    $ctmStack[] = $ctm;
                    break;

                case 'Q':
                    $ctm = array_pop($ctmStack) ?? [1, 0, 0, 1, 0, 0];
                    break;

                case 'cm':
                    $m = array_map('floatval', explode(' ', $c));
                    // Correct order: CTM' = M × CTM_old (pre-multiply)
                    $ctm = self::multiplyMatrix($m, $ctm);
                    break;

                case 'BT':
                    $tm = [1, 0, 0, 1, 0, 0];
                    $tl = 0.0;
                    $tx = 0.0;
                    $ty = 0.0;
                    break;

                case 'TL':
                    $tl = (float) $c * (float) $tm[3];
                    break;

                case 'Td':
                    $coord = array_map('floatval', explode(' ', $c));
                    $tx += $coord[0] * (float) $tm[0];
                    $ty += $coord[1] * (float) $tm[3];
                    $tm[4] = (string) $tx;
                    $tm[5] = (string) $ty;
                    break;

                case 'TD':
                    $coord = array_map('floatval', explode(' ', $c));
                    $tl = -($coord[1] * (float) $tm[3]);
                    $tx += $coord[0] * (float) $tm[0];
                    $ty += $coord[1] * (float) $tm[3];
                    $tm[4] = (string) $tx;
                    $tm[5] = (string) $ty;
                    break;

                case 'Tm':
                    $tm = array_map('floatval', explode(' ', $c));
                    $tx = (float) $tm[4];
                    $ty = (float) $tm[5];
                    break;

                case 'T*':
                    $ty -= $tl;
                    $tm[5] = (string) $ty;
                    break;

                case 'Tj':
                case 'TJ':
                    // Transform Tm position through CTM to get page-space pt
                    $pagePos = self::transformPoint((float) $tm[4], (float) $tm[5], $ctm);
                    $results[] = [
                        'text' => $textArray[$textIdx] ?? '',
                        'x' => $pagePos[0],
                        'y' => $pagePos[1],
                    ];
                    $textIdx++;
                    break;

                case "'":
                    $ty -= $tl;
                    $tm[5] = (string) $ty;
                    $pagePos = self::transformPoint((float) $tm[4], (float) $tm[5], $ctm);
                    $results[] = [
                        'text' => $textArray[$textIdx] ?? '',
                        'x' => $pagePos[0],
                        'y' => $pagePos[1],
                    ];
                    $textIdx++;
                    break;

                case '"':
                    $ty -= $tl;
                    $tm[5] = (string) $ty;
                    $pagePos = self::transformPoint((float) $tm[4], (float) $tm[5], $ctm);
                    $parts = explode(' ', $textArray[$textIdx] ?? '');
                    $results[] = [
                        'text' => $parts[2] ?? ($textArray[$textIdx] ?? ''),
                        'x' => $pagePos[0],
                        'y' => $pagePos[1],
                    ];
                    $textIdx++;
                    break;
            }
        }

        return $results;
    }

    /**
     * Multiply two 2D transformation matrices [a,b,c,d,e,f].
     *
     * Computes A × B where matrices represent affine transforms:
     * | a b 0 |
     * | c d 0 |
     * | e f 1 |
     *
     * @param  array<int, float>  $a
     * @param  array<int, float>  $b
     * @return array<int, float>
     */
    private static function multiplyMatrix(array $a, array $b): array
    {
        return [
            $a[0] * $b[0] + $a[1] * $b[2],
            $a[0] * $b[1] + $a[1] * $b[3],
            $a[2] * $b[0] + $a[3] * $b[2],
            $a[2] * $b[1] + $a[3] * $b[3],
            $a[4] * $b[0] + $a[5] * $b[2] + $b[4],
            $a[4] * $b[1] + $a[5] * $b[3] + $b[5],
        ];
    }

    /**
     * Transform a point (x, y) through an affine matrix [a,b,c,d,e,f].
     *
     * @param  array<int, float>  $matrix
     * @return array{0: float, 1: float}
     */
    private static function transformPoint(float $x, float $y, array $matrix): array
    {
        return [
            $x * $matrix[0] + $y * $matrix[2] + $matrix[4],
            $x * $matrix[1] + $y * $matrix[3] + $matrix[5],
        ];
    }
}
