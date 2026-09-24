@extends('layouts.admin')

@section('title', 'Edit Invoice')

@push('styles')
<style>
.modal-backdrop ~ .modal-backdrop { display: none !important; }
#previewModal .modal-footer { position: relative; z-index: 1060; }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0 mb-4" id="formSection">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Invoice: {{ $invoice->nomor_invoice }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $invoice->tanggal) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pilih Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required onchange="if(this.value==='__add__'){ window.open('{{ route('customers.create') }}','_blank'); this.value=''; }">
                        <option value="">-- Pilih Customer --</option>
                        <option value="__add__">+ Tambah Customer Baru</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ (old('customer_id') ?? $invoice->customer_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_instansi }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4"></div>
            </div>

            <hr>
            <h6 class="mb-3">Item Invoice</h6>

            <datalist id="itemSuggestions">
                @foreach($products as $p)
                    <option value="{{ $p->name }}">Produk: {{ $p->name }}</option>
                @endforeach
                @foreach($services as $s)
                    <option value="{{ $s->title }}">Layanan: {{ $s->title }}</option>
                @endforeach
            </datalist>

            <div id="itemsBody" class="ie-items">
                        @foreach($invoice->items as $index => $item)
                        <div class="item-row ie-card">
                            <div class="ie-head">
                                <div class="ie-head-left">
                                    <span class="ie-no">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="ie-group">Grup
                                        <input type="number" name="items[{{ $index }}][group_no]" class="ie-group-input group-no-input" value="{{ $item->group_no ?? 1 }}" min="1">
                                    </span>
                                </div>
                                <div class="ie-head-right">
                                    <button type="button" class="ie-remove remove-row" title="Hapus item"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="ie-label">Produk/Jasa (Opsional)</label>
                                    <input type="text" name="items[{{ $index }}][nama_item]" class="form-control nama-item-input" list="itemSuggestions" value="{{ $item->nama_item }}" placeholder="Nama Barang/Pekerjaan">
                                </div>
                                <div class="col-md-7">
                                    <label class="ie-label">Deskripsi <span class="text-danger">*</span></label>
                                    <textarea name="items[{{ $index }}][deskripsi]" class="form-control deskripsi-input" rows="3" required>{{ $item->deskripsi }}</textarea>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <label class="ie-label">Tgl Kegiatan</label>
                                    <input type="date" name="items[{{ $index }}][tanggal_kegiatan]" class="form-control tanggal-input" value="{{ $item->tanggal_kegiatan }}">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="ie-label">Volume</label>
                                    <input type="number" name="items[{{ $index }}][volume]" class="form-control volume-input" value="{{ $item->volume }}" required placeholder="0" step="any" min="0">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="ie-label">Satuan</label>
                                    <select name="items[{{ $index }}][satuan]" class="form-select satuan-input">
                                        <option value="">--</option>
                                        <option value="Unit" {{ ($item->satuan ?? 'Unit') == 'Unit' ? 'selected' : '' }}>Unit</option>
                                        <option value="Orang" {{ ($item->satuan ?? '') == 'Orang' ? 'selected' : '' }}>Orang</option>
                                        <option value="Paket" {{ ($item->satuan ?? '') == 'Paket' ? 'selected' : '' }}>Paket</option>
                                        <option value="Pcs" {{ ($item->satuan ?? '') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                        <option value="Cm" {{ ($item->satuan ?? '') == 'Cm' ? 'selected' : '' }}>Cm</option>
                                        <option value="mm" {{ ($item->satuan ?? '') == 'mm' ? 'selected' : '' }}>mm</option>
                                        <option value="Meter" {{ ($item->satuan ?? '') == 'Meter' ? 'selected' : '' }}>Meter</option>
                                        <option value="Set" {{ ($item->satuan ?? '') == 'Set' ? 'selected' : '' }}>Set</option>
                                        <option value="Box" {{ ($item->satuan ?? '') == 'Box' ? 'selected' : '' }}>Box</option>
                                        <option value="Rim" {{ ($item->satuan ?? '') == 'Rim' ? 'selected' : '' }}>Rim</option>
                                        <option value="Lembar" {{ ($item->satuan ?? '') == 'Lembar' ? 'selected' : '' }}>Lembar</option>
                                        <option value="Buah" {{ ($item->satuan ?? '') == 'Buah' ? 'selected' : '' }}>Buah</option>
                                        <option value="Bulan" {{ ($item->satuan ?? '') == 'Bulan' ? 'selected' : '' }}>Bulan</option>
                                        <option value="Tahun" {{ ($item->satuan ?? '') == 'Tahun' ? 'selected' : '' }}>Tahun</option>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="ie-label">Harga Satuan</label>
                                    <input type="text" name="items[{{ $index }}][harga_satuan]" class="form-control harga-input currency-format" value="{{ floatval($item->harga_satuan) }}" required placeholder="0">
                                </div>
                            </div>
                            <div class="ie-total">
                                <span class="ie-total-label">Jumlah Harga</span>
                                <input type="text" class="ie-total-value subtotal-input" value="{{ number_format($item->subtotal, 0, ',', '.') }}" readonly placeholder="Otomatis">
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <button type="button" id="addRow" class="ie-add-row" style="flex:1;"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
                        <button type="button" id="addGroup" class="ie-add-row alt" style="flex:1;"><i class="bi bi-folder-plus"></i> Tambah Grup Baru</button>
                    </div>

                    <div class="row g-4 mb-4 align-items-start">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ppnToggle" name="ppn_active" value="1" {{ $invoice->ppn_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="ppnToggle">Aktifkan PPN 11%</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ie-summary">
                                <div class="ie-summary-row">
                                    <span class="ie-summary-label">Total</span>
                                    <input type="text" id="totalKeseluruhan" class="ie-total-input" value="{{ number_format($invoice->total, 0, ',', '.') }}" readonly>
                                </div>
                                <div class="ie-summary-row" id="ppnRow" style="{{ $invoice->ppn_active ? '' : 'display:none;' }}">
                                    <span class="ie-summary-label">PPN (11%)</span>
                                    <input type="text" id="ppnAmount" class="ie-total-input" value="" readonly>
                                </div>
                                <div class="ie-summary-row main" id="grandTotalRow" style="{{ $invoice->ppn_active ? '' : 'display:none;' }}">
                                    <span class="ie-summary-label">Grand Total</span>
                                    <input type="text" id="grandTotal" class="ie-total-input" value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3">{{ trim(old('catatan', $invoice->catatan ?? '')) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Kata Penutup (Opsional)</label>
                <p class="text-muted small mb-2">Sunting kata atau paragraf penutup yang akan muncul di PDF.</p>
                <textarea name="kata_penutup" class="form-control" rows="3">{{ old('kata_penutup', $invoice->kata_penutup ?? '') }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui Invoice</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('modals')
<button id="triggerPreviewModal" type="button" class="d-none" data-bs-toggle="modal" data-bs-target="#previewModal"></button>

<div class="modal fade" id="previewModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-receipt text-primary me-2"></i>Preview Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <p class="text-muted text-center py-4">Memuat preview...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-1"></i> Kembali Edit
                </button>
                <button type="button" id="confirmSaveBtn" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Perbarui Invoice
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($invoice->items) }};
    const tbody = document.getElementById('itemsBody');

    const formatIDR = (num) => new Intl.NumberFormat('id-ID').format(num);

    const parseIDR = (str) => {
        if (!str) return 0;
        return parseFloat(str.replace(/\./g, '').replace(',', '.')) || 0;
    };

    const formatCurrencyInput = (input) => {
        let val = input.value.replace(/[^,\d]/g, '');
        let parts = val.split(',');
        let integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        if (parts.length > 2) {
            parts = [parts[0], parts[1]];
        }
        input.value = parts.length > 1 ? integerPart + ',' + parts[1] : integerPart;
    };

    const calculateRowSubtotal = (row) => {
        const volume = parseFloat(row.querySelector('.volume-input').value) || 0;
        const harga = parseIDR(row.querySelector('.harga-input').value);
        const subtotal = volume * harga;
        row.querySelector('.subtotal-input').value = subtotal > 0 ? formatIDR(subtotal) : '';
    };

    const calculateTotal = () => {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            total += parseIDR(row.querySelector('.subtotal-input').value);
        });
        document.getElementById('totalKeseluruhan').value = total > 0 ? formatIDR(total) : '0';

        const ppnActive = document.getElementById('ppnToggle').checked;
        const ppnRow = document.getElementById('ppnRow');
        const grandTotalRow = document.getElementById('grandTotalRow');
        if (ppnActive) {
            const ppn = Math.round(total * 0.11);
            const grandTotal = total + ppn;
            document.getElementById('ppnAmount').value = formatIDR(ppn);
            document.getElementById('grandTotal').value = formatIDR(grandTotal);
            ppnRow.style.display = '';
            grandTotalRow.style.display = '';
        } else {
            ppnRow.style.display = 'none';
            grandTotalRow.style.display = 'none';
        }
    };

    document.getElementById('ppnToggle').addEventListener('change', calculateTotal);

    tbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('currency-format')) {
            formatCurrencyInput(e.target);
        }
        if (e.target.classList.contains('volume-input') || e.target.classList.contains('harga-input') || e.target.classList.contains('currency-format')) {
            const row = e.target.closest('.item-row');
            if (row) calculateRowSubtotal(row);
            calculateTotal();
        }
    });

    const getMaxGroupNo = () => {
        let max = 1;
        document.querySelectorAll('.group-no-input').forEach(input => {
            const val = parseInt(input.value) || 1;
            if (val > max) max = val;
        });
        return max;
    };

    const buildRowHTML = (index, namaItem = '', groupNo = 1) => {
        return `
        <div class="item-row ie-card">
            <div class="ie-head">
                <div class="ie-head-left">
                    <span class="ie-no">${String(index + 1).padStart(2, '0')}</span>
                    <span class="ie-group">Grup
                        <input type="number" name="items[${index}][group_no]" class="ie-group-input group-no-input" value="${groupNo}" min="1">
                    </span>
                </div>
                <div class="ie-head-right">
                    <button type="button" class="ie-remove remove-row" title="Hapus item"><i class="bi bi-trash"></i></button>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="ie-label">Produk/Jasa (Opsional)</label>
                    <input type="text" name="items[${index}][nama_item]" class="form-control nama-item-input" list="itemSuggestions"
                        value="${namaItem}" placeholder="Nama Barang/Pekerjaan">
                </div>
                <div class="col-md-7">
                    <label class="ie-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="items[${index}][deskripsi]" class="form-control deskripsi-input" rows="3" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <label class="ie-label">Tgl Kegiatan</label>
                    <input type="date" name="items[${index}][tanggal_kegiatan]" class="form-control tanggal-input">
                </div>
                <div class="col-6 col-md-3">
                    <label class="ie-label">Volume</label>
                    <input type="number" name="items[${index}][volume]" class="form-control volume-input" value="" required placeholder="0" step="any" min="0">
                </div>
                <div class="col-6 col-md-3">
                    <label class="ie-label">Satuan</label>
                    <select name="items[${index}][satuan]" class="form-select satuan-input">
                        <option value="" selected>--</option>
                        <option value="Unit">Unit</option>
                        <option value="Orang">Orang</option>
                        <option value="Paket">Paket</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Cm">Cm</option>
                        <option value="mm">mm</option>
                        <option value="Meter">Meter</option>
                        <option value="Set">Set</option>
                        <option value="Box">Box</option>
                        <option value="Rim">Rim</option>
                        <option value="Lembar">Lembar</option>
                        <option value="Buah">Buah</option>
                        <option value="Bulan">Bulan</option>
                        <option value="Tahun">Tahun</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="ie-label">Harga Satuan</label>
                    <input type="text" name="items[${index}][harga_satuan]" class="form-control harga-input currency-format" value="" required placeholder="0">
                </div>
            </div>
            <div class="ie-total">
                <span class="ie-total-label">Jumlah Harga</span>
                <input type="text" class="ie-total-value subtotal-input" value="" readonly placeholder="Otomatis">
            </div>
        </div>`;
    };

    const reindexRows = () => {
        tbody.querySelectorAll('.item-row').forEach((row, i) => {
            row.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/items\[\d+\]/, `items[${i}]`);
            });
            const no = row.querySelector('.ie-no');
            if (no) no.textContent = String(i + 1).padStart(2, '0');
        });
        itemIndex = tbody.querySelectorAll('.item-row').length;
    };

    const updateRemoveButtons = () => {
        const rows = tbody.querySelectorAll('.item-row');
        rows.forEach(row => {
            const btn = row.querySelector('.remove-row');
            if (btn) btn.disabled = (rows.length === 1);
        });
    };

    document.getElementById('addRow').addEventListener('click', function() {
        const currentMax = getMaxGroupNo();
        tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++, '', currentMax));
        reindexRows();
        updateRemoveButtons();
    });

    document.getElementById('addGroup').addEventListener('click', function() {
        const nextGroup = getMaxGroupNo() + 1;
        tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++, '', nextGroup));
        reindexRows();
        updateRemoveButtons();
    });

    tbody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const btn = e.target.closest('.remove-row');
            if (!btn.disabled) {
                btn.closest('.item-row').remove();
                reindexRows();
                updateRemoveButtons();
                calculateTotal();
            }
        }
    });

    // Preview & Confirm flow
    const form = document.getElementById('invoiceForm');
    const previewContent = document.getElementById('previewContent');
    const confirmBtn = document.getElementById('confirmSaveBtn');
    const triggerBtn = document.getElementById('triggerPreviewModal');

    const showPreviewModal = () => {
        triggerBtn.click();
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';

        const formData = new FormData(form);
        formData.delete('_method');

        fetch('{{ route("invoices.preview") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(async r => {
            if (!r.ok) {
                let msg = 'Gagal memproses (kode ' + r.status + ').';
                try { const d = await r.json(); if (d.message) msg = d.message; } catch(_) {}
                throw new Error(msg);
            }
            return r.json();
        })
        .then(data => {
            previewContent.innerHTML = data.html;
            showPreviewModal();
        })
        .catch(err => {
            alert(err.message || 'Terjadi kesalahan. Periksa kembali form.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-save"></i> Perbarui Invoice';
        });
    });

    confirmBtn.addEventListener('click', function() {
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        const formData = new FormData(form);

        fetch('{{ route("invoices.update", $invoice->id) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(async r => {
            if (!r.ok) {
                let msg = 'Gagal menyimpan (kode ' + r.status + ').';
                try { const d = await r.json(); if (d.message) msg = d.message; } catch(_) {}
                throw new Error(msg);
            }
            return r.json();
        })
        .then(data => {
            if (data.redirect) window.location.href = data.redirect;
        })
        .catch(err => {
            alert(err.message || 'Gagal menyimpan invoice.');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Perbarui Invoice';
        });
    });

    updateRemoveButtons();
    document.querySelectorAll('.currency-format').forEach(input => {
        if(input.value) formatCurrencyInput(input);
    });
    document.querySelectorAll('.item-row').forEach(row => {
        calculateRowSubtotal(row);
    });
    calculateTotal();
});
</script>
@endpush
