@extends('layouts.app')

@section('title', 'Pengaturan Display Caller')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .animate-marquee {
        animation: dc-marquee 12s linear infinite;
        display: inline-block;
        padding-left: 100%;
    }
    @keyframes dc-marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }
</style>
@endpush

@section('content')
@php
    $input = 'w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white';
    $btnPrimary = 'inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary-acorn hover:bg-primary-acorn/90 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5';
    $btnSecondary = 'inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 dark:bg-slate-100 dark:hover:bg-white text-white dark:text-slate-900 text-[10px] font-black uppercase tracking-widest transition';
    $tabBase = 'inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition';
    $tabActive = 'bg-primary-acorn text-white shadow-md';
    $tabInactive = 'bg-slate-100 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800';
    $colorLabels = [
        'color_bg' => 'Latar Belakang',
        'color_bg_card' => 'Latar Kartu',
        'color_border' => 'Garis / Border',
        'color_text' => 'Teks Utama',
        'color_text_muted' => 'Teks Redup',
        'color_accent' => 'Aksen / Marquee',
        'color_number' => 'Nomor Jumbo',
    ];
@endphp
<div class="space-y-6" x-data="displaySettings(@js($settings))">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Pengaturan Display Caller</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Kontrol layar TV Display Caller MPP secara realtime via Firebase Realtime Database.</p>
        </div>
        <a href="#" x-on:click.prevent="window.open('/', '_blank')"
            class="{{ $btnSecondary }}">
            <iconify-icon icon="lucide:external-link" class="text-sm"></iconify-icon>
            Buka Halaman Publik
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/10 text-emerald-500 rounded-2xl text-xs font-black uppercase tracking-wider">
            {{ session('success') }}
        </div>
    @endif

    <!-- Firebase Warning -->
    @if(!$firebase_ready)
        <div class="p-4 bg-amber-500/10 border border-amber-500/20 text-amber-600 rounded-2xl text-xs font-black uppercase tracking-wider flex items-start gap-3">
            <iconify-icon icon="lucide:alert-triangle" class="text-base mt-0.5"></iconify-icon>
            <span>Firebase belum dikonfigurasi. Isi <code class="font-mono">FIREBASE_CREDENTIALS</code> dan <code class="font-mono">FIREBASE_DATABASE_URL</code> di <code class="font-mono">.env</code> agar perubahan tampil realtime di layar TV. Perubahan tetap disimpan ke database.</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- ================= LIVE PREVIEW (kiri) ================= -->
        <div class="lg:col-span-2 space-y-4">
            <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-4 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
                <h2 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">
                    <iconify-icon icon="lucide:eye" class="inline mr-1"></iconify-icon> Pratinjau Langsung
                </h2>

                {{-- Mini display mock --}}
                <div class="rounded-xl overflow-hidden border" :style="{ background: colors.bg, borderColor: colors.border }">
                    {{-- Header --}}
                    <div class="px-3 py-2 flex items-center justify-between gap-2" :style="{ background: colors.bg_card, borderBottom: '1px solid ' + colors.border }">
                        <div class="min-w-0">
                            <p class="font-black uppercase truncate" :style="{ color: colors.text, fontSize: '9px' }" x-text="header_title || 'SIBERUGO MPP TUBABA'"></p>
                            <p class="truncate" :style="{ color: colors.text_muted, fontSize: '6px' }" x-text="header_subtitle"></p>
                        </div>
                        <p class="shrink-0 font-mono" :style="{ color: colors.accent, fontSize: '7px' }" x-text="clock"></p>
                    </div>

                    {{-- Body: hero (nomor antrian + loket aktif) --}}
                    <div class="grid grid-cols-5 gap-1.5 p-2">
                        <div class="col-span-3 rounded p-1.5 flex flex-col items-center justify-center text-center space-y-0.5" :style="{ background: colors.bg_card, border: '1px solid ' + colors.border }">
                            <p class="uppercase font-black" :style="{ color: colors.text_muted, fontSize: '5px' }">Nomor Antrian</p>
                            <p class="font-black" :style="{ color: colors.number, fontSize: '18px' }">D-1</p>
                            <p class="uppercase font-black truncate" :style="{ color: colors.accent, fontSize: '6px' }">Gerai 11</p>
                        </div>
                        <div class="col-span-2 grid grid-cols-2 gap-1">
                            <div class="rounded flex flex-col items-center justify-center gap-0.5" :style="{ background: colors.bg_card, border: '1px solid ' + colors.border }">
                                <span class="uppercase truncate w-full text-center" :style="{ color: colors.text_muted, fontSize: '4px' }">Loket 1</span>
                                <span class="font-black" :style="{ color: colors.accent, fontSize: '8px' }">D-1</span>
                            </div>
                            <div class="rounded flex flex-col items-center justify-center gap-0.5" :style="{ background: colors.bg_card, border: '1px solid ' + colors.border }">
                                <span class="uppercase truncate w-full text-center" :style="{ color: colors.text_muted, fontSize: '4px' }">Loket 2</span>
                                <span class="font-black" :style="{ color: colors.accent, fontSize: '8px' }">D-2</span>
                            </div>
                        </div>
                    </div>

                    {{-- Marquee --}}
                    <div class="px-3 py-1.5 overflow-hidden" :style="{ background: colors.accent }">
                        <p class="whitespace-nowrap animate-marquee text-white font-bold truncate" :style="{ fontSize: '7px' }" x-text="running_text || 'Running text pengumuman...'"></p>
                    </div>
                </div>

                <p class="text-[9px] text-slate-400 font-bold mt-3 leading-relaxed">Setiap perubahan akan langsung tampil di layar TV display yang sedang berjalan (real-time).</p>
            </div>

            <!-- ============ TEST CALL ============ -->
            <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-4 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
                <h2 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">
                    <iconify-icon icon="lucide:phone-call" class="inline mr-1"></iconify-icon> Simulasi Test Panggilan
                </h2>
                <form action="{{ route('display-settings.simulate') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Nomor Antrian</label>
                            <input type="text" name="queue_number" required placeholder="D-1" class="{{ $input }}" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Nama Gerai</label>
                            <input type="text" name="gerai_name" required placeholder="Gerai 11" class="{{ $input }}" />
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Instansi / Agency</label>
                        <input type="text" name="agency" placeholder="Kantor Pajak Pratama Kotabumi" class="{{ $input }}" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Jenis Layanan</label>
                        <input type="text" name="service_type" placeholder="PENDAFTARAN NPWP" class="{{ $input }}" />
                    </div>
                    <button type="submit" class="{{ $btnPrimary }} w-full">
                        <iconify-icon icon="lucide:volume-2" class="text-sm"></iconify-icon> Kirim Test Panggilan
                    </button>
                </form>
            </div>
        </div>

        <!-- ============ FORM PENGATURAN (kanan) ============ -->
        <div class="lg:col-span-3">
            <form action="{{ route('display-settings.update') }}" method="POST" class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
                @csrf

                <!-- Navigasi Tab -->
                <div class="flex flex-wrap gap-2 border-b border-black/[0.05] dark:border-white/[0.05] pb-4">
                    <button type="button" x-on:click="tab='teks'" :class="tab==='teks' ? '{{ $tabActive }}' : '{{ $tabInactive }}'" class="{{ $tabBase }}">
                        <iconify-icon icon="lucide:type" class="text-sm"></iconify-icon> Teks
                    </button>
                    <button type="button" x-on:click="tab='media'" :class="tab==='media' ? '{{ $tabActive }}' : '{{ $tabInactive }}'" class="{{ $tabBase }}">
                        <iconify-icon icon="lucide:youtube" class="text-sm"></iconify-icon> Media / Video
                    </button>
                    <button type="button" x-on:click="tab='suara'" :class="tab==='suara' ? '{{ $tabActive }}' : '{{ $tabInactive }}'" class="{{ $tabBase }}">
                        <iconify-icon icon="lucide:volume-2" class="text-sm"></iconify-icon> Suara &amp; Bel
                    </button>
                    <button type="button" x-on:click="tab='warna'" :class="tab==='warna' ? '{{ $tabActive }}' : '{{ $tabInactive }}'" class="{{ $tabBase }}">
                        <iconify-icon icon="lucide:palette" class="text-sm"></iconify-icon> Warna Visual
                    </button>
                </div>

                <!-- ============ TAB: TEKS ============ -->
                <div x-show="tab==='teks'" x-cloak class="pt-5 space-y-5">
                    <div>
                        <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Teks Header &amp; Running Text</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Judul, subtitle, dan teks berjalan di layar display.</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Judul Utama (Header Title)</label>
                        <input type="text" name="header_title" x-model="header_title" value="{{ old('header_title', $settings['header_title']) }}" class="{{ $input }}" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Subtitle</label>
                        <input type="text" name="header_subtitle" x-model="header_subtitle" value="{{ old('header_subtitle', $settings['header_subtitle']) }}" class="{{ $input }}" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Teks Berjalan (Running Text)</label>
                        <textarea name="running_text" rows="3" x-model="running_text" class="{{ $input }} resize-none">{{ old('running_text', $settings['running_text']) }}</textarea>
                        <p class="text-[9px] text-slate-400 font-bold ml-1">Teks ini berjalan (marquee) di baris paling bawah layar display.</p>
                    </div>
                </div>

                <!-- ============ TAB: MEDIA ============ -->
                <div x-show="tab==='media'" x-cloak class="pt-5 space-y-4">
                    <div>
                        <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Video Media</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Tautan video / playlist YouTube yang diputar di layar display.</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Link Video YouTube</label>
                        <input type="url" name="youtube_url" x-model="youtube_url" value="{{ old('youtube_url', $settings['youtube_url']) }}" placeholder="https://www.youtube.com/watch?v=..." class="{{ $input }}" />
                    </div>
                    <div class="rounded-xl overflow-hidden border border-black/[0.04] dark:border-white/[0.04] aspect-video bg-slate-900 flex items-center justify-center" x-show="youtubeId()">
                        <iframe :src="'https://www.youtube.com/embed/' + youtubeId() + '?rel=0&modestbranding=1'" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>

                <!-- ============ TAB: SUARA & BEL ============ -->
                <div x-show="tab==='suara'" x-cloak class="pt-5 space-y-5">
                    <div>
                        <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Suara Pemanggilan (TTS)</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Pengaturan suara otomatis saat nomor dipanggil.</p>
                    </div>
                    <label class="flex items-center justify-between px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] cursor-pointer">
                        <span class="text-[11px] font-black text-slate-600 dark:text-slate-300">Aktifkan Suara Pemanggilan</span>
                        <input type="checkbox" name="tts_enabled" value="1" x-model="tts_enabled"
                            class="w-5 h-5 rounded accent-amber-500" :checked="tts_enabled" />
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Suara (Voice)</label>
                            <select name="tts_voice" x-model="tts_voice" class="{{ $input }}">
                                <option value="google">Google — Wanita (standar, gratis)</option>
                                <option value="gadis">Gadis — Wanita Formal (Edge TTS)</option>
                                <option value="ardi">Ardi — Pria (Edge TTS)</option>
                            </select>
                            <p class="text-[9px] text-slate-400 font-bold ml-1">Google tidak mendukung kecepatan/nada sehingga slider dinonaktifkan; Gadis/Ardi (Edge TTS) mendukung keduanya.</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Kecepatan (Rate): <span class="text-primary-acorn" x-text="tts_rate"></span></label>
                            <input type="range" min="0.5" max="2" step="0.1" x-model="tts_rate"
                                :disabled="tts_voice === 'google'"
                                class="w-full accent-amber-500 disabled:opacity-40 disabled:cursor-not-allowed" />
                            <input type="hidden" name="tts_rate" :value="tts_rate" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nada (Pitch): <span class="text-primary-acorn" x-text="tts_pitch"></span></label>
                        <input type="range" min="0.5" max="2" step="0.1" x-model="tts_pitch"
                            :disabled="tts_voice === 'google'"
                            class="w-full accent-amber-500 disabled:opacity-40 disabled:cursor-not-allowed" />
                        <input type="hidden" name="tts_pitch" :value="tts_pitch" />
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" x-on:click="testSpeech()" class="{{ $btnSecondary }}">
                            <iconify-icon icon="lucide:volume-2" class="text-sm"></iconify-icon> Uji Suara
                        </button>
                        <span class="text-[9px] text-slate-400 font-bold" x-show="!tts_enabled">Suara sedang dimatikan.</span>
                    </div>

                    <hr class="border-black/[0.05] dark:border-white/[0.05]">

                    <div>
                        <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Bel Antrian (Chime)</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Bunyi bel yang diputar sebelum pengumuman nomor antrian.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nada Bel (Chime Sound)</label>
                            <select name="chime_sound" x-model="chime_sound" class="{{ $input }}">
                                <option value="ding-dong-2tone">Ding-Dong 2 Tone — Classic</option>
                                <option value="airport-3tone">Airport 3 Tone — Soft &amp; Elegant</option>
                                <option value="tubular-bell">Tubular Bell — Modern</option>
                                <option value="announcement">Announcement — Efek Suara</option>
                                <option value="none">Tanpa Bel — Langsung Suara</option>
                            </select>
                            <p class="text-[9px] text-slate-400 font-bold ml-1" x-show="chime_sound === 'none'">Bel dimatikan — pengumuman langsung tanpa bunyi bel.</p>
                        </div>
                        <div class="flex sm:justify-end">
                            <button type="button" x-on:click="previewChime()" class="{{ $btnSecondary }}">
                                <iconify-icon icon="lucide:music-2" class="text-sm"></iconify-icon> Coba Bel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ============ TAB: WARNA ============ -->
                <div x-show="tab==='warna'" x-cloak class="pt-5 space-y-4">
                    <div>
                        <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Pengaturan Warna</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Sesuaikan seluruh pewarnaan layar display.</p>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($colorLabels as $key => $label)
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">{{ $label }}</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="{{ $key }}" x-model="colors.{{ str_replace('color_', '', $key) }}" value="{{ old($key, $settings[$key]) }}"
                                        class="w-10 h-10 rounded-xl border border-black/[0.04] dark:border-white/[0.04] bg-transparent cursor-pointer p-1" />
                                    <input type="text" name="{{ $key }}_hex" :value="colors.{{ str_replace('color_', '', $key) }}" readonly
                                        class="flex-1 px-3 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-xl text-[10px] font-mono font-bold dark:text-white" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-col sm:flex-row items-center gap-3 justify-between border-t border-black/[0.05] dark:border-white/[0.05] pt-4 mt-5">
                    <p class="text-[9px] text-slate-400 font-bold">Perubahan tersimpan &amp; tampil realtime di layar TV.</p>
                    <button type="submit" class="{{ $btnPrimary }}">
                        <iconify-icon icon="lucide:save" class="text-sm"></iconify-icon> Simpan &amp; Publikasikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function displaySettings(defaults) {
        return {
            tab: 'teks',
            header_title: defaults.header_title || '',
            header_subtitle: defaults.header_subtitle || '',
            running_text: defaults.running_text || '',
            youtube_url: defaults.youtube_url || '',
            tts_enabled: defaults.tts_enabled === true || defaults.tts_enabled === '1' || defaults.tts_enabled === 1,
            tts_rate: defaults.tts_rate || 0.9,
            tts_pitch: defaults.tts_pitch || 1,
            tts_voice: defaults.tts_voice || 'google',
            chime_sound: defaults.chime_sound || 'airport-3tone',
            colors: {
                bg: defaults.color_bg || '#0f172a',
                bg_card: defaults.color_bg_card || '#1e293b',
                border: defaults.color_border || '#334155',
                text: defaults.color_text || '#f8fafc',
                text_muted: defaults.color_text_muted || '#94a3b8',
                accent: defaults.color_accent || '#f8ab3a',
                number: defaults.color_number || '#f8ab3a',
            },
            clock: new Date().toLocaleTimeString('id-ID'),
            init() {
                const tick = () => this.clock = new Date().toLocaleTimeString('id-ID')
                this._clockTimer = setInterval(tick, 1000)
            },
            destroy() {
                clearInterval(this._clockTimer)
            },
            youtubeId() {
                const url = this.youtube_url || ''
                const m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/)
                return m ? m[1] : null
            },
            async testSpeech() {
                const sample = `Nomor antrian D-1, silahkan menuju Gerai 11, Kantor Pajak Pratama Kotabumi.`
                try {
                    const resp = await fetch('{{ route('display-settings.preview') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            text: sample,
                            voice: this.tts_voice,
                            rate: parseFloat(this.tts_rate) || 0.9,
                            pitch: parseFloat(this.tts_pitch) || 1,
                        }),
                    })
                    const data = await resp.json()
                    if (resp.ok && data.audio) {
                        new Audio(data.audio).play()
                        return
                    }
                } catch (e) {
                    /* lanjut fallback ke suara browser */
                }
                if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel()
                    const u = new SpeechSynthesisUtterance(sample)
                    u.lang = 'id-ID'
                    u.rate = parseFloat(this.tts_rate)
                    u.pitch = parseFloat(this.tts_pitch)
                    window.speechSynthesis.speak(u)
                }
            },
            previewChime() {
                if (this.chime_sound === 'none') return
                const audio = new Audio(`/sounds/chimes/${this.chime_sound}.wav`)
                audio.play().catch(() => {})
            },
        }
    }
</script>
@endpush
