@extends('layouts.admin')

@section('title', 'Manajemen Layanan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="m-0 font-weight-bold">Daftar Layanan</h6>
        <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Layanan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="d-none d-sm-table-cell">No</th>
                        <th width="120" class="d-none d-sm-table-cell">Gambar</th>
                        <th>Layanan</th>
                        <th class="d-none d-sm-table-cell">Deskripsi</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $index => $service)
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $index + 1 }}</td>
<td class="d-none d-sm-table-cell">
    @php $adminSvcImg = $service->images[0] ?? $service->image; @endphp
    @if($adminSvcImg)
        <img src="{{ Storage::url($adminSvcImg) }}" alt="{{ $service->title }}" class="img-thumbnail gallery-img" style="max-height: 80px; width: auto; cursor: pointer;" data-all-images='{{ json_encode($service->images ?? ($service->image ? [$service->image] : [])) }}' data-name="{{ $service->title }}">
    @else
        <span class="text-muted small">No Image</span>
    @endif
</td>
                        <td>
                            <strong>{{ $service->title }}</strong>
                            <div class="small text-muted mt-1">
                                @if(!empty($service->images)) {{ count($service->images) }} gambar @endif
                                @if(!empty($service->images) && !empty($service->features)) | @endif
                                @if(!empty($service->features)) {{ count($service->features) }} fitur @endif
                                @if(!empty($service->videos)) | {{ count($service->videos) }} video @endif
                            </div>
                        </td>
                        <td class="d-none d-sm-table-cell">{{ Str::limit($service->description, 80) }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-info text-white" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data layanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Lightbox -->
<div class="lightbox-overlay" id="lightboxOverlay">
    <span class="lightbox-close" id="lightboxClose">&times;</span>
    <img id="lightboxImg" src="" alt="">
    <div class="lightbox-nav-bottom">
        <span class="lightbox-nav lightbox-prev" id="lightboxPrev">&#10094;</span>
        <span class="lightbox-counter" id="lightboxCounter"></span>
        <span class="lightbox-nav lightbox-next" id="lightboxNext">&#10095;</span>
    </div>
</div>
@endsection

@push('styles')
<style>
.lightbox-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 9999;
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.lightbox-overlay.active {
    display: flex;
}
.lightbox-overlay img {
    max-width: 90%;
    max-height: 80%;
    border-radius: 8px;
    box-shadow: 0 4px 30px rgba(0,0,0,0.5);
    cursor: default;
}
.lightbox-nav-bottom {
    margin-top: 20px;
    display: flex;
    gap: 20px;
    align-items: center;
}
.lightbox-nav {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-size: 22px;
    cursor: pointer;
    user-select: none;
    transition: background .2s;
}
.lightbox-nav:hover { background: rgba(255,255,255,0.3); }
.lightbox-counter {
    color: #fff;
    font-size: 14px;
    font-family: sans-serif;
    opacity: 0.8;
}
.lightbox-close {
    position: fixed;
    top: 15px;
    right: 25px;
    color: #fff;
    font-size: 35px;
    cursor: pointer;
    z-index: 10000;
    opacity: 0.7;
    transition: opacity .2s;
}
.lightbox-close:hover { opacity: 1; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('lightboxOverlay');
    const lightboxImg = document.getElementById('lightboxImg');
    const prevBtn = document.getElementById('lightboxPrev');
    const nextBtn = document.getElementById('lightboxNext');
    let currentImages = [];
    let currentIndex = 0;

    document.querySelectorAll('.gallery-img').forEach(img => {
        img.addEventListener('click', function(e) {
            e.stopPropagation();
            try {
                currentImages = JSON.parse(this.dataset.allImages || '[]');
            } catch(e) { currentImages = []; }
            if (!currentImages.length) return;
            const src = this.src;
            currentIndex = currentImages.findIndex(p => StorageUrl(p) === src);
            if (currentIndex === -1) currentIndex = 0;
            showImage(currentIndex);
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function StorageUrl(path) {
        return '{{ Storage::url('') }}' + path;
    }

    function showImage(index) {
        if (!currentImages.length) return;
        lightboxImg.src = StorageUrl(currentImages[index]);
        const navBottom = document.querySelector('.lightbox-nav-bottom');
        if (currentImages.length > 1) {
            navBottom.style.display = 'flex';
            document.getElementById('lightboxCounter').textContent = (index + 1) + ' / ' + currentImages.length;
        } else {
            navBottom.style.display = 'none';
        }
    }

    prevBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
        showImage(currentIndex);
    });

    nextBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        currentIndex = (currentIndex + 1) % currentImages.length;
        showImage(currentIndex);
    });

    function closeLightbox() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    overlay.addEventListener('click', closeLightbox);
    document.getElementById('lightboxClose').addEventListener('click', closeLightbox);
    lightboxImg.addEventListener('click', function(e) { e.stopPropagation(); });

    document.addEventListener('keydown', function(e) {
        if (!overlay.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') prevBtn.click();
        if (e.key === 'ArrowRight') nextBtn.click();
    });
});
</script>
@endpush
