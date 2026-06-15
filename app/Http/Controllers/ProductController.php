<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return view('admin.products.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'spesifikasi' => 'nullable|string',
            'satuan' => 'nullable|string|max:50',
            'harga_default' => 'nullable|numeric|min:0',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'videos' => 'nullable|array|max:3',
            'videos.*' => 'mimes:mp4,avi,mov,mkv,webm,flv,wmv|max:102400',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('products', 'public');
            }
        }
        $validated['images'] = $imagePaths;
        $validated['active_images'] = $imagePaths;

        $videoPaths = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $videoPaths[] = $file->store('products/videos', 'public');
            }
        }
        $validated['videos'] = $videoPaths;
        $validated['active_videos'] = $videoPaths;

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'spesifikasi' => 'nullable|string',
            'satuan' => 'nullable|string|max:50',
            'harga_default' => 'nullable|numeric|min:0',
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
                $newImagePaths[] = $file->store('products', 'public');
            }
        }

        $activeImages = $request->input('active_images', []);
        $finalImages = array_slice(array_merge($product->images ?? [], $newImagePaths), 0, 5);
        $validated['images'] = $finalImages;
        $validated['active_images'] = array_merge($activeImages, $newImagePaths);

        $newVideoPaths = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $newVideoPaths[] = $file->store('products/videos', 'public');
            }
        }

        $activeVideos = $request->input('active_videos', []);
        $finalVideos = array_slice(array_merge($product->videos ?? [], $newVideoPaths), 0, 3);
        $validated['videos'] = $finalVideos;
        $validated['active_videos'] = array_merge($activeVideos, $newVideoPaths);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function deleteMedia(Request $request, Product $product)
    {
        $request->validate(['file' => 'required|string']);

        $file = $request->input('file');

        if (! Str::startsWith($file, ['products/', 'products/videos/'])) {
            return redirect()->back()->with('error', 'Path file tidak valid.');
        }

        $images = $product->images ?? [];
        if (($key = array_search($file, $images)) !== false) {
            Storage::disk('public')->delete($file);
            unset($images[$key]);
            $product->images = array_values($images);
        }

        $videos = $product->videos ?? [];
        if (($key = array_search($file, $videos)) !== false) {
            Storage::disk('public')->delete($file);
            unset($videos[$key]);
            $product->videos = array_values($videos);
        }

        $product->save();

        return redirect()->back()->with('success', 'Media berhasil dihapus.');
    }

    public function destroy(Product $product)
    {
        if ($product->images) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        if ($product->videos) {
            foreach ($product->videos as $vid) {
                Storage::disk('public')->delete($vid);
            }
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
