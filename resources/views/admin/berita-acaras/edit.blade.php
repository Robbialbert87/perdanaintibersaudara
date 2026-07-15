@extends('layouts.admin')

@section('title', 'Edit Berita Acara')

@push('styles')
<style>
    .items-table th, .items-table td { vertical-align: middle; }
    .items-table .form-control, .items-table .form-select { font-size: 12px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:#f4f6f8">Edit Berita Acara</h4>
        <small class="text-muted">{{ $beritaAcara->nomor_surat }}</small>
    </div>
    <a href="{{ route('berita-acaras.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('berita-acaras.update', $beritaAcara->id) }}" id="beritaAcaraForm">
    @csrf
    @method('PUT')

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Informasi Dokumen</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $beritaAcara->tanggal->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="draft" {{ old('status', $beritaAcara->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="dikirim" {{ old('status', $beritaAcara->status) === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ old('status', $beritaAcara->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ old('status', $beritaAcara->status) === 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                        <textarea name="kegiatan" class="form-control" rows="3" required>{{ old('kegiatan', $beritaAcara->kegiatan) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $beritaAcara->lokasi) }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Pihak Penyerah</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="pihak_penyerah_nama" class="form-control" value="{{ old('pihak_penyerah_nama', $beritaAcara->pihak_penyerah_nama) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="pihak_penyerah_alamat" class="form-control" rows="2">{{ old('pihak_penyerah_alamat', $beritaAcara->pihak_penyerah_alamat) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Pihak Penerima</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="pihak_penerima_nama" class="form-control" value="{{ old('pihak_penerima_nama', $beritaAcara->pihak_penerima_nama) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="pihak_penerima_alamat" class="form-control" rows="2">{{ old('pihak_penerima_alamat', $beritaAcara->pihak_penerima_alamat) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Daftar Produk</h6>
            <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                <i class="bi bi-plus"></i> Tambah Item
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table items-table mb-0" id="itemsTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama Produk</th>
                            <th width="15%">Quantity</th>
                            <th width="20%">Kondisi</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        @foreach($beritaAcara->items as $idx => $item)
                        <tr data-index="{{ $idx }}">
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td><input type="text" name="items[{{ $idx }}][nama_produk]" class="form-control" value="{{ old('items.'.$idx.'.nama_produk', $item->nama_produk) }}" required></td>
                            <td><input type="number" name="items[{{ $idx }}][quantity]" class="form-control" value="{{ old('items.'.$idx.'.quantity', $item->quantity) }}" min="1" required></td>
                            <td>
                                <select name="items[{{ $idx }}][berfungsi]" class="form-select" required>
                                    <option value="1" {{ old('items.'.$idx.'.berfungsi', $item->berfungsi ? '1' : '0') === '1' ? 'selected' : '' }}>Berfungsi</option>
                                    <option value="0" {{ old('items.'.$idx.'.berfungsi', $item->berfungsi ? '1' : '0') === '0' ? 'selected' : '' }}>Tidak Berfungsi</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h6 class="mb-0">Penutup</h6></div>
        <div class="card-body">
            <div class="mb-0">
                <label class="form-label">Teks Penutup (opsional)</label>
                <textarea name="closing_text" class="form-control" rows="3" placeholder="Kosongkan untuk menggunakan teks default">{{ old('closing_text', $beritaAcara->closing_text) }}</textarea>
                <small class="form-text">Default: "Demikian Berita Acara Serah Terima dan Uji Fungsi ini kami buat agar dapat dipergunakan sebagaimana mestinya, atas kerjasama dan kepercayaan nya kami ucapkan terima kasih."</small>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('berita-acaras.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Perbarui
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
let itemIndex = {{ $beritaAcara->items->count() }};

function addItem() {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.setAttribute('data-index', itemIndex);
    row.innerHTML = `
        <td class="text-center">${tbody.children.length + 1}</td>
        <td><input type="text" name="items[${itemIndex}][nama_produk]" class="form-control" required placeholder="Nama produk"></td>
        <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control" value="1" min="1" required></td>
        <td>
            <select name="items[${itemIndex}][berfungsi]" class="form-select" required>
                <option value="1">Berfungsi</option>
                <option value="0">Tidak Berfungsi</option>
            </select>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    itemIndex++;
    reindexRows();
}

function removeItem(btn) {
    const tbody = document.getElementById('itemsBody');
    if (tbody.children.length > 1) {
        btn.closest('tr').remove();
        reindexRows();
    }
}

function reindexRows() {
    const tbody = document.getElementById('itemsBody');
    Array.from(tbody.children).forEach((row, i) => {
        row.querySelector('td:first-child').textContent = i + 1;
        row.querySelectorAll('input, select, textarea').forEach(el => {
            const name = el.getAttribute('name');
            if (name) {
                el.setAttribute('name', name.replace(/items\[\d+\]/, `items[${i}]`));
            }
        });
    });
}
</script>
@endpush
