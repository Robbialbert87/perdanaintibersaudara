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

if (! function_exists('terbilangKwitansi')) {
    /**
     * Ubah angka menjadi tulisan terbilang dalam Bahasa Indonesia.
     */
    function terbilangKwitansi(int|float|string $angka): string
    {
        $angka = abs((int) $angka);
        $bilangan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        $hasil = '';
        if ($angka < 12) {
            $hasil = $bilangan[$angka];
        } elseif ($angka < 20) {
            $hasil = $bilangan[$angka - 10].' belas';
        } elseif ($angka < 100) {
            $hasil = $bilangan[(int) ($angka / 10)].' puluh '.$bilangan[$angka % 10];
        } elseif ($angka < 200) {
            $sisa = $angka - 100;
            $hasil = 'seratus'.($sisa > 0 ? ' '.terbilangKwitansi($sisa) : '');
        } elseif ($angka < 1000) {
            $sisa = $angka % 100;
            $hasil = $bilangan[(int) ($angka / 100)].' ratus'.($sisa > 0 ? ' '.terbilangKwitansi($sisa) : '');
        } elseif ($angka < 2000) {
            $sisa = $angka - 1000;
            $hasil = 'seribu'.($sisa > 0 ? ' '.terbilangKwitansi($sisa) : '');
        } elseif ($angka < 1000000) {
            $sisa = $angka % 1000;
            $hasil = terbilangKwitansi((int) ($angka / 1000)).' ribu'.($sisa > 0 ? ' '.terbilangKwitansi($sisa) : '');
        } elseif ($angka < 1000000000) {
            $sisa = $angka % 1000000;
            $hasil = terbilangKwitansi((int) ($angka / 1000000)).' juta'.($sisa > 0 ? ' '.terbilangKwitansi($sisa) : '');
        } elseif ($angka < 1000000000000) {
            $sisa = $angka % 1000000000;
            $hasil = terbilangKwitansi((int) ($angka / 1000000000)).' miliar'.($sisa > 0 ? ' '.terbilangKwitansi($sisa) : '');
        } else {
            $hasil = '~';
        }

        return trim($hasil) ?: 'nol';
    }
}
