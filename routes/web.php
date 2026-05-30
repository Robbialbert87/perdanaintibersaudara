<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ServiceController;
use App\Models\Activity;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::latest()->take(15)->get();
    $activities = Activity::latest()->take(15)->get();
    $services = Service::latest()->get();

    return view('style.index', compact('products', 'activities', 'services'));
})->name('home');

Route::get('/about', function () {
    return view('style.about');
})->name('about');

Route::get('/layanan', function () {
    $services = Service::latest()->get();
    return view('style.layanan', compact('services'));
})->name('layanan.page');

Route::get('/produk', function () {
    $products = Product::latest()->get();
    return view('style.produk', compact('products'));
})->name('produk.page');

Route::get('/kegiatan', function () {
    $activities = Activity::latest()->get();
    return view('style.kegiatan', compact('activities'));
})->name('kegiatan.page');

Route::get('/contact', function () {
    return view('style.contact');
})->name('contact.page');
Route::get('/produk/{id}', function ($id) {
    $product = Product::findOrFail($id);

    return view('style.produk-detail', compact('product'));
})->name('produk.detail');

Route::get('/layanan/{id}', function ($id) {
    $service = \App\Models\Service::findOrFail($id);

    return view('style.layanan-detail', compact('service'));
})->name('layanan.detail');

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
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('activities', ActivityController::class)->except(['show']);
    Route::get('quotations/{quotation}/export-pdf', [QuotationController::class, 'exportPdf'])->name('quotations.export_pdf');
    Route::resource('quotations', QuotationController::class);
});

// require __DIR__.'/settings.php';
