<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CmsMediaService
{
    /**
     * Upload, optimize and store media.
     *
     * @param  string  $folder  Subfolder inside public storage (e.g., 'articles', 'banners')
     * @param  int|null  $maxWidth  Resize width limit, null for no resizing
     * @return string Relative path of the stored file
     */
    public function upload(UploadedFile $file, string $folder = 'general', ?int $maxWidth = 1200): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(40);
        $subPath = 'cms/'.date('Y/m').'/'.$folder;

        // Check if GD is enabled and file is a web-friendly image
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (extension_loaded('gd') && in_array($extension, $imageExtensions)) {
            // Attempt to load and optimize using GD
            try {
                $imagePath = $this->optimizeAndConvertToWebp($file, $subPath, $filename, $maxWidth);
                if ($imagePath) {
                    return $imagePath;
                }
            } catch (\Throwable $e) {
                // Fallback to direct upload if optimization fails
                logger()->error('Image optimization failed: '.$e->getMessage());
            }
        }

        // Direct upload fallback
        $fullFilename = $filename.'.'.$extension;

        return Storage::disk('public')->putFileAs($subPath, $file, $fullFilename);
    }

    /**
     * Optimize and convert image to WebP format using GD extension.
     */
    protected function optimizeAndConvertToWebp(UploadedFile $file, string $subPath, string $filename, ?int $maxWidth): ?string
    {
        $imageContent = file_get_contents($file->getRealPath());
        if (! $imageContent) {
            return null;
        }

        $image = @imagecreatefromstring($imageContent);
        if (! $image) {
            return null;
        }

        // Enable alpha blending and save alpha for PNG transparency
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        // Resize if necessary
        if ($maxWidth && $width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) (($height / $width) * $newWidth);

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);

            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resizedImage;
        }

        // Save output to temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'cms_img');

        // Output as webp (quality 80)
        if (! imagewebp($image, $tempFile, 80)) {
            imagedestroy($image);
            @unlink($tempFile);

            return null;
        }

        imagedestroy($image);

        // Put to storage
        $storedFilename = $filename.'.webp';
        $finalPath = $subPath.'/'.$storedFilename;

        Storage::disk('public')->put($finalPath, file_get_contents($tempFile));
        @unlink($tempFile);

        return $finalPath;
    }

    /**
     * Delete media file from storage.
     */
    public function delete(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
