<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\WarrantyCardController;
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

Route::get('/home', function () {
    $products = Product::latest()->take(15)->get();
    $activities = Activity::latest()->take(15)->get();
    $services = Service::latest()->get();

    return view('style.index', compact('products', 'activities', 'services'));
})->name('home.page');

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

Route::get('/verify/{token}', [VerifyController::class, 'show'])->name('verify.invoice');
Route::get('/verify-quotation/{token}', [VerifyController::class, 'showQuotation'])->name('verify.quotation');
Route::get('/verify-purchase-order/{token}', [VerifyController::class, 'showPurchaseOrder'])->name('verify.purchase_order');
Route::get('/verify-warranty-card/{token}', [VerifyController::class, 'showWarrantyCard'])->name('verify.warranty_card');
Route::get('/verify-berita-acara/{token}', [VerifyController::class, 'showBeritaAcara'])->name('verify.berita_acara');
Route::get('/produk/{id}', function ($id) {
    $product = Product::findOrFail($id);

    return view('style.produk-detail', compact('product'));
})->name('produk.detail');

Route::get('/layanan/{id}', function ($id) {
    $service = Service::findOrFail($id);

    return view('style.layanan-detail', compact('service'));
})->name('layanan.detail');

Route::get('/kegiatan/{id}', function ($id) {
    $activity = Activity::findOrFail($id);

    return view('style.kegiatan-detail', compact('activity'));
})->name('kegiatan.detail');

Route::get('/admin', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', 'throttle:60,1'])->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::delete('products/{product}/media', [ProductController::class, 'deleteMedia'])->name('products.deleteMedia');
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::delete('services/{service}/media', [ServiceController::class, 'deleteMedia'])->name('services.deleteMedia');
    Route::resource('activities', ActivityController::class)->except(['show']);
    Route::delete('activities/{activity}/media', [ActivityController::class, 'deleteMedia'])->name('activities.deleteMedia');
    Route::get('quotations/{quotation}/export-pdf', [QuotationController::class, 'exportPdf'])->name('quotations.export_pdf');
    Route::resource('quotations', QuotationController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::get('purchase-orders/{purchaseOrder}/export-pdf', [PurchaseOrderController::class, 'exportPdf'])->name('purchase-orders.export_pdf');
    Route::get('purchase-orders/{purchaseOrder}/preview-pdf', [PurchaseOrderController::class, 'previewPdf'])->name('purchase-orders.preview_pdf');
    Route::get('purchase-orders/{purchaseOrder}/print', [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');
    Route::get('warranty-cards/{warrantyCard}/export-pdf', [WarrantyCardController::class, 'exportPdf'])->name('warranty-cards.export_pdf');
    Route::resource('warranty-cards', WarrantyCardController::class);
    Route::get('berita-acaras/{beritaAcara}/export-pdf', [BeritaAcaraController::class, 'exportPdf'])->name('berita-acaras.export_pdf');
    Route::get('berita-acaras/{beritaAcara}/preview-pdf', [BeritaAcaraController::class, 'previewPdf'])->name('berita-acaras.preview_pdf');
    Route::get('berita-acaras/{beritaAcara}/print', [BeritaAcaraController::class, 'print'])->name('berita-acaras.print');
    Route::resource('berita-acaras', BeritaAcaraController::class);
    Route::get('invoices/{invoice}/export-pdf', [InvoiceController::class, 'exportPdf'])->name('invoices.export_pdf');
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark_paid');
    Route::post('invoices/ai-generate', [InvoiceController::class, 'aiGenerate'])->name('invoices.ai_generate');
    Route::post('invoices/ai-store', [InvoiceController::class, 'aiStore'])->name('invoices.ai_store');
    Route::post('invoices/ai-store-draft', [InvoiceController::class, 'aiStoreDraft'])->name('invoices.ai_store_draft');
    Route::post('invoices/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');
    Route::resource('invoices', InvoiceController::class);
    Route::resource('users', UserController::class)->except(['show']);
});

Route::get('/manifest.json', function () {
    return response(file_get_contents(public_path('manifest-content.json')))
        ->header('Content-Type', 'application/manifest+json')
        ->header('Cache-Control', 'no-cache');
})->name('manifest');

Route::get('/sw.js', function () {
    return response(file_get_contents(public_path('sw-content.js')))
        ->header('Content-Type', 'application/javascript')
        ->header('Cache-Control', 'no-cache');
})->name('sw');

// require __DIR__.'/settings.php';
