<?php

test('relay youtube mengembalikan halaman embed dengan video id', function () {
    $this->get('/yt-relay?v=ev8G3y0tHQE')
        ->assertOk()
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertSee('youtube-nocookie.com/embed/ev8G3y0tHQE', false);
});

test('relay youtube membersihkan video id yang tidak dikenal', function () {
    $this->get('/yt-relay?v=<script>alert(1)</script>')
        ->assertOk()
        ->assertDontSee('<script>', false)
        ->assertSee('Missing ?v=VIDEO_ID');
});

test('relay youtube tanpa video id menampilkan pesan kosong', function () {
    $this->get('/yt-relay')
        ->assertOk()
        ->assertSee('Missing ?v=VIDEO_ID');
});
