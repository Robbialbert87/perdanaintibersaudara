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
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required onchange="if(this.value==='__add__'){ window.open('{{ route('customers.create') }}','_blank'); this.value=''; }">
                        <option value="">-- Pilih Customer --</option>
                        <option value="__add__">+ Tambah Customer Baru</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_instansi }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Perihal (Produk/Jasa) <span class="text-danger">*</span></label>
                    <div id="perihalContainer">
                        <div class="input-group mb-2 perihal-row" data-id="0">
                            <span class="input-group-text perihal-number">1.</span>
                            <select name="perihal[]" class="form-select perihal-select" required>
                                <option value="">-- Pilih Produk/Jasa --</option>
                                <optgroup label="Produk">
                                    @foreach($products as $p)
                                        @php $firstImg = $p->active_images[0] ?? $p->images[0] ?? null; @endphp
                                        <option value="{{ $p->name }}" data-image="{{ $firstImg ? Storage::url($firstImg) : '' }}">{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Layanan">
                                    @foreach($services as $s)
                                        @php $firstImg = $s->active_images[0] ?? $s->images[0] ?? $s->image ?? null; @endphp
                                        <option value="{{ $s->title }}" data-image="{{ $firstImg ? Storage::url($firstImg) : '' }}">{{ $s->title }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <button type="button" class="btn btn-danger remove-perihal" disabled><i class="bi bi-x"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success mt-1" id="addPerihal"><i class="bi bi-plus"></i> Tambah Perihal</button>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-1 ms-2" id="addGroup"><i class="bi bi-folder-plus"></i> Tambah Grup Baru</button>
                    <div id="perihalPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    @error('perihal')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <h6 class="mb-3">Item Invoice</h6>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle" id="itemsTable" style="min-width:600px;">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Produk/Jasa</th>
                            <th width="17%">Deskripsi <span class="text-danger">*</span></th>
                            <th width="12%">Tgl Kegiatan</th>
                            <th width="6%">Vol</th>
                            <th width="9%">Satuan</th>
                            <th width="15%">Harga Satuan</th>
                            <th width="18%">Jumlah Harga</th>
                            <th width="3%" class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr class="item-row" data-perihal-id="0">
                            <td data-label="No">
                                <input type="number" name="items[0][group_no]" class="form-control group-no-input text-center fw-bold" value="1" min="1">
                            </td>
                            <td data-label="Produk/Jasa">
                                <small class="text-primary fw-semibold perihal-badge d-block mb-1"></small>
                                <input type="text" name="items[0][nama_item]" class="form-control nama-item-input" placeholder="Nama Barang/Pekerjaan (opsional jika ada label)" data-autofilled="false">
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
                            <td data-label="Satuan">
                                <select name="items[0][satuan]" class="form-select satuan-input">
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
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="7" class="text-end fw-bold align-middle"><strong>TOTAL</strong></td>
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

            <button type="button" id="addRow" class="btn btn-success btn-sm mb-4"><i class="bi bi-plus-lg"></i> Baris (jika Kegiatan Optional)</button>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Pembayaran via transfer Bank BCA, tempo 30 hari, dll.">{{ trim(old('catatan')) }}</textarea>
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
    let perihalCounter = 1;
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

    const getMaxGroupNo = () => {
        let max = 1;
        document.querySelectorAll('.group-no-input').forEach(input => {
            const val = parseInt(input.value) || 1;
            if (val > max) max = val;
        });
        return max;
    };

    const buildRowHTML = (index, namaItem = '', groupNo = 1, perihalId = null) => {
        const perihalAttr = perihalId !== null ? `data-perihal-id="${perihalId}"` : '';
        const badgeHTML = namaItem ? `<small class="text-primary fw-semibold perihal-badge d-block mb-1"><i class="bi bi-tag-fill me-1"></i>${namaItem}</small>` : `<small class="text-primary fw-semibold perihal-badge d-block mb-1"></small>`;
        return `
        <tr class="item-row" ${perihalAttr}>
            <td data-label="No">
                <input type="number" name="items[${index}][group_no]" class="form-control group-no-input text-center fw-bold" value="${groupNo}" min="1">
            </td>
            <td data-label="Produk/Jasa">
                ${badgeHTML}
                <input type="text" name="items[${index}][nama_item]" class="form-control nama-item-input"
                    value="${namaItem}" placeholder="Nama Barang/Pekerjaan (opsional jika ada label)"
                    data-autofilled="${namaItem !== '' ? 'true' : 'false'}">
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
        rows.forEach((row, i) => {
            const btn = row.querySelector('.remove-row');
            if (btn) btn.disabled = (rows.length === 1);
        });
    };

    const addPerihalRow = (namaItem = '', groupNo = 1) => {
        const perihalId = perihalCounter++;
        const rowCount = perihalContainer.querySelectorAll('.perihal-row').length;
        const optionsHTML = `<option value="">-- Pilih Produk/Jasa --</option>` +
            Array.from(perihalContainer.querySelectorAll('.perihal-select')[0].options).slice(1).map(o =>
                `<option value="${o.value}"${o.dataset.image ? ` data-image="${o.dataset.image}"` : ''}>${o.textContent}</option>`
            ).join('');
        const newPerihal = `
            <div class="input-group mb-2 perihal-row" data-id="${perihalId}">
                <span class="input-group-text perihal-number">${rowCount + 1}.</span>
                <select name="perihal[]" class="form-select perihal-select" required>${optionsHTML}</select>
                <button type="button" class="btn btn-danger remove-perihal"><i class="bi bi-x"></i></button>
            </div>`;
        perihalContainer.insertAdjacentHTML('beforeend', newPerihal);
        updatePerihalNumbers();
        updatePerihalPreview();

        tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++, '', groupNo, perihalId));
        reindexRows();
        updateRemoveButtons();
    };

    perihalContainer.addEventListener('change', function(e) {
        if (e.target.matches('.perihal-select')) {
            updatePerihalPreview();
            const perihalRow = e.target.closest('.perihal-row');
            const perihalId = perihalRow.dataset.id;
            const matchingRow = tbody.querySelector(`.item-row[data-perihal-id="${perihalId}"]`);
            if (matchingRow) {
                const namaInput = matchingRow.querySelector('.nama-item-input');
                const badge = matchingRow.querySelector('.perihal-badge');
                if (namaInput && namaInput.dataset.autofilled !== 'false') {
                    namaInput.value = e.target.value;
                    namaInput.dataset.autofilled = 'true';
                }
                if (badge) badge.innerHTML = e.target.value ? `<i class="bi bi-tag-fill me-1"></i>${e.target.value}` : '';
            }
        }
    });

    tbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('nama-item-input')) {
            e.target.dataset.autofilled = 'false';
        }
    });

    const updatePerihalPreview = () => {
        const container = document.getElementById('perihalPreview');
        container.innerHTML = '';
        document.querySelectorAll('.perihal-select').forEach(select => {
            const option = select.options[select.selectedIndex];
            if (option && option.dataset.image) {
                const img = document.createElement('img');
                img.src = option.dataset.image;
                img.alt = option.value;
                img.className = 'img-thumbnail';
                img.style = 'max-height: 80px; width: auto;';
                container.appendChild(img);
            }
        });
    };

    document.getElementById('addPerihal').addEventListener('click', function() {
        const currentMax = getMaxGroupNo();
        addPerihalRow('', currentMax);
    });

    document.getElementById('addGroup').addEventListener('click', function() {
        const nextGroup = getMaxGroupNo() + 1;
        addPerihalRow('', nextGroup);
    });

    perihalContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-perihal')) {
            const btn = e.target.closest('.remove-perihal');
            if (!btn.disabled) {
                const perihalRow = btn.closest('.perihal-row');
                const perihalId = perihalRow.dataset.id;
                perihalRow.remove();
                updatePerihalNumbers();
                updatePerihalPreview();
                const matchingRow = tbody.querySelector(`.item-row[data-perihal-id="${perihalId}"]`);
                if (matchingRow) matchingRow.remove();
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
        const currentMax = getMaxGroupNo();
        tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++, '', currentMax, null));
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
    updatePerihalPreview();
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
