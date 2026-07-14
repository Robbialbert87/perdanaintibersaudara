@extends('layouts.admin')

@section('title', 'Buat Purchase Order')

@push('styles')
<style>
    .po-container {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #d1d5db;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    .po-header {
        padding: 20px 30px;
        border-bottom: 3px double #000;
    }
    .po-header-top {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .po-header .logo {
        width: 80px;
    }
    .po-header .company-name {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1a2332;
        margin: 0;
        font-family: 'Arial', sans-serif;
    }
    .po-header .subtitle {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        letter-spacing: 1px;
        margin: 2px 0;
    }
    .po-header .address-line {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 1px 0;
    }
    .po-title-section {
        text-align: center;
        padding: 20px 30px 15px;
    }
    .po-title-section h2 {
        font-size: 1.6rem;
        font-weight: 800;
        color: #1a2332;
        margin: 0;
        letter-spacing: 2px;
    }
    .po-number {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-top: 5px;
    }
    .po-date {
        text-align: right;
        padding: 10px 30px;
        font-size: 0.95rem;
    }
    .po-info-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000;
        font-size: 0.9rem;
    }
    .po-info-table td {
        border: 1px solid #000;
        padding: 8px 12px;
        vertical-align: top;
    }
    .po-info-table .label {
        font-weight: 600;
        color: #374151;
        width: 120px;
        background: #f9fafb;
    }
    .po-info-table .value .form-control {
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 6px 10px;
        font-size: 0.9rem;
        background: #fff;
        width: 100%;
        color: #1a2332;
    }
    .po-info-table .value .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.2);
    }
    .po-section-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1a2332;
        background: #e5e7eb;
        padding: 6px 12px;
        border: 1px solid #000;
        margin: 0;
    }
    .po-two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }
    .po-col {
        border: 1px solid #000;
        padding: 0;
    }
    .po-col-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1a2332;
        background: #e5e7eb;
        padding: 6px 12px;
        border-bottom: 1px solid #000;
        text-align: center;
    }
    .po-col .po-col-body {
        padding: 10px 12px;
    }
    .po-col .po-col-body .field-row {
        display: flex;
        gap: 8px;
        margin-bottom: 6px;
        align-items: center;
    }
    .po-col .po-col-body .field-label {
        font-weight: 600;
        color: #6b7280;
        min-width: 80px;
        font-size: 0.8rem;
    }
    .po-col .po-col-body .field-input {
        flex: 1;
    }
    .po-col .po-col-body .field-input .form-control {
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 6px 10px;
        font-size: 0.85rem;
        background: #fff;
        width: 100%;
        color: #1a2332;
    }
    .po-col .po-col-body .field-input .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.2);
    }
    .po-items-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000;
        font-size: 0.9rem;
        margin-top: 0;
    }
    .po-items-table th {
        background: #e5e7eb;
        border: 1px solid #000;
        padding: 8px 10px;
        font-weight: 700;
        color: #1a2332;
        text-align: center;
        font-size: 0.85rem;
    }
    .po-items-table td {
        border: 1px solid #000;
        padding: 8px 10px;
        vertical-align: middle;
    }
    .po-items-table .text-center { text-align: center; }
    .po-items-table .text-right { text-align: right; }
    .po-items-table input,
    .po-items-table select,
    .po-items-table textarea {
        font-size: 0.85rem;
        padding: 4px 6px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        width: 100%;
        background: #fff;
        color: #1a2332;
    }
    .po-items-table textarea {
        min-height: 38px;
        resize: vertical;
    }
    .po-items-table input:focus,
    .po-items-table select:focus,
    .po-items-table textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.2);
    }
    .po-summary {
        display: flex;
        justify-content: flex-end;
        padding: 15px 30px;
        border: 1px solid #000;
        border-top: none;
    }
    .po-summary-table {
        width: 300px;
    }
    .po-summary-table td {
        padding: 5px 10px;
        font-size: 0.9rem;
    }
    .po-summary-table .label {
        text-align: right;
        font-weight: 600;
        color: #6b7280;
    }
    .po-summary-table .value {
        text-align: right;
        font-weight: 600;
        color: #1a2332;
    }
    .po-summary-table .grand-total {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1a2332;
        border-top: 2px solid #000;
        padding-top: 8px;
    }
    .po-signature {
        display: flex;
        justify-content: flex-end;
        padding: 30px 30px 20px;
    }
    .po-signature-box {
        text-align: center;
        min-width: 200px;
    }
    .po-signature-box .title {
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 60px;
    }
    .po-signature-box .name {
        font-weight: 700;
        color: #1a2332;
    }
    .po-signature-box .position {
        color: #6b7280;
        font-size: 0.85rem;
    }
    .po-actions {
        padding: 15px 30px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-remove-item {
        background: #ef4444;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 4px 8px;
        cursor: pointer;
        font-size: 0.8rem;
    }
    .btn-remove-item:hover { background: #dc2626; }
    .btn-add-item {
        background: #10b981;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 6px 14px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .btn-add-item:hover { background: #059669; }
    .po-form-section {
        padding: 20px 30px;
        border: 1px solid #000;
        border-top: none;
    }
    .po-form-section .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #374151;
        margin-bottom: 4px;
    }
    .po-form-section .form-control,
    .po-form-section .form-select {
        font-size: 0.9rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="po-container">
    <form action="{{ route('purchase-orders.store') }}" method="POST" id="poForm">
        @csrf

        <!-- Header -->
        <div class="po-header">
            <div class="po-header-top">
                @php
                    $path = public_path('style/assets/img/pib-logo.png');
                    if(!file_exists($path)) $path = public_path('style/assets/img/PIBnew.png');
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_exists($path) ? file_get_contents($path) : '';
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                @endphp
                @if(file_exists($path))
                    <img src="{{ $base64 }}" alt="Logo" class="logo">
                @endif
                <div>
                    <h1 class="company-name">CV. PERDANA INTI BERSAUDARA</h1>
                    <p class="subtitle">RADIOLOGI - SERVICE - SPAREPART - TIMBAL - ACCESORIES</p>
                    <p class="address-line">Jl. Kepodang 1 N0. 205 RT 24 Kel. Andil Jaya Jambi</p>
                    <p class="address-line">HP. 0852 6305 6505 &nbsp;&nbsp; E-mail : perdanaintibersaudara@gmail.com</p>
                </div>
            </div>
        </div>

        <!-- Date -->
        <div class="po-date">
            <label class="form-label mb-1">Tanggal</label>
            <input type="date" name="po_date" class="form-control" style="width: 200px; display: inline-block;" value="{{ old('po_date', date('Y-m-d')) }}" required>
        </div>

        <!-- Title -->
        <div class="po-title-section">
            <h2>PURCHASE ORDER</h2>
        </div>

        <!-- Vendor Info -->
        <div style="padding: 0 30px 15px;">
            <p class="po-section-title">KEPADA VENDOR</p>
            <table class="po-info-table">
                <tr>
                    <td class="label">Nama Vendor</td>
                    <td class="value">
                        <input type="text" name="vendor" class="form-control" placeholder="Nama Perusahaan Vendor" value="{{ old('vendor') }}" required>
                    </td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="value">
                        <input type="text" name="vendor_address" class="form-control" placeholder="Alamat Vendor" value="{{ old('vendor_address') }}">
                    </td>
                </tr>
                <tr>
                    <td class="label">CP</td>
                    <td class="value">
                        <input type="text" name="vendor_cp" class="form-control" placeholder="Contact Person" value="{{ old('vendor_cp') }}">
                    </td>
                </tr>
                <tr>
                    <td class="label">Telepon</td>
                    <td class="value">
                        <input type="text" name="vendor_phone" class="form-control" placeholder="No. Telepon" value="{{ old('vendor_phone') }}">
                    </td>
                </tr>
            </table>
        </div>

        <!-- Two Column: Pesanan Pembelian & Alamat Pengiriman -->
        <div class="po-two-col" style="margin: 0 30px 15px;">
            <!-- Left: Pesanan Pembelian -->
            <div class="po-col">
                <div class="po-col-title">PESANAN PEMBELIAN</div>
                <div class="po-col-body">
                    <div class="field-row">
                        <span class="field-label">Dipesan Oleh</span>
                        <input type="text" name="buyer_name" class="form-control field-input" placeholder="Nama Pemesan" value="{{ old('buyer_name') }}" required>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Alamat</span>
                        <input type="text" name="buyer_address" class="form-control field-input" placeholder="Alamat Pemesan" value="{{ old('buyer_address') }}">
                    </div>
                    <div class="field-row">
                        <span class="field-label">CP</span>
                        <input type="text" name="buyer_cp" class="form-control field-input" placeholder="Contact Person" value="{{ old('buyer_cp') }}">
                    </div>
                    <div class="field-row">
                        <span class="field-label">Telepon</span>
                        <input type="text" name="buyer_phone" class="form-control field-input" placeholder="No. Telepon" value="{{ old('buyer_phone') }}">
                    </div>
                </div>
            </div>
            <!-- Right: Alamat Pengiriman -->
            <div class="po-col">
                <div class="po-col-title">ALAMAT PENGIRIMAN</div>
                <div class="po-col-body">
                    <div class="field-row">
                        <span class="field-label">Nama</span>
                        <input type="text" name="shipping_name" class="form-control field-input" placeholder="Nama Penerima" value="{{ old('shipping_name') }}">
                    </div>
                    <div class="field-row">
                        <span class="field-label">Alamat</span>
                        <input type="text" name="shipping_address" class="form-control field-input" placeholder="Alamat Pengiriman" value="{{ old('shipping_address') }}">
                    </div>
                    <div class="field-row">
                        <span class="field-label">CP</span>
                        <input type="text" name="shipping_cp" class="form-control field-input" placeholder="Contact Person" value="{{ old('shipping_cp') }}">
                    </div>
                    <div class="field-row">
                        <span class="field-label">Telepon</span>
                        <input type="text" name="shipping_phone" class="form-control field-input" placeholder="No. Telepon" value="{{ old('shipping_phone') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div style="margin: 0 30px 15px;">
            <p class="po-section-title">DAFTAR BARANG</p>
            <table class="po-items-table" id="itemsTable">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th width="8%">Jumlah</th>
                        <th width="10%">Satuan</th>
                        <th width="30%">Jenis Barang</th>
                        <th width="14%">Harga Satuan</th>
                        <th width="14%">Total Harga</th>
                        <th width="8%">DP %</th>
                        <th width="12%">Jumlah DP</th>
                        <th width="5%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <tr class="item-row">
                        <td class="text-center">1</td>
                        <td><input type="text" name="items[0][qty]" class="qty-input" value="1" required></td>
                        <td>
                            <select name="items[0][satuan]" class="satuan-input">
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
                        </td>
                        <td><textarea name="items[0][deskripsi]" class="deskripsi-input" rows="2" placeholder="Jenis Barang / Deskripsi" required></textarea></td>
                        <td><input type="text" name="items[0][price]" class="price-input currency-format" value="" required></td>
                        <td><input type="text" name="items[0][subtotal]" class="subtotal-input" value="0" readonly></td>
                        <td><input type="text" name="items[0][dp_persentase]" class="dp-input" placeholder="0" value="0"></td>
                        <td><input type="text" name="items[0][dp_nominal]" class="dp-nominal-input" value="0" readonly></td>
                        <td class="text-center"><button type="button" class="btn-remove-item remove-row" disabled><i class="bi bi-trash"></i></button></td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 8px;">
                <button type="button" id="addRow" class="btn-add-item"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
            </div>
        </div>

        <!-- Summary -->
        <div class="po-summary">
            <table class="po-summary-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value">Rp <span id="subtotalDisplay">0</span></td>
                </tr>
                <tr>
                    <td class="label">Total DP</td>
                    <td class="value">Rp <span id="dpDisplay">0</span></td>
                </tr>
                <tr>
                    <td class="label">Diskon</td>
                    <td class="value">
                        <input type="text" name="discount" id="discountInput" class="currency-format" style="width: 150px; text-align: right; border: 1px solid #d1d5db; border-radius: 4px; padding: 4px 8px;" value="{{ old('discount', '0') }}">
                    </td>
                </tr>
                <tr>
                    <td class="label">PPN (11%)</td>
                    <td class="value">
                        <input type="text" name="ppn" id="ppnInput" class="currency-format" style="width: 150px; text-align: right; border: 1px solid #d1d5db; border-radius: 4px; padding: 4px 8px;" value="{{ old('ppn', '0') }}">
                    </td>
                </tr>
                <tr>
                    <td class="label grand-total">GRAND TOTAL</td>
                    <td class="value grand-total">Rp <span id="grandTotalDisplay">0</span></td>
                </tr>
            </table>
        </div>

        <!-- Keterangan -->
        <div class="po-form-section">
            <label class="form-label">Keterangan <small class="text-muted">(opsional)</small></label>
            <textarea name="catatan" class="form-control" rows="3" placeholder="Tulis keterangan tambahan jika diperlukan...">{{ old('catatan') }}</textarea>
        </div>

        <!-- Signature -->
        <div class="po-signature">
            <div class="po-signature-box">
                <div class="title">Hormat Saya,</div>
                <div class="name">CV. PERDANA INTI BERSAUDARA</div>
                <div class="position">Direktur</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="po-actions">
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Purchase Order</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const tbody = document.getElementById('itemsBody');

    const formatIDR = (num) => new Intl.NumberFormat('id-ID').format(num);

    const parseIDR = (val) => {
        return parseFloat(val.replace(/\./g, '').replace(',', '.')) || 0;
    };

    const formatCurrencyInput = (input) => {
        let val = input.value.replace(/[^,\d]/g, '');
        let parts = val.split(',');
        let integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        if (parts.length > 2) parts = [parts[0], parts[1]];
        input.value = parts.length > 1 ? integerPart + ',' + parts[1] : integerPart;
    };

    const calculateRowSubtotal = (row) => {
        const qty = parseFloat(row.querySelector('.qty-input')?.value) || 0;
        const price = parseIDR(row.querySelector('.price-input')?.value);
        const subtotal = qty * price;
        const subtotalInput = row.querySelector('.subtotal-input');
        if (subtotalInput) subtotalInput.value = subtotal > 0 ? formatIDR(subtotal) : '0';

        const dpPersentase = parseFloat(row.querySelector('.dp-input')?.value) || 0;
        const dpNominal = subtotal * (dpPersentase / 100);
        const dpNominalInput = row.querySelector('.dp-nominal-input');
        if (dpNominalInput) dpNominalInput.value = dpNominal > 0 ? formatIDR(dpNominal) : '0';
    };

    const calculateTotal = () => {
        let total = 0;
        let totalDp = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            total += parseIDR(row.querySelector('.subtotal-input')?.value || '0');
            totalDp += parseIDR(row.querySelector('.dp-nominal-input')?.value || '0');
        });

        const discount = parseIDR(document.getElementById('discountInput').value);
        const ppn = parseIDR(document.getElementById('ppnInput').value);
        const grandTotal = total - discount + ppn;

        document.getElementById('subtotalDisplay').textContent = total > 0 ? formatIDR(total) : '0';
        document.getElementById('dpDisplay').textContent = totalDp > 0 ? formatIDR(totalDp) : '0';
        document.getElementById('grandTotalDisplay').textContent = grandTotal > 0 ? formatIDR(grandTotal) : '0';
    };

    tbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('currency-format')) {
            formatCurrencyInput(e.target);
        }
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input') || e.target.classList.contains('currency-format') || e.target.classList.contains('dp-input')) {
            const row = e.target.closest('.item-row');
            if (row) calculateRowSubtotal(row);
            calculateTotal();
        }
    });

    document.getElementById('discountInput').addEventListener('input', function() {
        formatCurrencyInput(this);
        calculateTotal();
    });

    document.getElementById('ppnInput').addEventListener('input', function() {
        formatCurrencyInput(this);
        calculateTotal();
    });

    const buildRowHTML = (index) => {
        return `
        <tr class="item-row">
            <td class="text-center">${index + 1}</td>
            <td><input type="text" name="items[${index}][qty]" class="qty-input" value="1" required></td>
            <td>
                <select name="items[${index}][satuan]" class="satuan-input">
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
            </td>
            <td><textarea name="items[${index}][deskripsi]" class="deskripsi-input" rows="2" placeholder="Jenis Barang / Deskripsi" required></textarea></td>
            <td><input type="text" name="items[${index}][price]" class="price-input currency-format" value="" required></td>
            <td><input type="text" name="items[${index}][subtotal]" class="subtotal-input" value="0" readonly></td>
            <td><input type="text" name="items[${index}][dp_persentase]" class="dp-input" placeholder="0" value="0"></td>
            <td><input type="text" name="items[${index}][dp_nominal]" class="dp-nominal-input" value="0" readonly></td>
            <td class="text-center"><button type="button" class="btn-remove-item remove-row"><i class="bi bi-trash"></i></button></td>
        </tr>`;
    };

    const reindexRows = () => {
        tbody.querySelectorAll('.item-row').forEach((row, i) => {
            row.querySelector('td').textContent = i + 1;
            row.querySelectorAll('input, select, textarea').forEach(el => {
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
    document.querySelectorAll('.item-row').forEach(row => calculateRowSubtotal(row));
    calculateTotal();
});
</script>
@endpush
