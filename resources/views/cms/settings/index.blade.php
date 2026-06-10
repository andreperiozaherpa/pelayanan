@extends('layouts.app')

@section('title', 'Pengaturan Website CMS')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'general' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Pengaturan Website</h1>
            <p class="text-xs text-slate-500 font-medium tracking-tight">Konfigurasikan nama situs, logo, kontak, media sosial, dan SEO default website.</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/10 text-emerald-500 rounded-2xl text-xs font-black uppercase tracking-wider">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('cms-settings.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        @csrf
        @method('PUT')

        <!-- Left Column: Tab Links -->
        <div class="lg:col-span-1 space-y-2">
            <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-4 border border-black/[0.03] dark:border-white/[0.03] shadow-sm space-y-1">
                <!-- Tab: General -->
                <button type="button" @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'bg-primary-acorn/10 text-primary-acorn' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-[10px] font-black uppercase tracking-widest transition-all">
                    <iconify-icon icon="lucide:settings" class="text-base"></iconify-icon>
                    Umum / General
                </button>

                <!-- Tab: Contact -->
                <button type="button" @click="activeTab = 'contact'"
                    :class="activeTab === 'contact' ? 'bg-primary-acorn/10 text-primary-acorn' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-[10px] font-black uppercase tracking-widest transition-all">
                    <iconify-icon icon="lucide:phone-call" class="text-base"></iconify-icon>
                    Hubungi Kami
                </button>

                <!-- Tab: Social -->
                <button type="button" @click="activeTab = 'social'"
                    :class="activeTab === 'social' ? 'bg-primary-acorn/10 text-primary-acorn' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-[10px] font-black uppercase tracking-widest transition-all">
                    <iconify-icon icon="lucide:share-2" class="text-base"></iconify-icon>
                    Media Sosial
                </button>

                <!-- Tab: SEO -->
                <button type="button" @click="activeTab = 'seo'"
                    :class="activeTab === 'seo' ? 'bg-primary-acorn/10 text-primary-acorn' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-[10px] font-black uppercase tracking-widest transition-all">
                    <iconify-icon icon="lucide:search" class="text-base"></iconify-icon>
                    SEO Default
                </button>
            </div>

            <!-- Save Button Card -->
            <div class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-4 border border-black/[0.03] dark:border-white/[0.03] shadow-sm">
                <button type="submit" class="w-full bg-primary-acorn hover:bg-primary-acorn/90 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary-acorn/20 transition-all hover:-translate-y-0.5">
                    Simpan Pengaturan
                </button>
            </div>
        </div>

        <!-- Right Column: Settings Inputs -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Tab Pane: General -->
            <div x-show="activeTab === 'general'" x-transition class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 border border-black/[0.03] dark:border-white/[0.03] shadow-sm space-y-6">
                <div>
                    <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Pengaturan Umum</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Konfigurasikan data dasar website utama perusahaan.</p>
                </div>

                <div class="space-y-4">
                    <!-- Site Name -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nama Website (Site Name)</label>
                        <input type="text" name="settings[site_name]" value="{{ old('settings.site_name', $settings->get('general', collect())->firstWhere('key', 'site_name')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Site Tagline -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Slogan / Tagline</label>
                        <input type="text" name="settings[site_tagline]" value="{{ old('settings.site_tagline', $settings->get('general', collect())->firstWhere('key', 'site_tagline')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Site Logo -->
                    @include('cms.partials.dropzone-upload', [
                        'id' => 'site_logo',
                        'name' => 'settings[site_logo]',
                        'label' => 'Logo Website',
                        'value' => old('settings.site_logo', $settings->get('general', collect())->firstWhere('key', 'site_logo')?->value ?? ''),
                        'placeholder' => 'Klik atau seret berkas logo ke sini',
                        'folder' => 'settings'
                    ])

                    <!-- Site Favicon -->
                    @include('cms.partials.dropzone-upload', [
                        'id' => 'site_favicon',
                        'name' => 'settings[site_favicon]',
                        'label' => 'Favicon Website',
                        'value' => old('settings.site_favicon', $settings->get('general', collect())->firstWhere('key', 'site_favicon')?->value ?? ''),
                        'placeholder' => 'Klik atau seret berkas favicon ke sini',
                        'folder' => 'settings'
                    ])
                </div>
            </div>

            <!-- Tab Pane: Contact -->
            <div x-show="activeTab === 'contact'" x-transition class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 border border-black/[0.03] dark:border-white/[0.03] shadow-sm space-y-6">
                <div>
                    <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Hubungi Kami (Kontak)</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Konfigurasikan informasi alamat dan sarana kontak publik.</p>
                </div>

                <div class="space-y-4">
                    <!-- Contact Email -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Alamat Email Resmi</label>
                        <input type="email" name="settings[contact_email]" value="{{ old('settings.contact_email', $settings->get('contact', collect())->firstWhere('key', 'contact_email')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Contact Phone -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nomor Telepon / Hotline</label>
                        <input type="text" name="settings[contact_phone]" value="{{ old('settings.contact_phone', $settings->get('contact', collect())->firstWhere('key', 'contact_phone')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Contact Address -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Alamat Kantor / Instansi</label>
                        <textarea name="settings[contact_address]" rows="3"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white">{{ old('settings.contact_address', $settings->get('contact', collect())->firstWhere('key', 'contact_address')?->value ?? '') }}</textarea>
                    </div>

                    <!-- Map Iframe -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">HTML Google Maps Iframe (Embed)</label>
                        <textarea name="settings[contact_map_iframe]" rows="3"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-mono transition-all dark:text-white"
                            placeholder='<iframe src="https://www.google.com/maps/embed?..." ...></iframe>'>{{ old('settings.contact_map_iframe', $settings->get('contact', collect())->firstWhere('key', 'contact_map_iframe')?->value ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tab Pane: Social -->
            <div x-show="activeTab === 'social'" x-transition class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 border border-black/[0.03] dark:border-white/[0.03] shadow-sm space-y-6">
                <div>
                    <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Tautan Media Sosial</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Hubungkan website dengan akun media sosial resmi instansi.</p>
                </div>

                <div class="space-y-4">
                    <!-- Facebook -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Facebook URL</label>
                        <input type="url" name="settings[social_facebook]" value="{{ old('settings.social_facebook', $settings->get('social', collect())->firstWhere('key', 'social_facebook')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Instagram -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Instagram URL</label>
                        <input type="url" name="settings[social_instagram]" value="{{ old('settings.social_instagram', $settings->get('social', collect())->firstWhere('key', 'social_instagram')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Twitter -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Twitter / X URL</label>
                        <input type="url" name="settings[social_twitter]" value="{{ old('settings.social_twitter', $settings->get('social', collect())->firstWhere('key', 'social_twitter')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Youtube -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">YouTube Channel URL</label>
                        <input type="url" name="settings[social_youtube]" value="{{ old('settings.social_youtube', $settings->get('social', collect())->firstWhere('key', 'social_youtube')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>
                </div>
            </div>

            <!-- Tab Pane: SEO -->
            <div x-show="activeTab === 'seo'" x-transition class="premium-card bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 border border-black/[0.03] dark:border-white/[0.03] shadow-sm space-y-6">
                <div>
                    <h2 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">SEO Default (Search Engine Optimization)</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Informasi default yang dikirimkan ke mesin pencari jika halaman tidak memiliki SEO spesifik.</p>
                </div>

                <div class="space-y-4">
                    <!-- Meta Title Default -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Meta Title Default</label>
                        <input type="text" name="settings[meta_title_default]" value="{{ old('settings.meta_title_default', $settings->get('seo', collect())->firstWhere('key', 'meta_title_default')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white" />
                    </div>

                    <!-- Meta Description Default -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Meta Description Default</label>
                        <textarea name="settings[meta_description_default]" rows="4"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white">{{ old('settings.meta_description_default', $settings->get('seo', collect())->firstWhere('key', 'meta_description_default')?->value ?? '') }}</textarea>
                    </div>

                    <!-- Meta Keywords Default -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Meta Keywords Default</label>
                        <input type="text" name="settings[meta_keywords_default]" value="{{ old('settings.meta_keywords_default', $settings->get('seo', collect())->firstWhere('key', 'meta_keywords_default')?->value ?? '') }}"
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-black/[0.03] dark:border-white/[0.03] rounded-2xl focus:ring-2 focus:ring-primary-acorn/20 focus:border-primary-acorn text-[11px] font-bold transition-all dark:text-white"
                            placeholder="keyword1, keyword2, keyword3..." />
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
