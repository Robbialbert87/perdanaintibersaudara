@extends('layouts.admin')

@section('title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold">{{ isset($service) ? 'Edit Layanan' : 'Tambah Layanan Baru' }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ isset($service) ? route('services.update', $service->id) : route('services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($service))
                @method('PUT')
            @endif

            <div class="row gy-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Layanan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $service->title ?? '') }}" required placeholder="Contoh: Cardiology">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required placeholder="Tuliskan deskripsi lengkap mengenai layanan ini...">{{ old('description', $service->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fitur / Keunggulan</label>
                        <p class="text-muted small mb-2">Tambahkan daftar poin-poin fitur layanan (misal: ECG Testing, Heart Surgery).</p>
                        
                        <div id="features-container">
                            @php
                                $features = old('features', isset($service) ? $service->features : []);
                                if (empty($features)) $features = ['']; // At least one empty input
                            @endphp

                            @foreach($features as $index => $feature)
                                <div class="input-group mb-2 feature-item">
                                    <span class="input-group-text"><i class="bi bi-check2"></i></span>
                                    <input type="text" name="features[]" class="form-control" value="{{ $feature }}" placeholder="Nama fitur...">
                                    <button class="btn btn-outline-danger remove-feature" type="button" {{ count($features) == 1 ? 'disabled' : '' }}><i class="bi bi-x"></i></button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-feature">
                            <i class="bi bi-plus"></i> Tambah Fitur
                        </button>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Layanan</label>
                        @if(isset($service) && $service->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($service->image) }}" alt="{{ $service->title }}" class="img-fluid img-thumbnail rounded">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" {{ !isset($service) ? 'required' : '' }}>
                        <div class="form-text">Format: JPG, PNG, WEBP. Maks 5MB. @if(!isset($service)) Wajib diisi. @endif</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('services.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('features-container');
    const addButton = document.getElementById('add-feature');

    // Add new feature field
    addButton.addEventListener('click', function() {
        const item = document.createElement('div');
        item.className = 'input-group mb-2 feature-item';
        item.innerHTML = `
            <span class="input-group-text"><i class="bi bi-check2"></i></span>
            <input type="text" name="features[]" class="form-control" placeholder="Nama fitur...">
            <button class="btn btn-outline-danger remove-feature" type="button"><i class="bi bi-x"></i></button>
        `;
        container.appendChild(item);
        updateRemoveButtons();
    });

    // Remove feature field
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            const button = e.target.closest('.remove-feature');
            if (!button.disabled) {
                button.closest('.feature-item').remove();
                updateRemoveButtons();
            }
        }
    });

    function updateRemoveButtons() {
        const items = container.querySelectorAll('.feature-item');
        const buttons = container.querySelectorAll('.remove-feature');
        if (items.length <= 1) {
            buttons.forEach(btn => btn.disabled = true);
        } else {
            buttons.forEach(btn => btn.disabled = false);
        }
    }
});
</script>
@endsection
