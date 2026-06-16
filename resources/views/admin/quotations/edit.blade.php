@extends('layouts.admin')

@section('title', 'Edit Penawaran')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Penawaran: {{ $quotation->nomor_surat }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('quotations.update', $quotation->id) }}" method="POST" id="quotationForm">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $quotation->tanggal) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pilih Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ (old('customer_id') ?? $quotation->customer_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_instansi }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Perihal (Produk/Jasa) <span class="text-danger">*</span></label>
                    <div id="perihalContainer">
                        @php $perihalArray = $quotation->perihal ?? [$quotation->perihal]; @endphp
                        @foreach($perihalArray as $index => $perihal)
                        <div class="input-group mb-2 perihal-row">
                            <span class="input-group-text perihal-number">{{ $index + 1 }}.</span>
                            <select name="perihal[]" class="form-select" required>
                                <option value="">-- Pilih Produk/Jasa --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->name }}" {{ old('perihal.'.$index, $perihal) == $p->name ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-danger remove-perihal" {{ count($perihalArray) == 1 ? 'disabled' : '' }}><i class="bi bi-x"></i></button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addPerihal"><i class="bi bi-plus"></i> Tambah Perihal</button>
                    @error('perihal')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status Penawaran</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="draft" {{ $quotation->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="dikirim" {{ $quotation->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="deal" {{ $quotation->status == 'deal' ? 'selected' : '' }}>Deal</option>
                        <option value="batal" {{ $quotation->status == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
            </div>

            <hr>
            <h6 class="mb-3">Item Penawaran</h6>
            
            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="25%">Produk/Jasa (Opsional)</th>
                            <th width="30%">Deskripsi Detail <span class="text-danger">*</span></th>
                            <th width="10%">Volume</th>
                            <th width="15%">Harga Satuan</th>
                            <th width="15%">Jumlah Harga</th>
                            <th width="5%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        @foreach($quotation->items as $index => $item)
                        <tr class="item-row">
                            <td>
                                <small class="text-primary fw-semibold perihal-badge d-block mb-1">{!! $item->nama_item ? '<i class="bi bi-tag-fill me-1"></i>'.e($item->nama_item) : '' !!}</small>
                                <input type="text" name="items[{{ $index }}][nama_item]" class="form-control nama-item-input" value="{{ $item->nama_item }}" placeholder="Nama Barang/Pekerjaan (opsional jika ada label)" data-autofilled="false">
                            </td>
                            <td>
                                <textarea name="items[{{ $index }}][deskripsi]" class="form-control deskripsi-input" rows="2" required>{{ $item->deskripsi }}</textarea>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][volume]" class="form-control volume-input" value="{{ $item->volume }}" required>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][harga_satuan]" class="form-control harga-input currency-format" value="{{ floatval($item->harga_satuan) }}" required>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][subtotal]" class="form-control subtotal-input currency-format" value="{{ $item->subtotal }}" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end font-weight-bold align-middle"><strong>TOTAL KESELURUHAN</strong></td>
                            <td colspan="2">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="totalKeseluruhan" class="form-control font-weight-bold" value="{{ number_format($quotation->total, 0, ',', '.') }}" readonly>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <button type="button" id="addRow" class="btn btn-success btn-sm mb-4"><i class="bi bi-plus-lg"></i> Baris</button>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $quotation->catatan) }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui Penawaran</button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($quotation->items) }};
    const tbody = document.getElementById('itemsBody');
    const perihalContainer = document.getElementById('perihalContainer');

    const formatIDR = (num) => new Intl.NumberFormat('id-ID').format(num);

    // Format input as currency while typing
    const formatCurrencyInput = (input) => {
        let val = input.value.replace(/[^,\d]/g, '');
        let parts = val.split(',');
        let integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        
        // Prevent typing multiple commas
        if (parts.length > 2) {
            parts = [parts[0], parts[1]];
        }
        
        input.value = parts.length > 1 ? integerPart + ',' + parts[1] : integerPart;
    };

    const calculateTotal = () => {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            let val = row.querySelector('.subtotal-input').value.replace(/\./g, '').replace(',', '.');
            total += parseFloat(val) || 0;
        });
        document.getElementById('totalKeseluruhan').value = formatIDR(total);
    };

    tbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('currency-format')) {
            formatCurrencyInput(e.target);
        }
        if (e.target.classList.contains('subtotal-input')) calculateTotal();
    });

    const buildRowHTML = (index, namaItem = '') => {
        const badgeHTML = namaItem ? `<small class="text-primary fw-semibold perihal-badge d-block mb-1"><i class="bi bi-tag-fill me-1"></i>${namaItem}</small>` : `<small class="text-primary fw-semibold perihal-badge d-block mb-1"></small>`;
        return `
        <tr class="item-row">
            <td>
                ${badgeHTML}
                <input type="text" name="items[${index}][nama_item]" class="form-control nama-item-input"
                    value="${namaItem}" placeholder="Nama Barang/Pekerjaan"
                    data-autofilled="${namaItem !== '' ? 'true' : 'false'}" required>
            </td>
            <td>
                <textarea name="items[${index}][deskripsi]" class="form-control deskripsi-input" rows="2" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
            </td>
            <td>
                <input type="text" name="items[${index}][volume]" class="form-control volume-input" value="1" required>
            </td>
            <td>
                <input type="text" name="items[${index}][harga_satuan]" class="form-control harga-input currency-format" value="0" required>
            </td>
            <td>
                <input type="text" name="items[${index}][subtotal]" class="form-control subtotal-input currency-format" value="0" required>
            </td>
            <td class="text-center">
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

    // Listen for perihal change → update matching item nama_item
    perihalContainer.addEventListener('change', function(e) {
        if (e.target.matches('select[name="perihal[]"]')) {
            const idx = Array.from(perihalContainer.querySelectorAll('.perihal-row')).indexOf(e.target.closest('.perihal-row'));
            const rows = tbody.querySelectorAll('.item-row');
            if (rows[idx]) {
                const namaInput = rows[idx].querySelector('.nama-item-input');
                const badge = rows[idx].querySelector('.perihal-badge');
                if (namaInput && namaInput.dataset.autofilled !== 'false') {
                    namaInput.value = e.target.value;
                    namaInput.dataset.autofilled = 'true';
                }
                if (badge) badge.innerHTML = e.target.value ? `<i class="bi bi-tag-fill me-1"></i>${e.target.value}` : '';
            } else {
                // Add new row for newly selected perihal
                tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++, e.target.value));
                reindexRows();
                updateRemoveButtons();
            }
        }
    });

    // Manual edit of nama_item → unmark autofill
    tbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('nama-item-input')) {
            e.target.dataset.autofilled = 'false';
        }
    });

    // Tambah Perihal
    document.getElementById('addPerihal').addEventListener('click', function() {
        const rowCount = perihalContainer.querySelectorAll('.perihal-row').length;
        const optionsHTML = `<option value="">-- Pilih Produk/Jasa --</option>` +
            Array.from(perihalContainer.querySelectorAll('select[name="perihal[]"]')[0].options).slice(1).map(o =>
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

        // Add a matching blank item row
        tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++, ''));
        reindexRows();
        updateRemoveButtons();
    });

    // Hapus Perihal
    perihalContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-perihal')) {
            const btn = e.target.closest('.remove-perihal');
            if (!btn.disabled) {
                const perihalRow = btn.closest('.perihal-row');
                const perihalIndex = Array.from(perihalContainer.querySelectorAll('.perihal-row')).indexOf(perihalRow);
                perihalRow.remove();
                updatePerihalNumbers();

                // Remove matching item row
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

    // Manual add item row (independent)
    document.getElementById('addRow').addEventListener('click', function() {
        tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++, ''));
        reindexRows();
        updateRemoveButtons();
    });

    // Remove item row manually
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

    // Initialize
    updateRemoveButtons();
    updatePerihalNumbers();
    
    // Apply formatting initially
    document.querySelectorAll('.currency-format').forEach(input => {
        if(input.value) formatCurrencyInput(input);
    });
    calculateTotal();
});
</script>
@endsection
