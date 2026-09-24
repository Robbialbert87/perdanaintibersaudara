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
                        <div class="input-group mb-2 perihal-row">
                            <span class="input-group-text perihal-number">1.</span>
                            <select name="perihal[]" class="form-select perihal-select" required>
                                <option value="">-- Pilih Produk/Jasa --</option>
                                <optgroup label="Produk">
                                    @foreach($products as $p)
                                        @php
                                            $allPaths = $p->active_images ?? $p->images ?? [];
                                        @endphp
                                        <option value="{{ $p->name }}" data-images="{{ json_encode($allPaths) }}">{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Layanan">
                                    @foreach($services as $s)
                                        @php
                                            $allPaths = $s->active_images ?? $s->images ?? ($s->image ? [$s->image] : []);
                                        @endphp
                                        <option value="{{ $s->title }}" data-images="{{ json_encode($allPaths) }}">{{ $s->title }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <button type="button" class="btn btn-danger remove-perihal" disabled><i class="bi bi-x"></i></button>
                        </div>
                        <div class="perihal-images mt-2 d-flex flex-wrap gap-2"></div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addPerihal"><i class="bi bi-plus"></i> Tambah Perihal</button>
                    @error('perihal')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Perihal Surat (Opsional)</label>
                    <p class="text-muted small mb-2">Judul/perihal yang muncul di PDF. Kosongkan untuk menggunakan "Surat Penawaran".</p>
                    <input type="text" name="perihal_surat" class="form-control" placeholder="Contoh: Pengadaan Alat Kesehatan" value="{{ old('perihal_surat') }}">
                </div>
            </div>

            <hr>
            <h6 class="mb-3">Item Penawaran</h6>

            <div id="itemsBody" class="ie-items">
                <!-- Baris (jika Kegiatan Optional) pertama (default) -->
                <div class="item-row ie-card">
                    <div class="ie-head">
                        <div class="ie-head-left">
                            <span class="ie-no">01</span>
                            <span class="ie-perihal perihal-badge"></span>
                        </div>
                        <div class="ie-head-right">
                            <label class="ie-pdf-toggle">
                                <span>Label PDF</span>
                                <input type="hidden" name="items[0][tampilkan_label]" value="0">
                                <input type="checkbox" class="form-check-input" name="items[0][tampilkan_label]" value="1" checked style="cursor:pointer;">
                            </label>
                            <button type="button" class="ie-remove remove-row" disabled title="Hapus item"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="ie-label">Produk/Jasa (Opsional)</label>
                            <input type="text" name="items[0][nama_item]" class="form-control nama-item-input" placeholder="Nama Barang/Pekerjaan (opsional jika ada label)" data-autofilled="false">
                        </div>
                        <div class="col-md-7">
                            <label class="ie-label">Deskripsi Detail <span class="text-danger">*</span></label>
                            <textarea name="items[0][deskripsi]" class="form-control deskripsi-input" rows="3" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <label class="ie-label">Volume</label>
                            <input type="text" name="items[0][volume]" class="form-control volume-input" value="1" required>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="ie-label">Satuan</label>
                            <select name="items[0][satuan]" class="form-select satuan-input">
                                <option value="" selected>--</option>
                                <option value="Unit">Unit</option>
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
                            <input type="text" name="items[0][harga_satuan]" class="form-control harga-input currency-format" value="" required>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="ie-label">Diskon (%)</label>
                            <input type="number" name="items[0][diskon]" class="form-control diskon-input" value="" min="0" max="100" placeholder="0">
                        </div>
                    </div>
                    <div class="ie-total">
                        <span class="ie-total-label">Jumlah Harga</span>
                        <input type="text" class="ie-total-value subtotal-input currency-format" value="0" readonly>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <button type="button" id="addRow" class="ie-add-row"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
            </div>

            <div class="mb-4">
                <div class="ie-summary">
                    <div class="ie-summary-row">
                        <span class="ie-summary-label">Total Keseluruhan</span>
                        <input type="text" id="totalKeseluruhan" class="ie-total-input" value="0" readonly>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Kata Pengantar (Opsional)</label>
                <p class="text-muted small mb-2">Sunting kata pengantar yang akan muncul di PDF. Kosongkan untuk menggunakan teks default.</p>
                <textarea name="kata_pengantar" class="form-control" rows="5" placeholder="Dengan Hormat,&#10;&#10;Dengan ini kami (PIB) Perdana Inti Bersaudara yang berkedudukan di Jambi ingin menawarkan...">{{ old('kata_pengantar') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Harga belum termasuk PPN, franco Jakarta, dll.">{{ trim(old('catatan')) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Kata Penutup (Opsional)</label>
                <p class="text-muted small mb-2">Sunting kata atau paragraf penutup yang akan muncul di PDF.</p>
                <textarea name="kata_penutup" class="form-control" rows="3" placeholder="Demikian penawaran ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.">{{ old('kata_penutup') }}</textarea>
            </div>

            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-image text-primary fs-5"></i>
                    <span class="fw-semibold">Lampiran Gambar PDF</span>
                </div>
                <p class="text-muted small mb-2">Klik pada gambar di bawah setiap perihal untuk memilih gambar yang akan ditampilkan di PDF.</p>
                <input type="hidden" name="selected_images" id="selectedImagesInput" value="">
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Buat Penawaran</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const STORAGE_URL = '{{ Storage::url('') }}';
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

    // Calculate row subtotal = volume * harga_satuan * (100 - diskon) / 100
    const calculateRowSubtotal = (row) => {
        const volume = parseFloat(row.querySelector('.volume-input').value) || 0;
        const harga = parseIDR(row.querySelector('.harga-input').value);
        const diskon = parseFloat(row.querySelector('.diskon-input').value) || 0;
        let subtotal;
        if (diskon > 0) {
            subtotal = volume * harga * (100 - diskon) / 100;
        } else {
            subtotal = volume * harga;
        }
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
        if (e.target.classList.contains('volume-input') || e.target.classList.contains('harga-input') || e.target.classList.contains('diskon-input') || e.target.classList.contains('currency-format')) {
            const row = e.target.closest('.item-row');
            if (row) calculateRowSubtotal(row);
            calculateTotal();
        }
    });

    // --- Selected images tracking ---
    let selectedImagesMap = {};

    const getPerihalName = (selectEl) => selectEl.value;

    const getPerihalRow = (selectEl) => selectEl.closest('.perihal-row');

    const getImagesContainer = (selectEl) => {
        const row = getPerihalRow(selectEl);
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

    // --- CORE: Sync item rows to perihal selections ---
    const syncItemsFromPerihal = () => {
        const perihalSelects = perihalContainer.querySelectorAll('select[name="perihal[]"]');
        const existingRows = Array.from(tbody.querySelectorAll('.item-row'));

        perihalSelects.forEach((select, i) => {
            const selectedName = select.value;
            if (!existingRows[i]) {
                const newRowHTML = buildRowHTML(itemIndex++, selectedName);
                tbody.insertAdjacentHTML('beforeend', newRowHTML);
            } else {
                const namaInput = existingRows[i].querySelector('.nama-item-input');
                if (namaInput && (namaInput.dataset.autofilled === 'true' || namaInput.value === '')) {
                    namaInput.value = selectedName;
                    namaInput.dataset.autofilled = 'true';
                }
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
        const badgeHTML = namaItem ? `<i class="bi bi-tag-fill me-1"></i>${namaItem}` : '';
        return `
        <div class="item-row ie-card">
            <div class="ie-head">
                <div class="ie-head-left">
                    <span class="ie-no">${String(index + 1).padStart(2, '0')}</span>
                    <span class="ie-perihal perihal-badge">${badgeHTML}</span>
                </div>
                <div class="ie-head-right">
                    <label class="ie-pdf-toggle">
                        <span>Label PDF</span>
                        <input type="hidden" name="items[${index}][tampilkan_label]" value="0">
                        <input type="checkbox" class="form-check-input" name="items[${index}][tampilkan_label]" value="1" checked style="cursor:pointer;">
                    </label>
                    <button type="button" class="ie-remove remove-row" title="Hapus item"><i class="bi bi-trash"></i></button>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="ie-label">Produk/Jasa (Opsional)</label>
                    <input type="text" name="items[${index}][nama_item]" class="form-control nama-item-input"
                        value="${namaItem}" placeholder="Nama Barang/Pekerjaan (opsional jika ada label)"
                        data-autofilled="${namaItem !== '' ? 'true' : 'false'}">
                </div>
                <div class="col-md-7">
                    <label class="ie-label">Deskripsi Detail <span class="text-danger">*</span></label>
                    <textarea name="items[${index}][deskripsi]" class="form-control deskripsi-input" rows="3" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <label class="ie-label">Volume</label>
                    <input type="text" name="items[${index}][volume]" class="form-control volume-input" value="1" required>
                </div>
                <div class="col-6 col-md-3">
                    <label class="ie-label">Satuan</label>
                    <select name="items[${index}][satuan]" class="form-select satuan-input">
                        <option value="" selected>--</option>
                        <option value="Unit">Unit</option>
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
                    <input type="text" name="items[${index}][harga_satuan]" class="form-control harga-input currency-format" value="" required>
                </div>
                <div class="col-6 col-md-3">
                    <label class="ie-label">Diskon (%)</label>
                    <input type="number" name="items[${index}][diskon]" class="form-control diskon-input" value="" min="0" max="100" placeholder="0">
                </div>
            </div>
            <div class="ie-total">
                <span class="ie-total-label">Jumlah Harga</span>
                <input type="text" class="ie-total-value subtotal-input currency-format" value="0" readonly>
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
        rows.forEach((row, i) => {
            const btn = row.querySelector('.remove-row');
            if (btn) btn.disabled = (rows.length === 1);
        });
    };

    // Listen for perihal changes
    perihalContainer.addEventListener('change', function(e) {
        if (e.target.matches('.perihal-select')) {
            const name = getPerihalName(e.target);
            // Clean up old name from map if it was the previously selected
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

                // Remove both the perihal-row and its following images container
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
@endpush
