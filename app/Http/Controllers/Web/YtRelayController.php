<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Halaman "YouTube Embed Relay".
 *
 * Wails (Linux) memuat display dari skema `wails://` sehingga iframe YouTube
 * dikirim dengan Referer non-HTTP dan ditolak YouTube (Error 153). Halaman ini
 * di-host di domain HTTP(S) publik sehingga iframe YouTube di dalamnya membawa
 * Referer yang valid dan video dapat diputar.
 */
class YtRelayController extends Controller
{
    public function index(Request $request): Response
    {
        $videoId = (string) $request->query('v', '');
        $videoId = preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId) ? $videoId : '';

        return response()->view('yt-relay', ['videoId' => $videoId])
            ->header('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
