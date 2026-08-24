<?php

use App\Services\TtsService;

beforeEach(function () {
    $this->tts = new TtsService;
});

test('voiceName memetakan pilihan suara ke voice Neural', function () {
    expect($this->tts->voiceName('gadis'))->toBe('id-ID-GadisNeural');
    expect($this->tts->voiceName('ardi'))->toBe('id-ID-ArdiNeural');
    expect($this->tts->voiceName('google'))->toBeNull();
    expect($this->tts->voiceName('siri'))->toBeNull();
});

test('edgeRate mengubah kecepatan menjadi prosodi SSML', function (float $rate, string $expected) {
    expect($this->tts->edgeRate($rate))->toBe($expected);
})->with([
    [0.9, '-10%'],
    [1.0, '0%'],
    [1.5, '+50%'],
    [0.5, '-50%'],
    [2.0, '+100%'],
    [0.0, '-10%'],
]);

test('edgeProsody mengubah nada menjadi prosodi SSML', function (float $pitch, string $expected) {
    expect($this->tts->edgeProsody($pitch))->toBe($expected);
})->with([
    [1.0, '0%'],
    [1.5, '+50%'],
    [0.5, '-50%'],
    [2.0, '+100%'],
    [0.0, '0%'],
]);

test('clampRate membatasi kecepatan dalam rentang yang didukung Google', function (float $rate, float $expected) {
    expect($this->tts->clampRate($rate))->toBe($expected);
})->with([
    [0.0, 0.9],
    [0.1, 0.24],
    [5.0, 4.0],
    [1.2, 1.2],
]);

test('isMp3 mengenali MP3 (ID3 atau frame sync)', function () {
    expect($this->tts->isMp3('ID3abc'))->toBeTrue();
    expect($this->tts->isMp3("\xFF\xFB\x90\x00"))->toBeTrue();
    expect($this->tts->isMp3('abc'))->toBeFalse();
});
