@extends('layouts.admin')

@section('title', 'Edit Kwitansi')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Kwitansi {{ $kwitansi->nomor_kwitansi }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('kwitansis.update', $kwitansi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $kwitansi->tanggal->format('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Sudah Diterima Dari (Customer) <span class="text-danger">*</span></label>
                    <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" onchange="if(this.value==='__add__'){ window.open('{{ route('customers.create') }}','_blank'); this.value=''; }" required>
                        <option value="">-- Pilih Customer --</option>
                        <option value="__add__">+ Tambah Customer Baru</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $kwitansi->customer_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_instansi }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Ambil dari Invoice (Opsional)</label>
                <select id="invoice_select" class="form-select">
                    <option value="">-- Pilih Invoice (auto-isi customer & jumlah) --</option>
                    @foreach($invoices as $inv)
                        <option value="{{ $inv->id }}" data-customer-id="{{ $inv->customer_id }}" data-jumlah="{{ $inv->total }}">
                            {{ $inv->nomor_invoice }} — {{ $inv->customer->nama_instansi ?? '-' }} (Rp {{ number_format($inv->total, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Memilih invoice akan otomatis mengisi Customer dan Jumlah.</small>
            </div>

            <hr>
            <h6 class="mb-3">Detail Pembayaran</h6>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Uang Sejumlah (Jumlah) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" placeholder="Contoh: 5.000.000" value="{{ old('jumlah', number_format($kwitansi->jumlah, 0, ',', '.')) }}" required>
                    </div>
                    @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Terbilang: <em id="terbilang-preview">-</em></small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Untuk Pembayaran</label>
                    <input type="text" name="untuk_pembayaran" class="form-control @error('untuk_pembayaran') is-invalid @enderror" placeholder="Contoh: Pembayaran Invoice No. INV/PIB/08/2026/001" value="{{ old('untuk_pembayaran', $kwitansi->untuk_pembayaran) }}">
                    @error('untuk_pembayaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="4" placeholder="Contoh:&#10;1. Mohon Pembayaran ditransfer ke rekening Bank berikut :&#10;BCA, No. Rekening 619 801 2191, An. CV Perdana Inti Bersaudara&#10;2. Pembayaran baru dianggap sah setelah cek/giro telah dicairkan">{{ trim(old('catatan', $kwitansi->catatan)) }}</textarea>
                @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Jika diisi, catatan ini akan tampil pada PDF kwitansi.</small>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('kwitansis.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var invoiceData = {
        @foreach($invoices as $inv)
        {{ $inv->id }}: { customer_id: '{{ $inv->customer_id }}', jumlah: {{ $inv->total }} },
        @endforeach
    };

    function terbilang(angka) {
        angka = Math.abs(Math.floor(Number(angka) || 0));
        var bilangan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        var hasil = '';
        if (angka < 12) {
            hasil = bilangan[angka];
        } else if (angka < 20) {
            hasil = bilangan[angka - 10] + ' belas';
        } else if (angka < 100) {
            hasil = bilangan[Math.floor(angka / 10)] + ' puluh ' + bilangan[angka % 10];
        } else if (angka < 200) {
            hasil = 'seratus' + (angka - 100 > 0 ? ' ' + terbilang(angka - 100) : '');
        } else if (angka < 1000) {
            hasil = bilangan[Math.floor(angka / 100)] + ' ratus' + (angka % 100 > 0 ? ' ' + terbilang(angka % 100) : '');
        } else if (angka < 2000) {
            hasil = 'seribu' + (angka - 1000 > 0 ? ' ' + terbilang(angka - 1000) : '');
        } else if (angka < 1000000) {
            hasil = terbilang(Math.floor(angka / 1000)) + ' ribu' + (angka % 1000 > 0 ? ' ' + terbilang(angka % 1000) : '');
        } else if (angka < 1000000000) {
            hasil = terbilang(Math.floor(angka / 1000000)) + ' juta' + (angka % 1000000 > 0 ? ' ' + terbilang(angka % 1000000) : '');
        } else if (angka < 1000000000000) {
            hasil = terbilang(Math.floor(angka / 1000000000)) + ' miliar' + (angka % 1000000000 > 0 ? ' ' + terbilang(angka % 1000000000) : '');
        } else {
            hasil = '~';
        }
        return hasil.trim() || 'nol';
    }

    function parseJumlah(str) {
        return parseFloat(String(str).replace(/\./g, '').replace(/,/g, '.')) || 0;
    }

    function formatRibuan(num) {
        var parts = String(num).split(',');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return parts.join(',');
    }

    function updateTerbilang() {
        var val = document.getElementById('jumlah').value;
        var num = parseJumlah(val);
        var preview = document.getElementById('terbilang-preview');
        if (num > 0) {
            var text = terbilang(Math.floor(num));
            preview.textContent = text.charAt(0).toUpperCase() + text.slice(1) + ' rupiah';
        } else {
            preview.textContent = '-';
        }
    }

    document.getElementById('jumlah').addEventListener('input', function () {
        var raw = this.value.replace(/[^\d,]/g, '');
        if (raw !== '') {
            this.value = formatRibuan(raw);
        }
        updateTerbilang();
    });

    document.getElementById('invoice_select').addEventListener('change', function () {
        var id = this.value;
        if (!id || !invoiceData[id]) return;
        document.getElementById('customer_id').value = invoiceData[id].customer_id;
        var jumlahField = document.getElementById('jumlah');
        jumlahField.value = formatRibuan(String(invoiceData[id].jumlah));
        updateTerbilang();
    });

    updateTerbilang();
</script>
@endpush
