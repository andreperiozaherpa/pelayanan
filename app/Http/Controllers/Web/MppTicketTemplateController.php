<?php

namespace App\Http\Controllers\Web;

use App\Facades\Audit;
use App\Http\Controllers\Controller;
use App\Models\MppTicketTemplate;
use App\Models\MppTicketTemplateVersion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MppTicketTemplateController extends Controller
{
    public function edit(): View
    {
        $template = MppTicketTemplate::query()->firstOrCreate([], ['draft_layout' => $this->defaultLayout()]);
        $template->load(['activeVersion', 'versions' => fn ($query) => $query->with('publisher')->latest('version')->limit(10)]);

        return view('services.admin.mpp.template-tiket.edit', compact('template'));
    }

    public function update(Request $request): RedirectResponse
    {
        $layout = $this->validatedLayout($request);
        $template = MppTicketTemplate::query()->firstOrCreate([], ['draft_layout' => $this->defaultLayout()]);
        $oldLayout = $template->draft_layout;
        $template->update(['draft_layout' => $layout]);
        Audit::log('UPDATE_MPP_TICKET_TEMPLATE_DRAFT', $template, ['layout' => $layout], ['layout' => $oldLayout]);

        return back()->with('success', 'Draft template disimpan.');
    }

    public function publish(Request $request): RedirectResponse
    {
        $layout = $this->validatedLayout($request);
        $template = MppTicketTemplate::query()->firstOrCreate([], ['draft_layout' => $this->defaultLayout()]);

        DB::transaction(function () use ($template, $layout): void {
            $template = MppTicketTemplate::query()->lockForUpdate()->findOrFail($template->id);
            $version = (int) MppTicketTemplateVersion::query()->where('mpp_ticket_template_id', $template->id)->max('version') + 1;
            $published = MppTicketTemplateVersion::create([
                'mpp_ticket_template_id' => $template->id,
                'version' => $version,
                'layout' => $layout,
                'checksum' => hash('sha256', json_encode($layout, JSON_THROW_ON_ERROR)),
                'published_by' => auth()->id(),
                'published_at' => now(),
            ]);
            $template->update(['draft_layout' => $layout, 'active_version_id' => $published->id]);
            Audit::log('PUBLISH_MPP_TICKET_TEMPLATE', $published, ['version' => $published->version, 'checksum' => $published->checksum]);
        });

        return back()->with('success', 'Template dipublikasikan sebagai versi baru.');
    }

    public function rollback(MppTicketTemplateVersion $version): RedirectResponse
    {
        $template = MppTicketTemplate::query()->firstOrCreate([], ['draft_layout' => $this->defaultLayout()]);

        abort_unless($version->mpp_ticket_template_id === $template->id, 404);

        DB::transaction(function () use ($template, $version): void {
            $template = MppTicketTemplate::query()->lockForUpdate()->findOrFail($template->id);
            $oldVersionId = $template->active_version_id;
            $template->update(['draft_layout' => $version->layout, 'active_version_id' => $version->id]);
            Audit::log('ROLLBACK_MPP_TICKET_TEMPLATE', $template, ['active_version_id' => $version->id], ['active_version_id' => $oldVersionId]);
        });

        return back()->with('success', "Template dipulihkan ke versi {$version->version}.");
    }

    private function validatedLayout(Request $request): array
    {
        $validated = $request->validate(['layout' => ['required', 'json', 'max:10000']]);
        $layout = json_decode($validated['layout'], true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($layout) || ! isset($layout['blocks']) || ! is_array($layout['blocks'])) {
            throw ValidationException::withMessages(['layout' => 'Layout harus memiliki daftar blok.']);
        }

        $allowed = ['logo', 'title', 'subtitle', 'queue_number', 'priority', 'service', 'agency', 'gerai', 'remaining_queue', 'datetime', 'barcode', 'divider', 'footer'];
        $hasQueueNumber = false;
        foreach ($layout['blocks'] as $block) {
            if (! is_array($block) || ! in_array($block['type'] ?? null, $allowed, true)) {
                throw ValidationException::withMessages(['layout' => 'Blok template tidak valid.']);
            }

            if (isset($block['align']) && ! in_array($block['align'], ['left', 'center', 'right'], true)) {
                throw ValidationException::withMessages(['layout' => 'Rata teks blok tidak valid.']);
            }

            if (isset($block['text']) && (! is_string($block['text']) || mb_strlen($block['text']) > 200)) {
                throw ValidationException::withMessages(['layout' => 'Teks blok tidak valid atau terlalu panjang.']);
            }

            $hasQueueNumber = $hasQueueNumber || $block['type'] === 'queue_number';
        }

        if (! $hasQueueNumber) {
            throw ValidationException::withMessages(['layout' => 'Nomor antrian wajib ditampilkan pada template.']);
        }

        return $layout;
    }

    private function defaultLayout(): array
    {
        return ['schema' => 1, 'paper_width_mm' => 58, 'print_width_mm' => 55, 'blocks' => [
            ['type' => 'logo', 'align' => 'center'],
            ['type' => 'divider'],
            ['type' => 'title', 'text' => 'NOMOR ANTRIAN', 'align' => 'center'],
            ['type' => 'queue_number', 'align' => 'center'],
            ['type' => 'gerai', 'align' => 'center'],
            ['type' => 'remaining_queue', 'align' => 'center'],
            ['type' => 'datetime', 'align' => 'center'],
            ['type' => 'barcode', 'align' => 'center'],
            ['type' => 'divider'],
            ['type' => 'footer', 'text' => 'Mohon menunggu petugas kami akan memanggil nomor antrian Anda. Terima kasih.', 'align' => 'center'],
            ['type' => 'divider'],
            ['type' => 'footer', 'text' => 'Catatan:', 'align' => 'left'],
        ]];
    }
}
