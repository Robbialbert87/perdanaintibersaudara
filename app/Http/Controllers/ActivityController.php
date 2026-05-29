<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::latest()->get();
        return view('admin.activities.index', [
            'activities' => $activities
        ]);
    }

    public function create()
    {
        return view('admin.activities.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'date'    => 'required|date',
            'images'  => 'nullable|array|max:5',
            'images.*'=> 'image|max:5120',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('activities', 'public');
            }
        }
        $validated['images'] = $imagePaths;

        Activity::create($validated);

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Activity $activity)
    {
        return view('admin.activities.form', [
            'activity' => $activity
        ]);
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'date'    => 'required|date',
            'kept_images' => 'nullable|array',
            'kept_images.*' => 'string',
            'images'  => 'nullable|array|max:5',
            'images.*'=> 'image|max:5120',
        ]);

        $currentImages = $activity->images ?? [];
        $keptImages = $request->input('kept_images', []);

        // Delete images that are not kept
        foreach ($currentImages as $img) {
            if (!in_array($img, $keptImages)) {
                Storage::disk('public')->delete($img);
            }
        }

        // Add new images
        $newImagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $newImagePaths[] = $file->store('activities', 'public');
            }
        }

        // Combine kept images and new images (limit to 5 total)
        $finalImages = array_slice(array_merge($keptImages, $newImagePaths), 0, 5);
        $validated['images'] = $finalImages;
        unset($validated['kept_images']);

        $activity->update($validated);

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity)
    {
        if ($activity->images) {
            foreach ($activity->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
