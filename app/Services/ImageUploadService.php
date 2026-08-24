<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Convert and store an uploaded image as WebP with compression & auto-resize.
     *
     * @param UploadedFile $file
     * @param string $directory (e.g. 'articles', 'news', 'dokumentasi')
     * @param int $quality (1-100, default 82)
     * @param int $maxWidth (default 1920)
     * @return string Relative path inside public disk (e.g. 'articles/random_name.webp')
     */
    public static function uploadAsWebp(UploadedFile $file, string $directory = 'images', int $quality = 82, int $maxWidth = 1920): string
    {
        $disk = Storage::disk('public');
        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }

        $targetDir = storage_path('app/public/' . trim($directory, '/'));
        if (!file_exists($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $filename = Str::random(40) . '.webp';
        $destinationPath = $targetDir . '/' . $filename;
        $relativePath = trim($directory, '/') . '/' . $filename;

        // Convert using PHP GD
        if (function_exists('imagewebp') && extension_loaded('gd')) {
            try {
                $image = self::createImageFromUploadedFile($file);
                if ($image !== false) {
                    // Auto-rotate if EXIF orientation metadata exists (e.g. phone camera portrait photos)
                    $image = self::autoRotateImage($image, $file);

                    // Resize if width exceeds maxWidth to save bandwidth and memory
                    $image = self::resizeImageIfNeeded($image, $maxWidth);

                    // Output to WebP format
                    $success = imagewebp($image, $destinationPath, $quality);
                    imagedestroy($image);

                    if ($success && file_exists($destinationPath)) {
                        return $relativePath;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('WebP conversion failed, falling back to default store: ' . $e->getMessage());
            }
        }

        // Fallback to standard Laravel store if GD fails
        return $file->store($directory, 'public');
    }

    /**
     * Create GD image resource from UploadedFile.
     */
    private static function createImageFromUploadedFile(UploadedFile $file)
    {
        $mime = strtolower($file->getMimeType() ?: '');
        $path = $file->getRealPath();

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/pjpeg':
                return @imagecreatefromjpeg($path);
            case 'image/png':
            case 'image/x-png':
                $img = @imagecreatefrompng($path);
                if ($img) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                }
                return $img;
            case 'image/webp':
                return @imagecreatefromwebp($path);
            case 'image/gif':
                return @imagecreatefromgif($path);
            case 'image/bmp':
            case 'image/x-ms-bmp':
                return @imagecreatefrombmp($path);
            default:
                // Try from string
                $content = @file_get_contents($path);
                return $content ? @imagecreatefromstring($content) : false;
        }
    }

    /**
     * Auto rotate image based on EXIF data.
     */
    private static function autoRotateImage($image, UploadedFile $file)
    {
        if (!function_exists('exif_read_data')) {
            return $image;
        }

        try {
            $exif = @exif_read_data($file->getRealPath());
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $rotated = imagerotate($image, 180, 0);
                        imagedestroy($image);
                        return $rotated;
                    case 6:
                        $rotated = imagerotate($image, -90, 0);
                        imagedestroy($image);
                        return $rotated;
                    case 8:
                        $rotated = imagerotate($image, 90, 0);
                        imagedestroy($image);
                        return $rotated;
                }
            }
        } catch (\Throwable $e) {
            // Ignore exif errors
        }

        return $image;
    }

    /**
     * Resize image if width exceeds maxWidth while maintaining aspect ratio.
     */
    private static function resizeImageIfNeeded($image, int $maxWidth)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxWidth) {
            return $image;
        }

        $newWidth = $maxWidth;
        $newHeight = (int) round(($height / $width) * $maxWidth);

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }
}
