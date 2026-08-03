<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class ImageOptimizer
{
    public function __construct(
        protected int $maxWidth = 1600,
        protected int $quality = 85,
    ) {
    }

    public static function webpPath(string $path): string
    {
        return (string) preg_replace('/\.(jpe?g|png|gif)$/i', '.webp', $path);
    }

    public function optimizeStoragePath(string $path, string $disk = 'public', ?int $maxWidth = null, ?int $quality = null): ?string
    {
        $fs = Storage::disk($disk);

        if (! $fs->exists($path) || ! $this->isSupported($path)) {
            return null;
        }

        if ($this->encode($fs->path($path), $maxWidth, $quality)) {
            return self::webpPath($path);
        }

        return null;
    }

    public function optimizeAbsolutePath(string $path, ?int $maxWidth = null, ?int $quality = null): ?string
    {
        if (! is_file($path) || ! $this->isSupported($path)) {
            return null;
        }

        return $this->encode($path, $maxWidth, $quality) ? self::webpPath($path) : null;
    }

    public function isStale(string $source): bool
    {
        $dest = self::webpPath($source);

        if (! is_file($dest)) {
            return true;
        }

        return filemtime($dest) < filemtime($source);
    }

    protected function isSupported(string $path): bool
    {
        return (bool) preg_match('/\.(jpe?g|png|gif)$/i', $path);
    }

    protected function encode(string $source, ?int $maxWidth, ?int $quality): bool
    {
        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));

        $image = match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($source),
            'png' => @imagecreatefrompng($source),
            'gif' => @imagecreatefromgif($source),
            default => false,
        };

        if (! $image) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $targetWidth = $maxWidth ?? $this->maxWidth;
        $newWidth = $width;
        $newHeight = $height;

        if ($width > $targetWidth) {
            $newHeight = (int) round($height * ($targetWidth / $width));
            $newWidth = $targetWidth;
        }

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $dest = self::webpPath($source);
        $ok = imagewebp($resized, $dest, $quality ?? $this->quality);

        imagedestroy($image);
        imagedestroy($resized);

        if (! $ok) {
            @unlink($dest);

            return false;
        }

        return true;
    }
}
