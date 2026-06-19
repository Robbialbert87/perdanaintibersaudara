@extends('layouts.admin')

@section('title', 'Buat Penawaran')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-file-earmark-plus me-2"></i>Form Buat Penawaran</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('quotations.store') }}" method="POST" id="quotationForm">
            @csrf
            
            <div class="row mb-4">
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
                    <label class="form-label">Perihal (Produk/Jasa) <span class="text-danger">*</span></label>
                    <div id="perihalContainer">
                        <div class="input-group mb-2 perihal-row">
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
                    <button type="button" class="btn btn-sm btn-outline-success" id="addPerihal"><i class="bi bi-plus"></i> Tambah Perihal</button>
                    <div id="perihalPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    @error('perihal')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>
            <h6 class="mb-3">Item Penawaran</h6>
            
            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="20%">Produk/Jasa (Opsional)</th>
                            <th width="25%">Deskripsi Detail <span class="text-danger">*</span></th>
                            <th width="8%">Volume</th>
                            <th width="10%">Satuan</th>
                            <th width="15%">Harga Satuan</th>
                            <th width="15%">Jumlah Harga</th>
                            <th width="7%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <!-- Baris (jika Kegiatan Optional) pertama (default) -->
                        <tr class="item-row">
                            <td>
                                <small class="text-primary fw-semibold perihal-badge d-block mb-1"></small>
                                <input type="text" name="items[0][nama_item]" class="form-control nama-item-input" placeholder="Nama Barang/Pekerjaan (opsional jika ada label)" data-autofilled="false">
                            </td>
                            <td>
                                <textarea name="items[0][deskripsi]" class="form-control deskripsi-input" rows="2" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
                            </td>
                            <td>
                                <input type="text" name="items[0][volume]" class="form-control volume-input" value="1" required>
                            </td>
                            <td>
                                <select name="items[0][satuan]" class="form-select satuan-input">
                                    <option value="">--</option>
                                    <option value="Unit" selected>Unit</option>
                                    <option value="Paket">Paket</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Cm">Cm</option>
                                    <option value="Set">Set</option>
                                    <option value="Lembar">Lembar</option>
                                    <option value="Buah">Buah</option>
                                    <option value="Bulan">Bulan</option>
                                    <option value="Tahun">Tahun</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="items[0][harga_satuan]" class="form-control harga-input currency-format" value="" required>
                            </td>
                            <td>
                                <input type="text" name="items[0][subtotal]" class="form-control subtotal-input currency-format" value="0" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end font-weight-bold align-middle"><strong>TOTAL KESELURUHAN</strong></td>
                            <td colspan="2">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="totalKeseluruhan" class="form-control font-weight-bold" value="0" readonly>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <button type="button" id="addRow" class="btn btn-success btn-sm mb-4"><i class="bi bi-plus-lg"></i> Baris (jika Kegiatan Optional)</button>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Harga belum termasuk PPN, franco Jakarta, dll.">{{ old('catatan') }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Buat Penawaran</button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const tbody = document.getElementById('itemsBody');
    const perihalContainer = document.getElementById('perihalContainer');

    // Format number to IDR
    const formatIDR = (num) => new Intl.NumberFormat('id-ID').format(num);

    const parseIDR = (val) => {
        return parseFloat(val.replace(/\./g, '').replace(',', '.')) || 0;
    };

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

    // Calculate row subtotal = volume * harga_satuan
    const calculateRowSubtotal = (row) => {
        const volume = parseFloat(row.querySelector('.volume-input').value) || 0;
        const harga = parseIDR(row.querySelector('.harga-input').value);
        const subtotal = volume * harga;
        row.querySelector('.subtotal-input').value = subtotal > 0 ? formatIDR(subtotal) : '0';
    };

    // Calculate grand total from all Jumlah Harga inputs
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

    // --- CORE: Sync item rows to perihal selections ---
    const syncItemsFromPerihal = () => {
        const perihalSelects = perihalContainer.querySelectorAll('select[name="perihal[]"]');
        const existingRows = Array.from(tbody.querySelectorAll('.item-row'));

        perihalSelects.forEach((select, i) => {
            const selectedName = select.value;
            if (!existingRows[i]) {
                // Add a new row for this perihal
                const newRowHTML = buildRowHTML(itemIndex++, selectedName);
                tbody.insertAdjacentHTML('beforeend', newRowHTML);
            } else {
                // Update nama_item of existing row if it's empty or was auto-filled
                const namaInput = existingRows[i].querySelector('.nama-item-input');
                if (namaInput && (namaInput.dataset.autofilled === 'true' || namaInput.value === '')) {
                    namaInput.value = selectedName;
                    namaInput.dataset.autofilled = 'true';
                }
            }
        });

        // Remove extra rows beyond the perihal count
        const currentRows = tbody.querySelectorAll('.item-row');
        for (let i = perihalSelects.length; i < currentRows.length; i++) {
            currentRows[i].remove();
        }

        reindexRows();
        updateRemoveButtons();
        calculateTotal();
    };

    const buildRowHTML = (index, namaItem = '') => {
        const badgeHTML = namaItem ? `<small class="text-primary fw-semibold perihal-badge d-block mb-1"><i class="bi bi-tag-fill me-1"></i>${namaItem}</small>` : `<small class="text-primary fw-semibold perihal-badge d-block mb-1"></small>`;
        return `
        <tr class="item-row">
            <td>
                ${badgeHTML}
                <input type="text" name="items[${index}][nama_item]" class="form-control nama-item-input"
                    value="${namaItem}" placeholder="Nama Barang/Pekerjaan (opsional jika ada label)"
                    data-autofilled="${namaItem !== '' ? 'true' : 'false'}">
            </td>
            <td>
                <textarea name="items[${index}][deskripsi]" class="form-control deskripsi-input" rows="2" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
            </td>
            <td>
                <input type="text" name="items[${index}][volume]" class="form-control volume-input" value="1" required>
            </td>
            <td>
                <select name="items[${index}][satuan]" class="form-select satuan-input">
                    <option value="">--</option>
                    <option value="Unit" selected>Unit</option>
                    <option value="Paket">Paket</option>
                    <option value="Pcs">Pcs</option>
                    <option value="Cm">Cm</option>
                    <option value="Set">Set</option>
                    <option value="Lembar">Lembar</option>
                    <option value="Buah">Buah</option>
                    <option value="Bulan">Bulan</option>
                    <option value="Tahun">Tahun</option>
                </select>
            </td>
            <td>
                <input type="text" name="items[${index}][harga_satuan]" class="form-control harga-input currency-format" value="" required>
            </td>
            <td>
                <input type="text" name="items[${index}][subtotal]" class="form-control subtotal-input currency-format" value="0" readonly>
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
        rows.forEach((row, i) => {
            const btn = row.querySelector('.remove-row');
            if (btn) btn.disabled = (rows.length === 1);
        });
    };

    // Listen for perihal changes
    perihalContainer.addEventListener('change', function(e) {
        if (e.target.matches('.perihal-select')) {
            updatePerihalPreview();
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
                syncItemsFromPerihal();
            }
        }
    });

    // Allow user to manually edit nama_item → mark as not auto
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

    // Tambah Perihal
    document.getElementById('addPerihal').addEventListener('click', function() {
        const rowCount = perihalContainer.querySelectorAll('.perihal-row').length;
        const optionsHTML = `<option value="">-- Pilih Produk/Jasa --</option>` +
            Array.from(perihalContainer.querySelectorAll('.perihal-select')[0].options).slice(1).map(o =>
                `<option value="${o.value}"${o.dataset.image ? ` data-image="${o.dataset.image}"` : ''}>${o.textContent}</option>`
            ).join('');
        const newPerihal = `
            <div class="input-group mb-2 perihal-row">
                <span class="input-group-text perihal-number">${rowCount + 1}.</span>
                <select name="perihal[]" class="form-select perihal-select" required>${optionsHTML}</select>
                <button type="button" class="btn btn-danger remove-perihal"><i class="bi bi-x"></i></button>
            </div>`;
        perihalContainer.insertAdjacentHTML('beforeend', newPerihal);
        updatePerihalNumbers();
        updatePerihalPreview();

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
                updatePerihalPreview();

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

    // Manual add item row (independent from perihal)
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
    updatePerihalPreview();
    
    // Apply formatting initially
    document.querySelectorAll('.currency-format').forEach(input => {
        if(input.value) formatCurrencyInput(input);
    });

    // Calculate initial row subtotals
    document.querySelectorAll('.item-row').forEach(row => calculateRowSubtotal(row));
    calculateTotal();
    });
</script>
@endsection
