<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::latest()->get();

        return view('admin.activities.index', [
            'activities' => $activities,
        ]);
    }

    public function create()
    {
        return view('admin.activities.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'videos' => 'nullable|array|max:3',
            'videos.*' => 'mimes:mp4,avi,mov,mkv,webm,flv,wmv|max:102400',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('activities', 'public');
            }
        }
        $validated['images'] = $imagePaths;
        $validated['active_images'] = $imagePaths;

        $videoPaths = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $videoPaths[] = $file->store('activities/videos', 'public');
            }
        }
        $validated['videos'] = $videoPaths;
        $validated['active_videos'] = $videoPaths;

        Activity::create($validated);

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Activity $activity)
    {
        return view('admin.activities.form', [
            'activity' => $activity,
        ]);
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
            'active_images' => 'nullable|array',
            'active_images.*' => 'string',
            'active_videos' => 'nullable|array',
            'active_videos.*' => 'string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'videos' => 'nullable|array|max:3',
            'videos.*' => 'mimes:mp4,avi,mov,mkv,webm,flv,wmv|max:102400',
        ]);

        $newImagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $newImagePaths[] = $file->store('activities', 'public');
            }
        }

        $activeImages = $request->input('active_images', []);
        $finalImages = array_slice(array_merge($activity->images ?? [], $newImagePaths), 0, 5);
        $validated['images'] = $finalImages;
        $validated['active_images'] = array_merge($activeImages, $newImagePaths);

        $newVideoPaths = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $newVideoPaths[] = $file->store('activities/videos', 'public');
            }
        }

        $activeVideos = $request->input('active_videos', []);
        $finalVideos = array_slice(array_merge($activity->videos ?? [], $newVideoPaths), 0, 3);
        $validated['videos'] = $finalVideos;
        $validated['active_videos'] = array_merge($activeVideos, $newVideoPaths);

        $activity->update($validated);

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function deleteMedia(Request $request, Activity $activity)
    {
        $request->validate(['file' => 'required|string']);

        $file = $request->input('file');

        if (! Str::startsWith($file, ['activities/', 'activities/videos/'])) {
            return redirect()->back()->with('error', 'Path file tidak valid.');
        }

        $images = $activity->images ?? [];
        if (($key = array_search($file, $images)) !== false) {
            Storage::disk('public')->delete($file);
            unset($images[$key]);
            $activity->images = array_values($images);
        }

        $videos = $activity->videos ?? [];
        if (($key = array_search($file, $videos)) !== false) {
            Storage::disk('public')->delete($file);
            unset($videos[$key]);
            $activity->videos = array_values($videos);
        }

        $activity->save();

        return redirect()->back()->with('success', 'Media berhasil dihapus.');
    }

    public function destroy(Activity $activity)
    {
        if ($activity->images) {
            foreach ($activity->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        if ($activity->videos) {
            foreach ($activity->videos as $vid) {
                Storage::disk('public')->delete($vid);
            }
        }
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
