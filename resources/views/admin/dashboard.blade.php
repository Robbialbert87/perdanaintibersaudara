@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Total Kegiatan</h6>
                        <h2 class="mb-0">{{ \App\Models\Activity::count() }}</h2>
                    </div>
                    <i class="bi bi-card-image fs-1"></i>
                </div>
            </div>
            <a href="{{ route('activities.index') }}" class="card-footer text-white clearfix small z-1 d-flex align-items-center justify-content-between">
                <span>Lihat Detail</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Total Produk</h6>
                        <h2 class="mb-0">{{ \App\Models\Product::count() }}</h2>
                    </div>
                    <i class="bi bi-box-seam fs-1"></i>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="card-footer text-white clearfix small z-1 d-flex align-items-center justify-content-between">
                <span>Lihat Detail</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection
