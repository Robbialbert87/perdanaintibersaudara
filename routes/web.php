<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProductController;
use App\Models\Activity;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products    = Product::latest()->get();
    $activities  = Activity::latest()->take(3)->get();
    return view('style.index', compact('products', 'activities'));
})->name('home');

Route::get('/about', function () {
    return view('style.about');
})->name('about');

Route::get('/produk/{id}', function ($id) {
    $product = Product::findOrFail($id);
    return view('style.produk-detail', compact('product'));
})->name('produk.detail');

Route::get('/kegiatan/{id}', function ($id) {
    $activity = App\Models\Activity::findOrFail($id);
    return view('style.kegiatan-detail', compact('activity'));
})->name('kegiatan.detail');

Route::get('/admin', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('activities', ActivityController::class)->except(['show']);
});

require __DIR__.'/settings.php';
