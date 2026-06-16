@extends('layouts.admin')

@section('title', 'Buat Invoice')

@push('styles')
<style>
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
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-receipt me-2"></i>Form Buat Invoice</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pilih Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_instansi }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Perihal (Layanan) <span class="text-danger">*</span></label>
                    <div id="perihalContainer">
                        <div class="input-group mb-2 perihal-row">
                            <span class="input-group-text perihal-number">1.</span>
                            <select name="perihal[]" class="form-select" required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $s)
                                    <option value="{{ $s->title }}">{{ $s->title }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-danger remove-perihal" disabled><i class="bi bi-x"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success mt-1" id="addPerihal"><i class="bi bi-plus"></i> Tambah Perihal</button>
                    @error('perihal')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <h6 class="mb-3">Item Invoice</h6>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle" id="itemsTable" style="min-width:600px;">
                    <thead class="table-light">
                        <tr>
                            <th width="15%">Layanan</th>
                            <th width="20%">Deskripsi <span class="text-danger">*</span></th>
                            <th width="13%">Tgl Kegiatan</th>
                            <th width="8%">Vol</th>
                            <th width="15%">Harga Satuan</th>
                            <th width="16%">Jumlah Harga</th>
                            <th width="3%" class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr class="item-row">
                            <td data-label="Layanan">
                                <small class="text-primary fw-semibold perihal-badge d-block"></small>
                            </td>
                            <td data-label="Deskripsi">
                                <textarea name="items[0][deskripsi]" class="form-control deskripsi-input" rows="2" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
                            </td>
                            <td data-label="Tgl Kegiatan">
                                <input type="date" name="items[0][tanggal_kegiatan]" class="form-control tanggal-input">
                            </td>
                            <td data-label="Volume">
                                <input type="number" name="items[0][volume]" class="form-control volume-input" value="" required placeholder="0" step="any" min="0">
                            </td>
                            <td data-label="Harga Satuan">
                                <input type="text" name="items[0][harga_satuan]" class="form-control harga-input currency-format" value="" required placeholder="0">
                            </td>
                            <td data-label="Jumlah Harga">
                                <input type="text" name="items[0][subtotal]" class="form-control subtotal-input" value="" readonly placeholder="Otomatis">
                            </td>
                            <td class="text-center" data-label="">
                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end fw-bold align-middle"><strong>TOTAL</strong></td>
                            <td colspan="2">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="totalKeseluruhan" class="form-control fw-bold" value="0" readonly>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="button" id="addRow" class="btn btn-success btn-sm mb-4"><i class="bi bi-plus-lg"></i> Tambah Baris Item</button>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Pembayaran via transfer Bank BCA, tempo 30 hari, dll.">{{ old('catatan') }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Buat Invoice</button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const tbody = document.getElementById('itemsBody');
    const perihalContainer = document.getElementById('perihalContainer');

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

    const syncItemsFromPerihal = () => {
        const perihalSelects = perihalContainer.querySelectorAll('select[name="perihal[]"]');
        const existingRows = Array.from(tbody.querySelectorAll('.item-row'));

        perihalSelects.forEach((select, i) => {
            const selectedName = select.value;
            if (!existingRows[i]) {
                const newRowHTML = buildRowHTML(itemIndex++, selectedName);
                tbody.insertAdjacentHTML('beforeend', newRowHTML);
            }
        });

        const currentRows = tbody.querySelectorAll('.item-row');
        for (let i = perihalSelects.length; i < currentRows.length; i++) {
            currentRows[i].remove();
        }

        reindexRows();
        updateRemoveButtons();
        calculateTotal();
    };

    const buildRowHTML = (index, namaItem = '') => {
        const badgeHTML = namaItem ? `<small class="text-primary fw-semibold perihal-badge d-block mb-1"><i class="bi bi-tag-fill me-1"></i>${namaItem}</small>` : `<small class="text-primary fw-semibold perihal-badge d-block"></small>`;
        return `
        <tr class="item-row">
            <td data-label="Layanan">
                ${badgeHTML}
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
        rows.forEach((row, i) => {
            const btn = row.querySelector('.remove-row');
            if (btn) btn.disabled = (rows.length === 1);
        });
    };

    perihalContainer.addEventListener('change', function(e) {
        if (e.target.matches('select[name="perihal[]"]')) {
            const idx = Array.from(perihalContainer.querySelectorAll('.perihal-row')).indexOf(e.target.closest('.perihal-row'));
            const rows = tbody.querySelectorAll('.item-row');
            if (rows[idx]) {
                const badge = rows[idx].querySelector('.perihal-badge');
                if (badge) badge.innerHTML = e.target.value ? `<i class="bi bi-tag-fill me-1"></i>${e.target.value}` : '';
            } else {
                syncItemsFromPerihal();
            }
        }
    });

    document.getElementById('addPerihal').addEventListener('click', function() {
        const rowCount = perihalContainer.querySelectorAll('.perihal-row').length;
        const optionsHTML = `<option value="">-- Pilih Layanan --</option>` +
            Array.from(document.querySelectorAll('select[name="perihal[]"]:first-of-type option')).slice(1).map(o =>
                `<option value="${o.value}">${o.textContent}</option>`
            ).join('');
        const newPerihal = `
            <div class="input-group mb-2 perihal-row">
                <span class="input-group-text perihal-number">${rowCount + 1}.</span>
                <select name="perihal[]" class="form-select" required>${optionsHTML}</select>
                <button type="button" class="btn btn-danger remove-perihal"><i class="bi bi-x"></i></button>
            </div>`;
        perihalContainer.insertAdjacentHTML('beforeend', newPerihal);
        updatePerihalNumbers();
        tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++));
        reindexRows();
        updateRemoveButtons();
    });

    perihalContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-perihal')) {
            const btn = e.target.closest('.remove-perihal');
            if (!btn.disabled) {
                const perihalRow = btn.closest('.perihal-row');
                const perihalIndex = Array.from(perihalContainer.querySelectorAll('.perihal-row')).indexOf(perihalRow);
                perihalRow.remove();
                updatePerihalNumbers();
                const itemRows = tbody.querySelectorAll('.item-row');
                if (itemRows[perihalIndex]) itemRows[perihalIndex].remove();
                reindexRows();
                updateRemoveButtons();
                calculateTotal();
            }
        }
    });

    const updatePerihalNumbers = () => {
        perihalContainer.querySelectorAll('.perihal-row').forEach((row, i) => {
            row.querySelector('.perihal-number').textContent = (i + 1) + '.';
            const btn = row.querySelector('.remove-perihal');
            if (btn) btn.disabled = (perihalContainer.querySelectorAll('.perihal-row').length === 1);
        });
    };

    document.getElementById('addRow').addEventListener('click', function() {
        tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++));
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

    updateRemoveButtons();
    updatePerihalNumbers();
    document.querySelectorAll('.currency-format').forEach(input => {
        if(input.value) formatCurrencyInput(input);
    });
    document.querySelectorAll('.item-row').forEach(row => {
        calculateRowSubtotal(row);
    });
    calculateTotal();
});
</script>
@endsection
