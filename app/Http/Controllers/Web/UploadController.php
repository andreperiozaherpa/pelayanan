<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $folder = $request->input('folder', 'uploads');
        $allowedMimes = $request->input('allowed_mimes', 'pdf,jpg,jpeg,png');
        $maxSize = $request->input('max_size', 5120);

        $validated = $request->validate([
            'file' => ['required', 'file', "mimes:{$allowedMimes}", "max:{$maxSize}"],
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store($folder, 'public');

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
