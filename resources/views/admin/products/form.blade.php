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

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $product->category ?? '') }}" required>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="spesifikasi" class="form-label">Spesifikasi Detail</label>
                        <textarea class="form-control @error('spesifikasi') is-invalid @enderror" id="spesifikasi" name="spesifikasi" rows="4">{{ old('spesifikasi', $product->spesifikasi ?? '') }}</textarea>
                        @error('spesifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" value="{{ old('satuan', $product->satuan ?? '') }}" placeholder="Contoh: Unit, Pcs, Lot">
                        @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="harga_default" class="form-label">Harga Default</label>
                        <input type="number" class="form-control @error('harga_default') is-invalid @enderror" id="harga_default" name="harga_default" value="{{ old('harga_default', $product->harga_default ?? '') }}" min="0" step="0.01">
                        @error('harga_default')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="images" class="form-label">Unggah Foto (Maksimal 5 Foto)</label>
                        <input class="form-control @error('images.*') is-invalid @enderror" type="file" id="images" name="images[]" multiple accept="image/*">
                        <div class="form-text">Format: JPG, PNG, JPEG. Maks 5MB per foto.</div>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(isset($product) && !empty($product->images))
                        @php
                            $activeImages = $product->active_images ?? [];
                            $sortedImages = array_merge(
                                array_intersect($activeImages, $product->images ?? []),
                                array_diff($product->images ?? [], $activeImages)
                            );
                        @endphp
                        <div class="mb-3">
                            <label class="form-label">Foto yang Tersimpan:</label>
                            <div class="row g-2" id="sortable-images">
                                @foreach($sortedImages as $img)
                                @php $isActive = in_array($img, $activeImages); @endphp
                                <div class="col-auto">
                                    <div class="card" style="width: 120px;">
                                        <div style="height: 90px; overflow: hidden; background: #f8f9fa;">
                                            <img src="{{ Storage::url($img) }}" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div class="card-body p-1">
                                            <div class="d-flex align-items-center justify-content-between gap-1">
                                                <div class="form-check mb-0" style="min-height: auto;">
                                                    <input class="form-check-input toggle-media" type="checkbox" value="{{ $img }}" id="img_{{ $loop->index }}" data-type="image" @if($isActive) checked @endif>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-media" style="font-size: 0.65rem; padding: 1px 4px;" title="Hapus dari storage" data-file="{{ $img }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="videos" class="form-label">Unggah Video (Maksimal 3 Video)</label>
                        <input class="form-control @error('videos.*') is-invalid @enderror" type="file" id="videos" name="videos[]" multiple accept="video/*">
                        <div class="form-text">Format: MP4, AVI, MOV, MKV, WEBM, FLV, WMV. Maks 100MB per video.</div>
                        @error('videos.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(isset($product) && !empty($product->videos))
                        @php
                            $activeVideos = $product->active_videos ?? [];
                            $sortedVideos = array_merge(
                                array_intersect($activeVideos, $product->videos ?? []),
                                array_diff($product->videos ?? [], $activeVideos)
                            );
                        @endphp
                        <div class="mb-3">
                            <label class="form-label">Video yang Tersimpan:</label>
                            <div class="row g-2" id="sortable-videos">
                                @foreach($sortedVideos as $vid)
                                @php $isActive = in_array($vid, $activeVideos); @endphp
                                <div class="col-auto">
                                    <div class="card" style="width: 180px;">
                                        <div style="height: 90px; overflow: hidden; background: #000;">
                                            <video src="{{ Storage::url($vid) }}" style="width: 100%; height: 100%; object-fit: cover;" muted></video>
                                        </div>
                                        <div class="card-body p-1">
                                            <div class="d-flex align-items-center justify-content-between gap-1">
                                                <div class="form-check mb-0" style="min-height: auto;">
                                                    <input class="form-check-input toggle-media" type="checkbox" value="{{ $vid }}" id="vid_{{ $loop->index }}" data-type="video" @if($isActive) checked @endif>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-media" style="font-size: 0.65rem; padding: 1px 4px;" title="Hapus dari storage" data-file="{{ $vid }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <hr>
            <div id="active-media-inputs"></div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<form id="delete-media-form" action="{{ isset($product) ? route('products.deleteMedia', $product->id) : '#' }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
    <input type="hidden" name="file" id="delete-media-file">
</form>

@push('styles')
<style>
    #sortable-images > .col-auto,
    #sortable-videos > .col-auto {
        cursor: grab;
    }
    #sortable-images > .col-auto:active,
    #sortable-videos > .col-auto:active {
        cursor: grabbing;
    }
    @media (max-width: 576px) {
        #sortable-images .card,
        #sortable-videos .card {
            width: 100% !important;
        }
        #sortable-images .card > div:first-child,
        #sortable-videos .card > div:first-child {
            height: 120px !important;
        }
    }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function syncActiveInputs() {
        document.getElementById('active-media-inputs').innerHTML = '';
        document.querySelectorAll('.toggle-media:checked').forEach(function(cb) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = cb.dataset.type === 'video' ? 'active_videos[]' : 'active_images[]';
            input.value = cb.value;
            document.getElementById('active-media-inputs').appendChild(input);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        syncActiveInputs();
        document.querySelectorAll('.toggle-media').forEach(function(cb) {
            cb.addEventListener('change', syncActiveInputs);
        });

        document.querySelectorAll('.btn-delete-media').forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (confirm('Hapus media ini secara permanen?')) {
                    document.getElementById('delete-media-file').value = this.dataset.file;
                    document.getElementById('delete-media-form').submit();
                }
            });
        });

        if (document.getElementById('sortable-images')) {
            new Sortable(document.getElementById('sortable-images'), {
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: syncActiveInputs
            });
        }
        if (document.getElementById('sortable-videos')) {
            new Sortable(document.getElementById('sortable-videos'), {
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: syncActiveInputs
            });
        }
    });
</script>
@endpush
@endsection
