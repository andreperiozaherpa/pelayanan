<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CmsMediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CmsMediaController extends Controller
{
    public function __construct(
        protected CmsMediaService $mediaService
    ) {}

    /**
     * Upload file asynchronously for Dropzone.js.
     */
    public function upload(Request $request): JsonResponse
    {
        if (! auth()->user() || ! auth()->user()->hasAnyPermission([
            'cms.articles.create', 'cms.articles.edit',
            'cms.banners.create', 'cms.banners.edit',
            'cms.testimonials.create', 'cms.testimonials.edit',
            'cms.teams.create', 'cms.teams.edit',
            'cms.settings.edit',
            'cms.pages.create', 'cms.pages.edit',
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk mengunggah media.',
            ], 403);
        }

        $request->validate([
            'file' => ['required', 'file', 'image', 'max:5120'], // Max 5MB
            'folder' => ['nullable', 'string', 'max:50'],
        ]);

        $folder = $request->input('folder', 'general');
        $file = $request->file('file');

        try {
            $path = $this->mediaService->upload($file, $folder);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => asset('storage/'.$path),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah berkas: '.$e->getMessage(),
            ], 500);
        }
    }
}
