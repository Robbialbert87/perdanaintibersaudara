@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: .2; }
}
.bi-hourglass-split.blink {
    animation: blink 1.2s ease-in-out infinite;
}
.stat-card {
    border: none;
    border-radius: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12) !important;
}
.stat-card .card-body {
    padding: 1.5rem;
}
.stat-card .icon-wrapper {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.stat-card .stat-footer {
    padding: 0.75rem 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.15);
    transition: background 0.2s;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    letter-spacing: 0.3px;
}
.stat-card .stat-footer:hover {
    background: rgba(255,255,255,0.06);
}
</style>
@endpush

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card stat-card shadow-sm" style="background: linear-gradient(135deg, #1a237e, #3949ab);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white text-opacity-75 text-uppercase small fw-semibold mb-1" style="letter-spacing: 0.5px;">Invoice</p>
                        <h2 class="text-white mb-2 fw-bold">{{ \App\Models\Invoice::count() }} Total</h2>
                        <div class="d-flex gap-3">
                            <span class="text-white-50 small"><i class="bi bi-check-circle-fill text-success me-1"></i> Lunas: {{ \App\Models\Invoice::where('status', 'dibayar')->count() }}</span>
                            @php $belumLunas = \App\Models\Invoice::whereIn('status', ['draft', 'dikirim'])->count(); @endphp
                            <span class="text-white-50 small"><i class="bi bi-hourglass-split text-warning me-1{{ $belumLunas > 0 ? ' blink' : '' }}"></i> Belum Lunas: {{ $belumLunas }}</span>
                        </div>
                    </div>
                    <div class="icon-wrapper" style="background: rgba(255,255,255,0.15); color: white;">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
            <a href="{{ route('invoices.index') }}" class="stat-footer d-flex align-items-center justify-content-between text-white">
                <span>Lihat Detail</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card shadow-sm" style="background: linear-gradient(135deg, #13447f, #065cc2);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white text-opacity-75 text-uppercase small fw-semibold mb-1" style="letter-spacing: 0.5px;">Layanan</p>
                        <h2 class="text-white mb-0 fw-bold">{{ \App\Models\Service::count() }}</h2>
                    </div>
                    <div class="icon-wrapper" style="background: rgba(255,255,255,0.15); color: white;">
                        <i class="bi bi-gear"></i>
                    </div>
                </div>
            </div>
            <a href="{{ route('services.index') }}" class="stat-footer d-flex align-items-center justify-content-between text-white">
                <span>Lihat Detail</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card stat-card shadow-sm" style="background: linear-gradient(135deg, #1a7a3a, #28a745);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white text-opacity-75 text-uppercase small fw-semibold mb-1" style="letter-spacing: 0.5px;">Produk</p>
                        <h2 class="text-white mb-0 fw-bold">{{ \App\Models\Product::count() }}</h2>
                    </div>
                    <div class="icon-wrapper" style="background: rgba(255,255,255,0.15); color: white;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="stat-footer d-flex align-items-center justify-content-between text-white">
                <span>Lihat Detail</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card shadow-sm" style="background: linear-gradient(135deg, #b4521a, #e67e22);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white text-opacity-75 text-uppercase small fw-semibold mb-1" style="letter-spacing: 0.5px;">Kegiatan</p>
                        <h2 class="text-white mb-0 fw-bold">{{ \App\Models\Activity::count() }}</h2>
                    </div>
                    <div class="icon-wrapper" style="background: rgba(255,255,255,0.15); color: white;">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
            </div>
            <a href="{{ route('activities.index') }}" class="stat-footer d-flex align-items-center justify-content-between text-white">
                <span>Lihat Detail</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection
