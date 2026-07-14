@extends('layouts.admin')

@section('title', 'Detail Purchase Order')

@push('styles')
<style>
    .po-preview {
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
    .po-header .logo { width: 80px; }
    .po-header .company-name {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1a2332;
        margin: 0;
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
    .po-items-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000;
        font-size: 0.9rem;
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
</style>
@endpush

@section('content')
<div class="po-preview">
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
        Jambi, {{ \Carbon\Carbon::parse($purchaseOrder->tanggal)->locale('id')->translatedFormat('d F Y') }}
    </div>

    <!-- Title -->
    <div class="po-title-section">
        <h2>PURCHASE ORDER</h2>
        <p class="po-number">{{ $purchaseOrder->nomor_surat }}</p>
    </div>

    <!-- Vendor Info -->
    <div style="padding: 0 30px 15px;">
        <p class="po-section-title">KEPADA VENDOR</p>
        <table class="po-info-table">
            <tr>
                <td class="label">Nama Vendor</td>
                <td>{{ $purchaseOrder->vendor ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>{{ $purchaseOrder->vendor_address ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">CP</td>
                <td>{{ $purchaseOrder->vendor_cp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Telepon</td>
                <td>{{ $purchaseOrder->vendor_phone ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Two Column: Pesanan Pembelian & Alamat Pengiriman -->
    <div class="po-two-col" style="margin: 0 30px 15px;">
        <div class="po-col">
            <div class="po-col-title">PESANAN PEMBELIAN</div>
            <div class="po-col-body">
                <div class="field-row">
                    <span class="field-label">Dipesan Oleh</span>
                    <span>{{ $purchaseOrder->buyer_name ?? '-' }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Alamat</span>
                    <span>{{ $purchaseOrder->buyer_address ?? '-' }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">CP</span>
                    <span>{{ $purchaseOrder->buyer_cp ?? '-' }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Telepon</span>
                    <span>{{ $purchaseOrder->buyer_phone ?? '-' }}</span>
                </div>
            </div>
        </div>
        <div class="po-col">
            <div class="po-col-title">ALAMAT PENGIRIMAN</div>
            <div class="po-col-body">
                <div class="field-row">
                    <span class="field-label">Nama</span>
                    <span>{{ $purchaseOrder->shipping_name ?? '-' }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Alamat</span>
                    <span>{{ $purchaseOrder->shipping_address ?? '-' }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">CP</span>
                    <span>{{ $purchaseOrder->shipping_cp ?? '-' }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Telepon</span>
                    <span>{{ $purchaseOrder->shipping_phone ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div style="margin: 0 30px 15px;">
        <p class="po-section-title">DAFTAR BARANG</p>
        <table class="po-items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Jumlah</th>
                    <th width="10%">Satuan</th>
                    <th width="45%">Jenis Barang</th>
                    <th width="15%" class="text-right">Harga Satuan</th>
                    <th width="15%" class="text-right">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->volume }}</td>
                    <td class="text-center">{{ $item->satuan ?? '-' }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div class="po-summary">
        <table class="po-summary-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</td>
            </tr>
            @if($purchaseOrder->discount > 0)
            <tr>
                <td class="label">Diskon</td>
                <td class="value">- Rp {{ number_format($purchaseOrder->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($purchaseOrder->ppn > 0)
            <tr>
                <td class="label">PPN (11%)</td>
                <td class="value">+ Rp {{ number_format($purchaseOrder->ppn, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td class="label grand-total">GRAND TOTAL</td>
                <td class="value grand-total">Rp {{ number_format($purchaseOrder->grand_total, 0, ',', '.') }}</td>
            </tr>
        </table>
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
        <div class="d-flex gap-2">
            <a href="{{ route('purchase-orders.preview_pdf', $purchaseOrder->id) }}" target="_blank" class="btn btn-warning">
                <i class="bi bi-eye"></i> Preview PDF
            </a>
            <a href="{{ route('purchase-orders.export_pdf', $purchaseOrder->id) }}" class="btn btn-danger">
                <i class="bi bi-download"></i> Download PDF
            </a>
            <a href="{{ route('purchase-orders.print', $purchaseOrder->id) }}" target="_blank" class="btn btn-info">
                <i class="bi bi-printer"></i> Print
            </a>
            <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection
