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
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required onchange="if(this.value==='__add__'){ window.open('{{ route('customers.create') }}','_blank'); this.value=''; }">
                        <option value="">-- Pilih Customer --</option>
                        <option value="__add__">+ Tambah Customer Baru</option>
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
                        @php $perihalArray = is_array($quotation->perihal) ? $quotation->perihal : (json_decode($quotation->perihal, true) ?? [$quotation->perihal]); @endphp
                        @foreach($perihalArray as $index => $perihal)
                        <div class="input-group mb-2 perihal-row">
                            <span class="input-group-text perihal-number">{{ $index + 1 }}.</span>
                            <select name="perihal[]" class="form-select perihal-select" required>
                                <option value="">-- Pilih Produk/Jasa --</option>
                                <optgroup label="Produk">
                                    @foreach($products as $p)
                                        @php
                                            $allPaths = $p->active_images ?? $p->images ?? [];
                                        @endphp
                                        <option value="{{ $p->name }}" {{ old('perihal.'.$index, $perihal) == $p->name ? 'selected' : '' }} data-images="{{ json_encode($allPaths) }}">{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Layanan">
                                    @foreach($services as $s)
                                        @php
                                            $allPaths = $s->active_images ?? $s->images ?? ($s->image ? [$s->image] : []);
                                        @endphp
                                        <option value="{{ $s->title }}" {{ old('perihal.'.$index, $perihal) == $s->title ? 'selected' : '' }} data-images="{{ json_encode($allPaths) }}">{{ $s->title }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <button type="button" class="btn btn-danger remove-perihal" {{ count($perihalArray) == 1 ? 'disabled' : '' }}><i class="bi bi-x"></i></button>
                        </div>
                        <div class="perihal-images mt-2 d-flex flex-wrap gap-2"></div>
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

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Perihal Surat (Opsional)</label>
                    <p class="text-muted small mb-2">Judul/perihal yang muncul di PDF. Kosongkan untuk menggunakan "Surat Penawaran".</p>
                    <input type="text" name="perihal_surat" class="form-control" placeholder="Contoh: Pengadaan Alat Kesehatan" value="{{ old('perihal_surat', $quotation->perihal_surat) }}">
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
                                <th width="12%">Harga Satuan</th>
                                <th width="12%">Jumlah Harga</th>
                                <th width="6%" class="text-center">Label<br><small class="text-muted">PDF</small></th>
                                <th width="7%" class="text-center">Aksi</th>
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
                                    <select name="items[{{ $index }}][satuan]" class="form-select satuan-input">
                                        <option value="">--</option>
                                        <option value="Unit" {{ ($item->satuan ?? 'Unit') == 'Unit' ? 'selected' : '' }}>Unit</option>
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
                                <td>
                                    <input type="text" name="items[{{ $index }}][harga_satuan]" class="form-control harga-input currency-format" value="Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}" required>
                                </td>
                                <td>
                                    <input type="text" name="items[{{ $index }}][subtotal]" class="form-control subtotal-input currency-format" value="Rp {{ number_format((float) $item->volume * $item->harga_satuan, 0, ',', '.') }}" readonly>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block m-0">
                                        <input type="hidden" name="items[{{ $index }}][tampilkan_label]" value="0">
                                        <input type="checkbox" class="form-check-input" name="items[{{ $index }}][tampilkan_label]" value="1" {{ $item->tampilkan_label ? 'checked' : '' }} style="cursor:pointer;">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row" {{ $quotation->items->count() == 1 ? 'disabled' : '' }}><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end font-weight-bold align-middle"><strong>TOTAL KESELURUHAN</strong></td>
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
            
            <button type="button" id="addRow" class="btn btn-success btn-sm mb-4"><i class="bi bi-plus-lg"></i> Baris (jika Kegiatan Optional)</button>

            <div class="mb-4">
                <label class="form-label">Kata Pengantar (Opsional)</label>
                <p class="text-muted small mb-2">Sunting kata pengantar yang akan muncul di PDF. Kosongkan untuk menggunakan teks default.</p>
                <textarea name="kata_pengantar" class="form-control" rows="5">{{ old('kata_pengantar', $quotation->kata_pengantar) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3">{{ trim(old('catatan', $quotation->catatan)) }}</textarea>
            </div>

            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-image text-primary fs-5"></i>
                    <span class="fw-semibold">Lampiran Gambar PDF</span>
                </div>
                <p class="text-muted small mb-2">Klik pada gambar di bawah setiap perihal untuk memilih gambar yang akan ditampilkan di PDF.</p>
                <input type="hidden" name="selected_images" id="selectedImagesInput" value="{{ json_encode(is_array($quotation->selected_images) ? $quotation->selected_images : (json_decode($quotation->selected_images ?? '{}', true) ?: [])) }}">
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui Penawaran</button>
            </div>
        </form>
    </div>
</div>


<script>
const STORAGE_URL = '{{ Storage::url('') }}';
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($quotation->items) }};
    const tbody = document.getElementById('itemsBody');
    const perihalContainer = document.getElementById('perihalContainer');

    const formatIDR = (num) => new Intl.NumberFormat('id-ID').format(num);

    const parseIDR = (val) => {
        return parseFloat(val.replace(/\./g, '').replace(',', '.')) || 0;
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
        row.querySelector('.subtotal-input').value = subtotal > 0 ? formatIDR(subtotal) : '0';
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

    // --- Selected images tracking ---
    let selectedImagesMap = {};

    try {
        const saved = document.getElementById('selectedImagesInput').value;
        if (saved) selectedImagesMap = JSON.parse(saved);
    } catch (e) {}

    const getPerihalName = (selectEl) => selectEl.value;

    const getImagesContainer = (selectEl) => {
        const row = selectEl.closest('.perihal-row');
        return row ? row.nextElementSibling : null;
    };

    const renderImageGallery = (selectEl) => {
        const container = getImagesContainer(selectEl);
        if (!container) return;

        const option = selectEl.options[selectEl.selectedIndex];
        container.innerHTML = '';

        if (!option || !option.value || !option.dataset.images) return;

        let images;
        try {
            images = JSON.parse(option.dataset.images);
        } catch (e) { return; }

        if (!images.length) return;

        const name = option.value;
        if (!selectedImagesMap[name]) selectedImagesMap[name] = [];

        images.forEach((rawPath, idx) => {
            const displayUrl = STORAGE_URL + rawPath;
            const isSelected = selectedImagesMap[name].includes(rawPath);
            const wrapper = document.createElement('div');
            wrapper.className = 'image-option' + (isSelected ? ' selected' : '');
            wrapper.style.cssText = `
                position: relative; cursor: pointer; border-radius: 8px; overflow: hidden;
                border: 3px solid ${isSelected ? '#28a745' : '#dee2e6'};
                transition: border-color .2s; width: 100px; height: 80px; flex-shrink: 0;
            `;
            wrapper.title = isSelected ? 'Klik untuk hapus pilihan' : 'Klik untuk pilih';

            const img = document.createElement('img');
            img.src = displayUrl;
            img.alt = `Gambar ${idx + 1}`;
            img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; display: block;';

            const check = document.createElement('div');
            check.className = 'image-check';
            check.innerHTML = '&#10003;';
            check.style.cssText = `
                position: absolute; top: 4px; right: 4px; width: 22px; height: 22px;
                border-radius: 50%; background: ${isSelected ? '#28a745' : '#6c757d'};
                color: #fff; font-size: 14px; font-weight: bold; display: flex;
                align-items: center; justify-content: center; opacity: ${isSelected ? '1' : '0.6'};
                transition: all .2s;
            `;

            wrapper.appendChild(img);
            wrapper.appendChild(check);

            wrapper.addEventListener('click', function(e) {
                e.stopPropagation();
                if (!selectedImagesMap[name]) selectedImagesMap[name] = [];
                const idx = selectedImagesMap[name].indexOf(rawPath);
                if (idx > -1) {
                    selectedImagesMap[name].splice(idx, 1);
                    wrapper.style.borderColor = '#dee2e6';
                    check.style.background = '#6c757d';
                    check.style.opacity = '0.6';
                    wrapper.title = 'Klik untuk pilih';
                } else {
                    selectedImagesMap[name].push(rawPath);
                    wrapper.style.borderColor = '#28a745';
                    check.style.background = '#28a745';
                    check.style.opacity = '1';
                    wrapper.title = 'Klik untuk hapus pilihan';
                }
                if (selectedImagesMap[name].length === 0) delete selectedImagesMap[name];
                updateSelectedImagesInput();
            });

            container.appendChild(wrapper);
        });
    };

    const updateSelectedImagesInput = () => {
        document.getElementById('selectedImagesInput').value = JSON.stringify(selectedImagesMap);
    };

    const renderAllGalleries = () => {
        document.querySelectorAll('.perihal-select').forEach(renderImageGallery);
    };

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
                <select name="items[${index}][satuan]" class="form-select satuan-input">
                    <option value="" selected>--</option>
                    <option value="Unit">Unit</option>
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
        rows.forEach(row => {
            const btn = row.querySelector('.remove-row');
            if (btn) btn.disabled = (rows.length === 1);
        });
    };

    // Listen for perihal change → update matching item nama_item
    perihalContainer.addEventListener('change', function(e) {
        if (e.target.matches('.perihal-select')) {
            const name = getPerihalName(e.target);
            const idx = Array.from(perihalContainer.querySelectorAll('.perihal-row')).indexOf(e.target.closest('.perihal-row'));
            const rows = tbody.querySelectorAll('.item-row');

            renderImageGallery(e.target);

            if (rows[idx]) {
                const namaInput = rows[idx].querySelector('.nama-item-input');
                const badge = rows[idx].querySelector('.perihal-badge');
                if (namaInput && namaInput.dataset.autofilled !== 'false') {
                    namaInput.value = name;
                    namaInput.dataset.autofilled = 'true';
                }
                if (badge) badge.innerHTML = name ? `<i class="bi bi-tag-fill me-1"></i>${name}` : '';
            } else {
                tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++, name));
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
        const optionsHTML = perihalContainer.querySelector('.perihal-select').innerHTML.replace(/\s+selected/g, '');
        const newPerihal = `
            <div class="input-group mb-2 perihal-row">
                <span class="input-group-text perihal-number">${rowCount + 1}.</span>
                <select name="perihal[]" class="form-select perihal-select" required>${optionsHTML}</select>
                <button type="button" class="btn btn-danger remove-perihal"><i class="bi bi-x"></i></button>
            </div>
            <div class="perihal-images mt-2 d-flex flex-wrap gap-2"></div>`;
        perihalContainer.insertAdjacentHTML('beforeend', newPerihal);
        updatePerihalNumbers();

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
                const select = perihalRow.querySelector('.perihal-select');
                const oldName = getPerihalName(select);
                if (oldName && selectedImagesMap[oldName]) delete selectedImagesMap[oldName];
                updateSelectedImagesInput();

                const imagesContainer = perihalRow.nextElementSibling;
                perihalRow.remove();
                if (imagesContainer && imagesContainer.classList.contains('perihal-images')) {
                    imagesContainer.remove();
                }
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
        tbody.insertAdjacentHTML('beforeend', buildRowHTML(itemIndex++, ''));
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

    // Form submit → serialize selected images
    document.getElementById('quotationForm').addEventListener('submit', function() {
        updateSelectedImagesInput();
    });

    // Initialize
    updateRemoveButtons();
    updatePerihalNumbers();
    renderAllGalleries();
    
    document.querySelectorAll('.currency-format').forEach(input => {
        if(input.value) formatCurrencyInput(input);
    });

    document.querySelectorAll('.item-row').forEach(row => calculateRowSubtotal(row));
    calculateTotal();
});
</script>
@endsection
