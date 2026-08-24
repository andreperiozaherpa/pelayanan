<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <title>YouTube Relay</title>
    <style>
        html, body { margin: 0; height: 100%; background: #000; }
        iframe {
            display: block;
            width: 100%;
            height: 100%;
            border: 0;
            contain: layout paint style;
            will-change: transform;
            transform: translateZ(0);
        }
        .empty { color: #999; font-family: sans-serif; padding: 24px; }
    </style>
</head>
<body>
    @if ($videoId)
        <iframe src="https://www.youtube-nocookie.com/embed/{{ $videoId }}?autoplay=1&mute=1&loop=1&playlist={{ $videoId }}&playsinline=1&rel=0&vq=medium&modestbranding=1&iv_load_policy=3"
            title="YouTube video player"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen></iframe>
    @else
        <p class="empty">Missing ?v=VIDEO_ID</p>
    @endif
</body>
</html>