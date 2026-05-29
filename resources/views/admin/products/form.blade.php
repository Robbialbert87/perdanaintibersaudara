@extends('layouts.admin')

@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $product->category ?? '') }}" required>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="images" class="form-label">Unggah Foto (Maksimal 5 Foto)</label>
                <input class="form-control @error('images.*') is-invalid @enderror" type="file" id="images" name="images[]" multiple accept="image/*">
                <div class="form-text">Format yang didukung: JPG, PNG, JPEG. Ukuran maksimal 5MB per foto.</div>
                @error('images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if(isset($product) && !empty($product->images))
                <div class="mb-4">
                    <label class="form-label">Foto Saat Ini (Centang untuk menghapus foto):</label>
                    <div class="row g-2">
                        @foreach($product->images as $img)
                        <div class="col-auto">
                            <div class="card" style="width: 120px;">
                                <img src="{{ Storage::url($img) }}" class="card-img-top" alt="Product Image" style="height: 100px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="checkbox" name="kept_images[]" value="{{ $img }}" id="img_{{ $loop->index }}" checked>
                                        <label class="form-check-label small" for="img_{{ $loop->index }}">
                                            Pertahankan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <hr>
            <div class="d-flex justify-content-end">
                <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
