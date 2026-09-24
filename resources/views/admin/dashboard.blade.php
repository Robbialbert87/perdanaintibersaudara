@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    $totalInvoice = \App\Models\Invoice::count();
    $lunas   = \App\Models\Invoice::where('status', 'dibayar')->count();
    $belumLunas = \App\Models\Invoice::whereIn('status', ['draft', 'dikirim'])->count();
    $pendapatan = \App\Models\Invoice::where('status', 'dibayar')->sum('total');
    $jmlLayanan  = \App\Models\Service::count();
    $jmlProduk   = \App\Models\Product::count();
    $jmlKegiatan = \App\Models\Activity::count();
    $pengunjungHariIni  = \App\Models\Visitor::whereDate('created_at', now('Asia/Jakarta')->toDateString())->count();
    $pengunjungBulanIni = \App\Models\Visitor::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
    $invoiceTerbaru = \App\Models\Invoice::with('customer')->latest()->take(6)->get();
    $customerBaru   = \App\Models\Customer::latest()->take(5)->get();
    $namaDepan = ucwords(trim((strtok(auth()->user()->name ?? 'Admin', ' ') ?: 'Admin')));
@endphp

{{-- Hero --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 hero-card h-100">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-4">
                <div style="min-width:260px">
                    <p class="hero-kicker mb-1">Dashboard Admin</p>
                    <h4 class="mb-1">Selamat datang, <span class="text-primary">{{ $namaDepan }}</span> 👋</h4>
                    <p class="text-muted mb-3">Perdana Inti Bersaudara — ringkasan aktivitas bisnis Anda.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="hero-stat">
                            <p>Total Invoice</p>
                            <h5>{{ number_format($totalInvoice, 0, ',', '.') }}</h5>
                        </div>
                        <div class="hero-stat">
                            <p>Pendapatan (Lunas)</p>
                            <h5>Rp {{ number_format($pendapatan, 0, ',', '.') }}</h5>
                        </div>
                        <div class="hero-stat">
                            <p>Pengunjung Hari Ini</p>
                            <h5>{{ number_format($pengunjungHariIni, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="hero-icon">
                    <i data-lucide="layout-dashboard"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Statistik utama --}}
<div class="row g-4 mb-4">
    @include('partials.admin.stat-card', [
        'label' => 'Invoice',
        'value' => number_format($totalInvoice, 0, ',', '.'),
        'sub'   => 'Lunas: ' . $lunas . ' · Belum lunas: ' . $belumLunas,
        'icon'  => 'receipt',
        'tone'  => 'primary',
        'link'  => route('invoices.index'),
    ])
    @include('partials.admin.stat-card', [
        'label' => 'Layanan',
        'value' => number_format($jmlLayanan, 0, ',', '.'),
        'sub'   => 'Total layanan tersedia',
        'icon'  => 'briefcase',
        'tone'  => 'sky',
        'link'  => route('services.index'),
    ])
    @include('partials.admin.stat-card', [
        'label' => 'Produk',
        'value' => number_format($jmlProduk, 0, ',', '.'),
        'sub'   => 'Total produk ditawarkan',
        'icon'  => 'package',
        'tone'  => 'teal',
        'link'  => route('products.index'),
    ])
    @include('partials.admin.stat-card', [
        'label' => 'Kegiatan',
        'value' => number_format($jmlKegiatan, 0, ',', '.'),
        'sub'   => 'Total kegiatan / portofolio',
        'icon'  => 'calendar-days',
        'tone'  => 'amber',
        'link'  => route('activities.index'),
    ])
</div>

{{-- Aktivitas --}}
<div class="row g-4 mb-4">
    @include('partials.admin.stat-card', [
        'label' => 'Pengunjung Hari Ini',
        'value' => number_format($pengunjungHariIni, 0, ',', '.'),
        'sub'   => 'Website utama — hari ini',
        'icon'  => 'eye',
        'tone'  => 'purple',
    ])
    @include('partials.admin.stat-card', [
        'label' => 'Pengunjung Bulan Ini',
        'value' => number_format($pengunjungBulanIni, 0, ',', '.'),
        'sub'   => 'Website utama — bulan ini',
        'icon'  => 'chart-line',
        'tone'  => 'rose',
    ])
</div>

{{-- Transaksi terbaru + customer terbaru --}}
<div class="row g-4">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transaksi Terbaru</h5>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Invoice</th>
                                <th>Customer</th>
                                <th class="d-none d-md-table-cell">Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoiceTerbaru as $trx)
                                @php $sts = $trx->status; @endphp
                                <tr>
                                    <td class="text-nowrap fw-semibold">{{ $trx->nomor_invoice }}</td>
                                    <td class="text-nowrap">{{ $trx->customer->nama_instansi ?? '-' }}</td>
                                    <td class="d-none d-md-table-cell">{{ date('d/m/Y', strtotime($trx->tanggal)) }}</td>
                                    <td class="text-nowrap">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                    <td class="text-nowrap">
                                        @if ($sts == 'dibayar')
                                            <span class="badge badge-soft-success">Lunas</span>
                                        @elseif ($sts == 'dikirim')
                                            <span class="badge badge-soft-info">Dikirim</span>
                                        @elseif ($sts == 'batal')
                                            <span class="badge badge-soft-danger">Batal</span>
                                        @else
                                            <span class="badge badge-soft-warning">Draft</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Customer Terbaru</h5>
            </div>
            <div class="card-body">
                @forelse ($customerBaru as $cust)
                    <div class="d-flex align-items-center gap-3 py-2">
                        <span class="avatar avatar-sm">{{ strtoupper(mb_substr(trim($cust->nama_instansi), 0, 1)) }}</span>
                        <div class="min-width-0" style="min-width:0">
                            <p class="mb-0 fw-semibold text-truncate">{{ $cust->nama_instansi }}</p>
                            <small class="text-muted">{{ $cust->telepon ?? $cust->email }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada customer.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection