@extends('layouts.app')

@section('title', 'Template Tiket Antrian')

@section('content')
<div class="space-y-6" x-data="ticketTemplateEditor(@js($template->draft_layout))">
    <div>
        <h1 class="text-xl font-black text-slate-800 dark:text-white">Template Tiket Antrian</h1>
        <p class="text-xs text-slate-500">Area cetak 55 mm pada kertas 58 mm. Preview ini adalah perkiraan sebelum profil printer kiosk diterapkan.</p>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="rounded-lg bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('mpp-ticket-template.update') }}" class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(300px,55mm)]">
        @csrf
        <input type="hidden" name="layout" x-model="serializedLayout">

        <section class="space-y-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div><h2 class="font-bold text-slate-800 dark:text-white">Blok tiket</h2><p class="text-xs text-slate-500">Urutkan blok sesuai hasil cetak. Nomor antrian wajib tersedia.</p></div>
                <button type="button" @click="applyExample" class="rounded-lg border border-primary-acorn px-3 py-2 text-xs font-bold text-primary-acorn">Terapkan contoh tiket</button>
                <select x-model="newBlockType" class="rounded-lg border-slate-300 text-sm dark:bg-slate-900"><template x-for="option in blockOptions" :key="option.type"><option :value="option.type" x-text="option.label"></option></template></select>
                <button type="button" @click="addBlock" class="rounded-lg bg-slate-700 px-3 py-2 text-xs font-bold text-white">Tambah blok</button>
            </div>

            <template x-for="(block, index) in blocks" :key="block.id">
                <article class="rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <span class="w-24 text-sm font-bold text-slate-700 dark:text-slate-200" x-text="blockLabel(block.type)"></span>
                        <template x-if="block.type !== 'divider'"><select x-model="block.align" class="rounded border-slate-300 text-xs dark:bg-slate-900"><option value="left">Kiri</option><option value="center">Tengah</option><option value="right">Kanan</option></select></template>
                        <button type="button" @click="move(index, -1)" :disabled="index === 0" class="ml-auto rounded border px-2 py-1 text-xs disabled:opacity-30">↑</button>
                        <button type="button" @click="move(index, 1)" :disabled="index === blocks.length - 1" class="rounded border px-2 py-1 text-xs disabled:opacity-30">↓</button>
                        <button type="button" @click="remove(index)" :disabled="block.type === 'queue_number'" class="rounded border border-red-200 px-2 py-1 text-xs text-red-600 disabled:opacity-30">Hapus</button>
                    </div>
                    <template x-if="needsText(block.type)"><input x-model="block.text" maxlength="200" class="mt-3 w-full rounded border-slate-300 text-sm dark:bg-slate-900" placeholder="Teks yang akan dicetak"></template>
                </article>
            </template>

            <div class="flex flex-wrap gap-3 pt-2">
                <button class="rounded-lg bg-slate-700 px-4 py-2 text-xs font-bold text-white">Simpan Draft</button>
                <button formaction="{{ route('mpp-ticket-template.publish') }}" formmethod="POST" class="rounded-lg bg-primary-acorn px-4 py-2 text-xs font-bold text-white">Publish Versi Baru</button>
            </div>
        </section>

        <aside class="space-y-3">
            <h2 class="font-bold text-slate-800 dark:text-white">Preview contoh</h2>
            <div class="w-[55mm] rounded border border-dashed border-slate-400 bg-white p-4 text-xs text-slate-800 shadow-sm">
                <template x-for="block in blocks" :key="block.id"><div class="mb-2 break-words" :class="{'text-left': block.align === 'left', 'text-center': !block.align || block.align === 'center', 'text-right': block.align === 'right', 'border-t border-dashed border-slate-400': block.type === 'divider', 'my-3': block.type === 'divider', 'text-4xl font-black': block.type === 'queue_number', 'font-bold': block.type === 'title'}" x-text="previewText(block)"></div></template>
            </div>
            <p class="text-xs text-slate-500">Versi aktif: <strong>{{ $template->activeVersion?->version ? 'v'.$template->activeVersion->version : 'belum dipublikasikan' }}</strong></p>
        </aside>
    </form>

    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="font-bold text-slate-800 dark:text-white">Riwayat versi</h2>
        <p class="mb-3 text-xs text-slate-500">Versi yang sudah diterbitkan tidak diubah. Pemulihan menjadikannya template aktif dan menyalin isinya ke draft.</p>
        <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="border-b text-xs text-slate-500"><tr><th class="py-2">Versi</th><th class="py-2">Penerbit</th><th class="py-2">Waktu</th><th class="py-2">Checksum</th><th class="py-2"></th></tr></thead><tbody>
            @forelse($template->versions as $version)
                <tr class="border-b border-slate-100 dark:border-slate-700"><td class="py-3 font-bold">v{{ $version->version }} @if($template->active_version_id === $version->id)<span class="ml-1 rounded bg-emerald-100 px-1.5 py-0.5 text-xs text-emerald-700">aktif</span>@endif</td><td class="py-3">{{ $version->publisher?->name ?? 'Sistem' }}</td><td class="py-3">{{ $version->published_at?->format('d M Y H:i') }}</td><td class="py-3 font-mono text-xs">{{ str($version->checksum)->limit(12, '') }}</td><td class="py-3 text-right">@if($template->active_version_id !== $version->id)<form method="POST" action="{{ route('mpp-ticket-template.rollback', $version) }}">@csrf<button class="rounded border border-slate-300 px-2 py-1 text-xs">Pulihkan</button></form>@endif</td></tr>
            @empty
                <tr><td colspan="5" class="py-4 text-slate-500">Belum ada versi yang dipublikasikan.</td></tr>
            @endforelse
        </tbody></table></div>
    </section>
</div>
@endsection

@push('scripts')
<script>
function ticketTemplateEditor(layout) {
    const options = [{ type: 'logo', label: 'Logo MPP' }, { type: 'title', label: 'Judul' }, { type: 'subtitle', label: 'Subjudul' }, { type: 'queue_number', label: 'Nomor antrian' }, { type: 'priority', label: 'Prioritas' }, { type: 'service', label: 'Layanan' }, { type: 'agency', label: 'Instansi' }, { type: 'gerai', label: 'Nama gerai' }, { type: 'remaining_queue', label: 'Sisa antrean' }, { type: 'datetime', label: 'Tanggal & jam' }, { type: 'barcode', label: 'Barcode tiket' }, { type: 'divider', label: 'Pemisah' }, { type: 'footer', label: 'Footer' }];
    const example = [{ type: 'logo', align: 'center' }, { type: 'divider' }, { type: 'title', text: 'NOMOR ANTRIAN', align: 'center' }, { type: 'queue_number', align: 'center' }, { type: 'gerai', align: 'center' }, { type: 'remaining_queue', align: 'center' }, { type: 'datetime', align: 'center' }, { type: 'barcode', align: 'center' }, { type: 'divider' }, { type: 'footer', text: 'Mohon menunggu petugas kami akan memanggil nomor antrian Anda. Terima kasih.', align: 'center' }, { type: 'divider' }, { type: 'footer', text: 'Catatan:', align: 'left' }];
    let blockId = 0;
    const withId = (block) => ({ ...block, id: `block-${++blockId}` });
    return {
        blockOptions: options, newBlockType: 'title', blocks: (layout.blocks || []).map(withId),
        get serializedLayout() { return JSON.stringify({ schema: 1, paper_width_mm: 58, print_width_mm: 55, blocks: this.blocks.map(({ id, ...block }) => block) }); },
        addBlock() { this.blocks.push(withId({ type: this.newBlockType, align: 'center', ...(this.needsText(this.newBlockType) ? { text: '' } : {}) })); },
        applyExample() { this.blocks = example.map(withId); },
        remove(index) { this.blocks.splice(index, 1); },
        move(index, direction) { const target = index + direction; if (target >= 0 && target < this.blocks.length) [this.blocks[index], this.blocks[target]] = [this.blocks[target], this.blocks[index]]; },
        needsText(type) { return ['title', 'subtitle', 'footer'].includes(type); },
        blockLabel(type) { return this.blockOptions.find((option) => option.type === type)?.label || type; },
        previewText(block) { const sample = { logo: 'LOGO MPP', queue_number: 'A-001', priority: 'PRIORITAS', service: 'Layanan KTP Elektronik', agency: 'MPP Tulang Bawang Barat', gerai: 'Kantor Pelayanan Publik', remaining_queue: 'SISA ANTRIAN 0', datetime: '29-09-2026 14:40:06', barcode: '||| || ||| || |||', divider: '' }; return this.needsText(block.type) ? (block.text || 'Teks belum diisi') : sample[block.type]; },
    };
}
</script>
@endpush
