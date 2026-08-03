<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Product;
use App\Models\Service;
use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize';

    protected $description = 'Optimalkan semua gambar (resize + WebP) tanpa menghapus file asli';

    public function handle(ImageOptimizer $optimizer): int
    {
        $count = 0;

        $count += $this->optimizeDatabaseImages($optimizer);
        $count += $this->optimizeStorageDirs($optimizer);
        $count += $this->optimizeStaticPublic($optimizer);

        $this->info("Selesai. {$count} gambar dioptimalkan.");

        return self::SUCCESS;
    }

    protected function optimizeDatabaseImages(ImageOptimizer $optimizer): int
    {
        $paths = collect();

        foreach (Service::all() as $service) {
            foreach ($service->images ?? [] as $img) {
                $paths->push($img);
            }
            if ($service->image) {
                $paths->push($service->image);
            }
        }

        foreach (Product::all() as $product) {
            foreach ($product->images ?? [] as $img) {
                $paths->push($img);
            }
        }

        foreach (Activity::all() as $activity) {
            foreach ($activity->images ?? [] as $img) {
                $paths->push($img);
            }
        }

        return $this->optimizeStoragePaths($paths->unique()->values(), $optimizer);
    }

    protected function optimizeStorageDirs(ImageOptimizer $optimizer): int
    {
        $paths = collect();

        foreach (['services', 'products', 'activities'] as $dir) {
            $files = Storage::disk('public')->allFiles($dir);

            foreach ($files as $file) {
                if (preg_match('/\.(jpe?g|png|gif)$/i', $file)) {
                    $paths->push($file);
                }
            }
        }

        return $this->optimizeStoragePaths($paths, $optimizer);
    }

    protected function optimizeStoragePaths(Collection $paths, ImageOptimizer $optimizer): int
    {
        $count = 0;

        foreach ($paths as $path) {
            $fs = Storage::disk('public');

            if (! $fs->exists($path)) {
                continue;
            }

            if (! $optimizer->isStale($fs->path($path))) {
                $this->line("UP-TO-DATE {$path}");
                continue;
            }

            if ($optimizer->optimizeStoragePath($path)) {
                $this->info("OK   {$path}");
                $count++;
            } else {
                $this->warn("SKIP {$path}");
            }
        }

        return $count;
    }

    protected function optimizeStaticPublic(ImageOptimizer $optimizer): int
    {
        $count = 0;

        $targeted = [
            [public_path('style/assets/img/PIBnew.png'), 300],
            [public_path('style/assets/img/pib-logo.png'), 300],
            [public_path('style/assets/img/health/WhatsApp Image 2026-05-21 at 11.49.39.jpeg'), 1000],
            [public_path('style/assets/img/health/Gemini_Generated_Image_mnrhe1mnrhe1mnrh.png'), 1200],
        ];

        foreach ($targeted as [$file, $width]) {
            $count += $this->optimizeFile($file, $optimizer, $width);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(public_path('style/assets/img'), \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            $file = $fileInfo->getPathname();

            if ($fileInfo->getSize() < 30 * 1024) {
                continue;
            }

            if (! preg_match('/\.(jpe?g|png|gif)$/i', $file)) {
                continue;
            }

            $count += $this->optimizeFile($file, $optimizer);
        }

        return $count;
    }

    protected function optimizeFile(string $file, ImageOptimizer $optimizer, int $width = 1600): int
    {
        if (! is_file($file)) {
            return 0;
        }

        if (! $optimizer->isStale($file)) {
            $this->line("UP-TO-DATE {$file}");

            return 0;
        }

        if ($optimizer->optimizeAbsolutePath($file, $width)) {
            $this->info("OK   {$file}");
            return 1;
        }

        $this->warn("SKIP {$file}");

        return 0;
    }
}
