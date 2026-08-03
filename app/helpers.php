<?php

use App\Support\ImageOptimizer;
use Illuminate\Support\Facades\Storage;

if (! function_exists('img_url')) {
    /**
     * Kembalikan URL versi WebP bila tersedia, fallback ke file asli.
     */
    function img_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        static $memo = [];

        if (array_key_exists($path, $memo)) {
            return $memo[$path];
        }

        $disk = Storage::disk('public');
        $webp = ImageOptimizer::webpPath($path);

        $memo[$path] = $disk->exists($webp)
            ? $disk->url($webp)
            : $disk->url($path);

        return $memo[$path];
    }
}

if (! function_exists('versioned_asset')) {
    /**
     * URL asset dengan query versi berbasis waktu modifikasi file
     * sehingga cache jangka panjang tetap aman (auto cache-busting).
     */
    function versioned_asset(string $path): string
    {
        $full = public_path($path);
        $version = is_file($full) ? (string) filemtime($full) : '0';

        return asset($path).'?v='.$version;
    }
}
