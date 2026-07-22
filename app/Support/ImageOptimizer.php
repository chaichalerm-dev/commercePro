<?php

declare(strict_types=1);

namespace App\Support;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Downscales oversized uploads and re-encodes them as WebP before storing,
 * so a multi-MB camera-resolution admin upload isn't served byte-for-byte
 * to every consumer — a 60px cart icon and a full product-detail image were
 * previously the exact same file. Formats GD can't decode (e.g. .ico) are
 * stored untouched.
 */
class ImageOptimizer
{
    /**
     * @param  ?string  $replacing  path of the file this upload replaces, if any —
     *                              deleted first (see delete()) so admin edits don't
     *                              leak orphaned files on the disk.
     */
    public static function store(UploadedFile $file, string $dir, string $disk, int $maxWidth, int $maxHeight, int $quality = 82, ?string $replacing = null): string
    {
        if ($replacing !== null) {
            self::delete($replacing, $disk);
        }

        [$contents, $extension] = self::process($file, $maxWidth, $maxHeight, $quality);

        $path = trim($dir, '/').'/'.Str::uuid()->toString().'.'.$extension;

        Storage::disk($disk)->put($path, $contents, [
            'visibility' => 'public',
            // Uploaded files always get a fresh random name (never reused),
            // so it's safe to tell browsers/CDNs to cache them forever —
            // an edit produces a new path rather than overwriting this one.
            'CacheControl' => 'public, max-age=31536000, immutable',
        ]);

        return $path;
    }

    /**
     * Delete a stored file, skipping external URLs (demo/seed data, which
     * has nothing to delete). Shared by every controller/service that
     * replaces or removes an uploaded image, so the "is this a local path
     * or a passthrough URL" check exists in exactly one place.
     */
    public static function delete(?string $path, string $disk): void
    {
        if (filled($path) && ! Str::startsWith($path, ['http://', 'https://'])) {
            Storage::disk($disk)->delete($path);
        }
    }

    /**
     * @return array{0: string, 1: string} [raw file contents, extension]
     */
    private static function process(UploadedFile $file, int $maxWidth, int $maxHeight, int $quality): array
    {
        $source = self::load($file);

        if ($source === null) {
            return [
                (string) file_get_contents($file->getRealPath()),
                strtolower((string) $file->getClientOriginalExtension()),
            ];
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(1.0, $maxWidth / $width, $maxHeight / $height);

        if ($scale < 1.0) {
            $newWidth = max(1, (int) round($width * $scale));
            $newHeight = max(1, (int) round($height * $scale));

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        ob_start();
        imagewebp($source, quality: $quality);
        $contents = (string) ob_get_clean();
        imagedestroy($source);

        return [$contents, 'webp'];
    }

    private static function load(UploadedFile $file): ?GdImage
    {
        $path = $file->getRealPath();

        if ($path === false) {
            return null;
        }

        $image = match ($file->getMimeType()) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/bmp' => @imagecreatefrombmp($path),
            default => false,
        };

        return $image instanceof GdImage ? $image : null;
    }
}
