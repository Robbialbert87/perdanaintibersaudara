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
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $service->title ?? '') }}" required placeholder="Contoh: Cardiologi">
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
                                if (empty($features)) $features = [''];
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
                        <label for="images" class="form-label">Unggah Gambar (Maksimal 15)</label>
                        <input class="form-control @error('images.*') is-invalid @enderror" type="file" id="images" name="images[]" multiple accept="image/*">
                        <div class="form-text">Format: JPG, PNG, WEBP. Maks 5MB per gambar.</div>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(isset($service))
                        @php
                            $allImages = $service->images ?? [];
                            $legacyImage = $service->image;
                            if ($legacyImage && !in_array($legacyImage, $allImages)) {
                                array_unshift($allImages, $legacyImage);
                            }
                            $activeImages = $service->active_images ?? [];
                            if ($legacyImage && empty($activeImages) && empty($service->images)) {
                                $activeImages = [$legacyImage];
                            }
                            $sortedImages = array_merge(
                                array_intersect($activeImages, $allImages),
                                array_diff($allImages, $activeImages)
                            );
                        @endphp
                        @if(!empty($sortedImages))
                            <div class="mb-3">
                                <label class="form-label">Gambar yang Tersimpan:</label>
                                <div class="row g-2" id="sortable-images">
                                    @foreach($sortedImages as $img)
                                    @php $isActive = in_array($img, $activeImages); @endphp
                                    <div class="col-auto">
                                        <div class="card" style="width: 120px;">
                                            <div style="height: 90px; overflow: hidden; background: #f8f9fa;">
                                                <img src="{{ Storage::url($img) }}" alt="Service Image" style="width: 100%; height: 100%; object-fit: cover;">
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
                    @endif

                    <div class="mb-3">
                        <label for="videos" class="form-label">Unggah Video (Maksimal 3)</label>
                        <input class="form-control @error('videos.*') is-invalid @enderror" type="file" id="videos" name="videos[]" multiple accept="video/*">
                        <div class="form-text">Format: MP4, AVI, MOV, MKV, WEBM, FLV, WMV. Maks 100MB per video.</div>
                        @error('videos.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(isset($service) && !empty($service->videos))
                        @php
                            $activeVideos = $service->active_videos ?? [];
                            $sortedVideos = array_merge(
                                array_intersect($activeVideos, $service->videos ?? []),
                                array_diff($service->videos ?? [], $activeVideos)
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
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('services.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>
<form id="delete-media-form" action="{{ isset($service) ? route('services.deleteMedia', $service->id) : '#' }}" method="POST" class="d-none">
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
