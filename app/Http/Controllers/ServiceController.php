<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'videos' => 'nullable|array|max:3',
            'videos.*' => 'mimes:mp4,avi,mov,mkv,webm,flv,wmv|max:102400',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('services', 'public');
            }
        }
        $validated['images'] = $imagePaths;
        $validated['active_images'] = $imagePaths;

        $videoPaths = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $videoPaths[] = $file->store('services/videos', 'public');
            }
        }
        $validated['videos'] = $videoPaths;
        $validated['active_videos'] = $videoPaths;

        // Backward compat: set image from first uploaded image
        if (! empty($imagePaths)) {
            $validated['image'] = $imagePaths[0];
        } elseif ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // Clean up empty features
        if (! empty($validated['features'])) {
            $validated['features'] = array_values(array_filter($validated['features'], function ($val) {
                return ! empty(trim($val));
            }));
        } else {
            $validated['features'] = [];
        }

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'active_images' => 'nullable|array',
            'active_images.*' => 'string',
            'active_videos' => 'nullable|array',
            'active_videos.*' => 'string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
            'videos' => 'nullable|array|max:3',
            'videos.*' => 'mimes:mp4,avi,mov,mkv,webm,flv,wmv|max:102400',
        ]);

        $newImagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $newImagePaths[] = $file->store('services', 'public');
            }
        }

        $activeImages = $request->input('active_images', []);
        $finalImages = array_slice(array_merge($service->images ?? [], $newImagePaths), 0, 5);
        $validated['images'] = $finalImages;
        $validated['active_images'] = array_merge($activeImages, $newImagePaths);

        // Backward compat: update image from first active or uploaded
        if (! empty($newImagePaths)) {
            $validated['image'] = $newImagePaths[0];
        } elseif (! empty($activeImages)) {
            $validated['image'] = $activeImages[0];
        }

        $newVideoPaths = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $newVideoPaths[] = $file->store('services/videos', 'public');
            }
        }

        $activeVideos = $request->input('active_videos', []);
        $finalVideos = array_slice(array_merge($service->videos ?? [], $newVideoPaths), 0, 3);
        $validated['videos'] = $finalVideos;
        $validated['active_videos'] = array_merge($activeVideos, $newVideoPaths);

        // Handle single image upload (legacy field)
        if ($request->hasFile('image') && empty($newImagePaths)) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // Clean up empty features
        if (! empty($validated['features'])) {
            $validated['features'] = array_values(array_filter($validated['features'], function ($val) {
                return ! empty(trim($val));
            }));
        } else {
            $validated['features'] = [];
        }

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function deleteMedia(Request $request, Service $service)
    {
        $request->validate(['file' => 'required|string']);

        $file = $request->input('file');

        if (! Str::startsWith($file, ['services/', 'services/videos/'])) {
            return redirect()->back()->with('error', 'Path file tidak valid.');
        }

        // Handle legacy image field
        if ($service->image === $file) {
            Storage::disk('public')->delete($file);
            $service->image = null;
        }

        $images = $service->images ?? [];
        if (($key = array_search($file, $images)) !== false) {
            Storage::disk('public')->delete($file);
            unset($images[$key]);
            $service->images = array_values($images);
        }

        $videos = $service->videos ?? [];
        if (($key = array_search($file, $videos)) !== false) {
            Storage::disk('public')->delete($file);
            unset($videos[$key]);
            $service->videos = array_values($videos);
        }

        $service->save();

        return redirect()->back()->with('success', 'Media berhasil dihapus.');
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        if ($service->images) {
            foreach ($service->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        if ($service->videos) {
            foreach ($service->videos as $vid) {
                Storage::disk('public')->delete($vid);
            }
        }
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
