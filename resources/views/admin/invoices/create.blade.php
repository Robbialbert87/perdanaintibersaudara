@extends('layouts.admin')

@section('title', 'Buat Invoice')

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
                <div class="item-row ie-card">
                    <div class="ie-head">
                        <div class="ie-head-left">
                            <span class="ie-no">01</span>
                            <span class="ie-group">Grup
                                <input type="number" name="items[0][group_no]" class="ie-group-input group-no-input" value="1" min="1">
                            </span>
                        </div>
                        <div class="ie-head-right">
                            <button type="button" class="ie-remove remove-row" disabled title="Hapus item"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="ie-label">Produk/Jasa (Opsional)</label>
                            <input type="text" name="items[0][nama_item]" class="form-control nama-item-input" list="itemSuggestions" placeholder="Nama Barang/Pekerjaan">
                        </div>
                        <div class="col-md-6">
                            <label class="ie-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="items[0][deskripsi]" class="form-control deskripsi-input" rows="2" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <label class="ie-label">Tgl Kegiatan</label>
                            <input type="date" name="items[0][tanggal_kegiatan]" class="form-control tanggal-input">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="ie-label">Volume</label>
                            <input type="number" name="items[0][volume]" class="form-control volume-input" value="" required placeholder="0" step="any" min="0">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="ie-label">Satuan</label>
                            <select name="items[0][satuan]" class="form-select satuan-input">
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
                            <input type="text" name="items[0][harga_satuan]" class="form-control harga-input currency-format" value="" required placeholder="0">
                        </div>
                    </div>
                    <div class="ie-total">
                        <span class="ie-total-label">Jumlah Harga</span>
                        <input type="text" class="ie-total-value subtotal-input" value="" readonly placeholder="Otomatis">
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-4">
                <button type="button" id="addRow" class="ie-add-row" style="flex:1;"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
                <button type="button" id="addGroup" class="ie-add-row alt" style="flex:1;"><i class="bi bi-folder-plus"></i> Tambah Grup Baru</button>
            </div>

            <div class="row g-4 mb-4 align-items-start">
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="ppnToggle" name="ppn_active" value="1">
                        <label class="form-check-label fw-bold" for="ppnToggle">Aktifkan PPN 11%</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ie-summary">
                        <div class="ie-summary-row">
                            <span class="ie-summary-label">Total</span>
                            <input type="text" id="totalKeseluruhan" class="ie-total-input" value="0" readonly>
                        </div>
                        <div class="ie-summary-row" id="ppnRow" style="display:none;">
                            <span class="ie-summary-label">PPN (11%)</span>
                            <input type="text" id="ppnAmount" class="ie-total-input" value="" readonly>
                        </div>
                        <div class="ie-summary-row main" id="grandTotalRow" style="display:none;">
                            <span class="ie-summary-label">Grand Total</span>
                            <input type="text" id="grandTotal" class="ie-total-input" value="" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Pembayaran via transfer Bank BCA, tempo 30 hari, dll.">{{ trim(old('catatan')) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Kata Penutup (Opsional)</label>
                <p class="text-muted small mb-2">Sunting kata atau paragraf penutup yang akan muncul di PDF.</p>
                <textarea name="kata_penutup" class="form-control" rows="3" placeholder="Demikian invoice ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.">{{ old('kata_penutup') }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Buat Invoice</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
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
                <div class="col-md-6">
                    <label class="ie-label">Produk/Jasa (Opsional)</label>
                    <input type="text" name="items[${index}][nama_item]" class="form-control nama-item-input" list="itemSuggestions"
                        value="${namaItem}" placeholder="Nama Barang/Pekerjaan">
                </div>
                <div class="col-md-6">
                    <label class="ie-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="items[${index}][deskripsi]" class="form-control deskripsi-input" rows="2" required placeholder="Deskripsi pekerjaan/barang..."></textarea>
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
        rows.forEach((row, i) => {
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
