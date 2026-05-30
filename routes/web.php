<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QuotationController;
use App\Models\Activity;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::latest()->take(15)->get();
    $activities = Activity::latest()->take(15)->get();

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
    $activity = Activity::findOrFail($id);

    return view('style.kegiatan-detail', compact('activity'));
})->name('kegiatan.detail');

Route::get('/admin', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('activities', ActivityController::class)->except(['show']);
    Route::get('quotations/{quotation}/export-pdf', [QuotationController::class, 'exportPdf'])->name('quotations.export_pdf');
    Route::resource('quotations', QuotationController::class);
});

// require __DIR__.'/settings.php';
