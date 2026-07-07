@extends('layouts.admin')

@section('title', 'Edit Invoice')

@push('styles')
<style>
.modal-backdrop ~ .modal-backdrop { display: none !important; }
#previewModal .modal-footer { position: relative; z-index: 1060; }
@media (max-width: 768px) {
    #itemsTable, #itemsTable thead, #itemsTable tbody,
    #itemsTable tr, #itemsTable td { display: block; }
    #itemsTable { min-width: auto !important; }
    #itemsTable thead { display: none; }
    #itemsTable tr.item-row {
        background: #1c252e;
        border: 1px solid #454f5b;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 16px;
    }
    #itemsTable td {
        border: none !important;
        padding: 6px 0 !important;
        width: 100% !important;
    }
    #itemsTable td:before {
        content: attr(data-label);
        display: block;
        font-weight: 600;
        font-size: 11px;
        color: #637381;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 4px;
    }
    #itemsTable td:last-child {
        text-align: right;
        padding-top: 12px !important;
    }
    #itemsTable td textarea { min-height: 54px; }
    #itemsTable tfoot tr {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #itemsTable tfoot td { border: none !important; }
    #itemsTable tfoot td:first-child { flex: 1; text-align: right; padding: 0 !important; }
    #itemsTable tfoot td:last-child { flex: 1; padding: 0 !important; }
    #itemsTable tfoot td:last-child .input-group { margin-bottom: 0; }
    #itemsTable tfoot td:before { display: none; }
}
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

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle" id="itemsTable" style="min-width:600px;">
                    <thead class="table-light">
                        <tr>
                            <th width="8%">Grup</th>
                            <th width="15%">Produk/Jasa</th>
                            <th width="15%">Deskripsi <span class="text-danger">*</span></th>
                            <th width="11%">Tgl Kegiatan</th>
                            <th width="8%">Vol</th>
                            <th width="9%">Satuan</th>
                            <th width="15%">Harga Satuan</th>
                            <th width="18%">Jumlah Harga</th>
                            <th width="3%" class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        @foreach($invoice->items as $index => $item)
                        <tr class="item-row">
                            <td data-label="Grup">
                                <input type="number" name="items[{{ $index }}][group_no]" class="form-control group-no-input text-center fw-bold" value="{{ $item->group_no ?? 1 }}" min="1">
                            </td>
                            <td data-label="Produk/Jasa">
                                <input type="text" name="items[{{ $index }}][nama_item]" class="form-control nama-item-input" list="itemSuggestions" value="{{ $item->nama_item }}" placeholder="Nama Barang/Pekerjaan">
                            </td>
                            <td data-label="Deskripsi">
                                <textarea name="items[{{ $index }}][deskripsi]" class="form-control deskripsi-input" rows="2" required>{{ $item->deskripsi }}</textarea>
                            </td>
                            <td data-label="Tgl Kegiatan">
                                <input type="date" name="items[{{ $index }}][tanggal_kegiatan]" class="form-control tanggal-input" value="{{ $item->tanggal_kegiatan }}">
                            </td>
                            <td data-label="Volume">
                                <input type="number" name="items[{{ $index }}][volume]" class="form-control volume-input" value="{{ $item->volume }}" required placeholder="0" step="any" min="0">
                            </td>
                            <td data-label="Satuan">
                                <select name="items[{{ $index }}][satuan]" class="form-select satuan-input">
                                    <option value="">--</option>
                                    <option value="Unit" {{ ($item->satuan ?? 'Unit') == 'Unit' ? 'selected' : '' }}>Unit</option>
                                    <option value="Orang" {{ ($item->satuan ?? '') == 'Orang' ? 'selected' : '' }}>Orang</option>
                                    <option value="Paket" {{ ($item->satuan ?? '') == 'Paket' ? 'selected' : '' }}>Paket</option>
                                    <option value="Pcs" {{ ($item->satuan ?? '') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="Cm" {{ ($item->satuan ?? '') == 'Cm' ? 'selected' : '' }}>Cm</option>
                                    <option value="Set" {{ ($item->satuan ?? '') == 'Set' ? 'selected' : '' }}>Set</option>
                                    <option value="Box" {{ ($item->satuan ?? '') == 'Box' ? 'selected' : '' }}>Box</option>
                                    <option value="Rim" {{ ($item->satuan ?? '') == 'Rim' ? 'selected' : '' }}>Rim</option>
                                    <option value="Lembar" {{ ($item->satuan ?? '') == 'Lembar' ? 'selected' : '' }}>Lembar</option>
                                    <option value="Buah" {{ ($item->satuan ?? '') == 'Buah' ? 'selected' : '' }}>Buah</option>
                                    <option value="Bulan" {{ ($item->satuan ?? '') == 'Bulan' ? 'selected' : '' }}>Bulan</option>
                                    <option value="Tahun" {{ ($item->satuan ?? '') == 'Tahun' ? 'selected' : '' }}>Tahun</option>
                                </select>
                            </td>
                            <td data-label="Harga Satuan">
                                <input type="text" name="items[{{ $index }}][harga_satuan]" class="form-control harga-input currency-format" value="{{ floatval($item->harga_satuan) }}" required placeholder="0">
                            </td>
                            <td data-label="Jumlah Harga">
                                <input type="text" name="items[{{ $index }}][subtotal]" class="form-control subtotal-input" value="{{ number_format($item->subtotal, 0, ',', '.') }}" readonly placeholder="Otomatis">
                            </td>
                            <td class="text-center" data-label="">
                                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="7" class="text-end fw-bold align-middle text-white"><strong>TOTAL</strong></td>
                            <td colspan="2">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="totalKeseluruhan" class="form-control fw-bold" value="{{ number_format($invoice->total, 0, ',', '.') }}" readonly>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex gap-2 mb-4">
                <button type="button" id="addRow" class="btn btn-success btn-sm"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
                <button type="button" id="addGroup" class="btn btn-outline-primary btn-sm"><i class="bi bi-folder-plus"></i> Tambah Grup Baru</button>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3">{{ trim(old('catatan', $invoice->catatan ?? '')) }}</textarea>
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
    };

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
        const badgeHTML = namaItem ? `<small class="text-primary fw-semibold perihal-badge d-block mb-1"><i class="bi bi-tag-fill me-1"></i>${namaItem}</small>` : '';
        return `
        <tr class="item-row">
            <td data-label="Grup">
                <input type="number" name="items[${index}][group_no]" class="form-control group-no-input text-center fw-bold" value="${groupNo}" min="1">
            </td>
            <td data-label="Produk/Jasa">
                ${badgeHTML}
                <input type="text" name="items[${index}][nama_item]" class="form-control nama-item-input" list="itemSuggestions"
                    value="${namaItem}" placeholder="Nama Barang/Pekerjaan">
            </td>
            <td data-label="Deskripsi">
                <textarea name="items[${index}][deskripsi]" class="form-control deskripsi-input" rows="2" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
            </td>
            <td data-label="Tgl Kegiatan">
                <input type="date" name="items[${index}][tanggal_kegiatan]" class="form-control tanggal-input">
            </td>
            <td data-label="Volume">
                <input type="number" name="items[${index}][volume]" class="form-control volume-input" value="" required placeholder="0" step="any" min="0">
            </td>
            <td data-label="Satuan">
                <select name="items[${index}][satuan]" class="form-select satuan-input">
                    <option value="" selected>--</option>
                    <option value="Unit">Unit</option>
                    <option value="Orang">Orang</option>
                    <option value="Paket">Paket</option>
                    <option value="Pcs">Pcs</option>
                    <option value="Cm">Cm</option>
                    <option value="Set">Set</option>
                    <option value="Box">Box</option>
                    <option value="Rim">Rim</option>
                    <option value="Lembar">Lembar</option>
                    <option value="Buah">Buah</option>
                    <option value="Bulan">Bulan</option>
                    <option value="Tahun">Tahun</option>
                </select>
            </td>
            <td data-label="Harga Satuan">
                <input type="text" name="items[${index}][harga_satuan]" class="form-control harga-input currency-format" value="" required placeholder="0">
            </td>
            <td data-label="Jumlah Harga">
                <input type="text" name="items[${index}][subtotal]" class="form-control subtotal-input" value="" readonly placeholder="Otomatis">
            </td>
            <td class="text-center" data-label="">
                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;
    };

    const reindexRows = () => {
        tbody.querySelectorAll('.item-row').forEach((row, i) => {
            row.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/items\[\d+\]/, `items[${i}]`);
            });
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
