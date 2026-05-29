@extends('layouts.admin')

@section('title', isset($activity) ? 'Edit Kegiatan' : 'Tambah Kegiatan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold">{{ isset($activity) ? 'Edit Kegiatan' : 'Tambah Kegiatan Baru' }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ isset($activity) ? route('activities.update', $activity->id) : route('activities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($activity))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="title" class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $activity->title ?? '') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', isset($activity) ? \Carbon\Carbon::parse($activity->date)->format('Y-m-d') : '') }}" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Deskripsi / Konten <span class="text-danger">*</span></label>
                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" required>{{ old('content', $activity->content ?? '') }}</textarea>
                @error('content')
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

            @if(isset($activity) && !empty($activity->images))
                <div class="mb-4">
                    <label class="form-label">Foto Saat Ini (Centang untuk menghapus foto):</label>
                    <div class="row g-2">
                        @foreach($activity->images as $img)
                        <div class="col-auto">
                            <div class="card" style="width: 120px;">
                                <img src="{{ Storage::url($img) }}" class="card-img-top" alt="Activity Image" style="height: 100px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <div class="form-check d-inline-block">
                                        <!-- If unchecked, we keep the image. So checkbox value should be 'kept_images[]' if we want to keep it?
                                             Wait, the controller expects 'kept_images' for images to KEEP. 
                                             So checkbox should be checked by default, and unchecking it removes it. -->
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
                <a href="{{ route('activities.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
