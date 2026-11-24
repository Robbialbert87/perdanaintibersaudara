@extends('layouts.admin')

@section('title', 'Tambah Surat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:var(--foreground)">Tambah Surat</h4>
        <small class="text-muted">Buat surat dengan kop surat dan QR verifikasi</small>
    </div>
    <a href="{{ route('surats.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('surats.store') }}" enctype="multipart/form-data">
    @csrf

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
                <div class="card-header"><h6 class="mb-0">Informasi Surat</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                        <select name="jenis" id="jenisSurat" class="form-select" required>
                            @foreach($jenisOptions as $jenis)
                                <option value="{{ $jenis }}" {{ old('jenis', 'Permintaan') === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_surat" id="nomorSurat" class="form-control" value="{{ old('nomor_surat', $nomorSurat) }}" required>
                        <small class="form-text">Nomor otomatis dapat diubah. Kode menyesuaikan jenis surat.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['draft' => 'Draft', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'batal' => 'Batal'] as $value => $label)
                                <option value="{{ $value }}" {{ old('status', 'draft') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Penandatangan</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama_penandatangan" class="form-control" value="{{ old('nama_penandatangan', 'Erwin Darmawan') }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan_penandatangan" class="form-control" value="{{ old('jabatan_penandatangan', 'Direktur') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Tujuan Surat</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Customer <small class="text-muted">(opsional)</small></label>
                        <select name="customer_id" id="customer_id" class="form-select">
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    data-nama="{{ $customer->nama_instansi }}"
                                    data-alamat="{{ $customer->alamat ?? '' }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama_instansi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Tujuan <span class="text-danger">*</span></label>
                        <input type="text" name="tujuan_nama" id="tujuan_nama" class="form-control" value="{{ old('tujuan_nama') }}" placeholder="Nama instansi/pihak tujuan">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Alamat Tujuan</label>
                        <textarea name="tujuan_alamat" id="tujuan_alamat" class="form-control" rows="3" placeholder="Alamat tujuan (opsional)">{{ old('tujuan_alamat') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h6 class="mb-0">Isi Surat</h6></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Perihal <span class="text-danger">*</span></label>
                <input type="text" name="perihal" class="form-control" value="{{ old('perihal') }}" placeholder="Contoh: Permintaan penggantian sparepart">
            </div>
            <div class="mb-0">
                <label class="form-label">Isi Surat <span class="text-danger">*</span></label>
                <textarea name="isi" class="form-control" rows="10" required placeholder="Tulis isi surat. Baris baru akan tampil sebagai paragraf di PDF.">{{ old('isi') }}</textarea>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h6 class="mb-0">Lampiran (Opsional)</h6></div>
        <div class="card-body">
            <input type="file" name="lampiran[]" id="lampiranInput" class="form-control" accept="image/*" multiple>
            <small class="text-muted">Maksimal 10 gambar, masing-masing maks 5 MB (jpg, jpeg, png, webp). 2 gambar per halaman di PDF.</small>
            <div id="lampiranPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('surats.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Simpan
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
const kodeJenis = {
    'Permintaan': 'SPM',
    'Pengantar': 'SPG',
    'Keterangan': 'SK',
    'Pemberitahuan': 'SPB',
    'Permohonan': 'SPMOH',
    'Lainnya': 'SURAT'
};

document.getElementById('customer_id').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    if (option.value) {
        document.getElementById('tujuan_nama').value = option.dataset.nama || '';
        document.getElementById('tujuan_alamat').value = option.dataset.alamat || '';
    }
});

document.getElementById('jenisSurat').addEventListener('change', function() {
    const nomor = document.getElementById('nomorSurat');
    const kode = kodeJenis[this.value] || 'SURAT';
    nomor.value = nomor.value.replace(/^(\d{3}\/)[A-Z]+(\/)/, '$1' + kode + '$2');
});

const lampiranInput = document.getElementById('lampiranInput');
if (lampiranInput) {
    lampiranInput.addEventListener('change', function() {
        const preview = document.getElementById('lampiranPreview');
        preview.innerHTML = '';
        Array.from(this.files).slice(0, 10).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width:80px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #ddd;';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
}
</script>
@endpush
