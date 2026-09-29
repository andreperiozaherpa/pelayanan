<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MppTicketTemplate;
use App\Models\MppTicketTemplateVersion;
use Illuminate\Http\JsonResponse;

class MppTicketTemplateController extends Controller
{
    public function active(): JsonResponse
    {
        $template = MppTicketTemplate::query()->with('activeVersion')->first();

        if (! $template?->activeVersion) {
            return response()->json(['success' => false, 'error' => 'Template tiket belum dipublikasikan.'], 404);
        }

        return $this->versionResponse($template->activeVersion);
    }

    public function show(MppTicketTemplateVersion $version): JsonResponse
    {
        return $this->versionResponse($version);
    }

    private function versionResponse(MppTicketTemplateVersion $version): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $version->id,
                'version' => $version->version,
                'schema' => $version->layout['schema'] ?? 1,
                'layout' => $version->layout,
                'checksum' => $version->checksum,
                'published_at' => $version->published_at,
            ],
        ])->setEtag($version->checksum);
    }
}
