<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $purchaseOrder->nomor_surat }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #1a2332;
        }
        .page {
            width: 595.28pt;
            height: 935.43pt;
            padding: 25pt 30pt;
            margin: 0;
        }

        /* Header */
        .header {
            border-bottom: 3px double #000;
            padding-bottom: 8pt;
            margin-bottom: 10pt;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 80pt;
            text-align: center;
            vertical-align: middle;
            padding-right: 12pt;
        }
        .header-logo img {
            width: 70pt;
        }
        .header-content {
            vertical-align: middle;
            text-align: center;
        }
        .header-content h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: 800;
            color: #1a2332;
        }
        .header-content h3 {
            margin: 2pt 0;
            font-size: 9pt;
            font-weight: 700;
            color: #374151;
            letter-spacing: 1px;
        }
        .header-footer {
            font-size: 7pt;
            margin-top: 4pt;
            color: #6b7280;
            font-weight: 600;
        }

        /* Date */
        .date-right {
            text-align: right;
            padding: 8pt 0;
            font-size: 10pt;
        }

        /* Title */
        .po-title {
            text-align: center;
            padding: 10pt 0;
        }
        .po-title h2 {
            font-size: 16pt;
            font-weight: 800;
            color: #1a2332;
            letter-spacing: 2px;
            margin: 0;
        }
        .po-number {
            font-size: 10pt;
            font-weight: 700;
            color: #374151;
            margin-top: 4pt;
        }

        /* Info Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 9pt;
            margin-bottom: 12pt;
        }
        .info-table td {
            border: 1px solid #000;
            padding: 6pt 8pt;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: 700;
            width: 90pt;
            background: #f3f4f6;
        }

        /* Section Title */
        .section-title {
            font-size: 9pt;
            font-weight: 700;
            background: #e5e7eb;
            padding: 5pt 8pt;
            border: 1px solid #000;
            margin-bottom: 0;
        }

        /* Two Column */
        .two-col-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12pt;
        }
        .two-col-table td {
            border: 1px solid #000;
            padding: 0;
            vertical-align: top;
        }
        .col-title {
            font-size: 8pt;
            font-weight: 700;
            background: #e5e7eb;
            padding: 5pt 8pt;
            border-bottom: 1px solid #000;
            text-align: center;
        }
        .col-body {
            padding: 6pt 8pt;
            font-size: 9pt;
        }
        .col-body .field-row {
            margin-bottom: 4pt;
        }
        .col-body .field-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 8pt;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 9pt;
            margin-bottom: 0;
        }
        .items-table th {
            background: #e5e7eb;
            border: 1px solid #000;
            padding: 6pt 8pt;
            font-weight: 700;
            text-align: center;
            font-size: 8pt;
        }
        .items-table td {
            border: 1px solid #000;
            padding: 5pt 8pt;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Summary */
        .summary-table {
            width: 220pt;
            border-collapse: collapse;
            margin-left: auto;
            font-size: 9pt;
        }
        .summary-table td {
            padding: 4pt 8pt;
        }
        .summary-table .label {
            text-align: right;
            font-weight: 600;
            color: #6b7280;
        }
        .summary-table .value {
            text-align: right;
            font-weight: 700;
            color: #1a2332;
        }
        .summary-table .grand-total {
            font-size: 11pt;
            font-weight: 800;
            border-top: 2pt solid #000;
            padding-top: 6pt;
        }

        /* Signature */
        .signature-section {
            margin-top: 25pt;
            text-align: right;
            padding-right: 30pt;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 150pt;
        }
        .signature-box .title {
            font-weight: 700;
            margin-bottom: 50pt;
        }
        .signature-box .name {
            font-weight: 700;
        }
        .signature-box .position {
            color: #6b7280;
            font-size: 8pt;
        }

        /* QR Code */
        .qr-section {
            margin-top: 15pt;
        }
        .qr-table {
            width: auto;
            border-collapse: collapse;
            margin-left: auto;
        }
        .qr-table td {
            padding: 0;
            vertical-align: middle;
        }
        .qr-table .qr-img {
            padding-right: 10pt;
        }
        .qr-table img {
            width: 70pt;
            height: 70pt;
        }
        .qr-table .qr-text {
            text-align: left;
            font-size: 9pt;
        }
        .qr-table .qr-text p {
            margin: 2pt 0;
        }
    </style>
</head>
<body>

@php
    $path = public_path('style/assets/img/pib-logo.png');
    if(!file_exists($path)) $path = public_path('style/assets/img/PIBnew.png');
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_exists($path) ? file_get_contents($path) : '';
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $fmtDate = \Carbon\Carbon::parse($purchaseOrder->tanggal)->locale('id')->translatedFormat('d F Y');
@endphp

<div class="page">
    <!-- Header -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @if(file_exists($path))
                        <img src="{{ $base64 }}" alt="Logo">
                    @else
                        <h1 style="font-size:24pt;">PIB</h1>
                    @endif
                </td>
                <td class="header-content">
                    <h1>CV. PERDANA INTI BERSAUDARA</h1>
                    <h3>RADIOLOGI - SERVICE - SPAREPART - TIMBAL - ACCESORIES</h3>
                    <div class="header-footer">
                        Jl. Kepodang 1 N0. 205 RT 24 Kel. Andil Jaya Jambi &nbsp;&nbsp; HP. 0852 6305 6505 &nbsp;&nbsp; E-mail : perdanaintibersaudara@gmail.com
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Date -->
    <div class="date-right">
        Jambi, {{ $fmtDate }}
    </div>

    <!-- Title -->
    <div class="po-title">
        <h2>PURCHASE ORDER</h2>
        <div class="po-number">{{ $purchaseOrder->nomor_surat }}</div>
    </div>

    <!-- Vendor Info -->
    <div class="section-title">KEPADA VENDOR</div>
    <table class="info-table">
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

    <!-- Two Column -->
    <table class="two-col-table">
        <tr>
            <td style="width: 50%;">
                <div class="col-title">PESANAN PEMBELIAN</div>
                <div class="col-body">
                    <div class="field-row">
                        <span class="field-label">Dipesan Oleh: </span>
                        <span>{{ $purchaseOrder->buyer_name ?? '-' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Alamat: </span>
                        <span>{{ $purchaseOrder->buyer_address ?? '-' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">CP: </span>
                        <span>{{ $purchaseOrder->buyer_cp ?? '-' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Telepon: </span>
                        <span>{{ $purchaseOrder->buyer_phone ?? '-' }}</span>
                    </div>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="col-title">ALAMAT PENGIRIMAN</div>
                <div class="col-body">
                    <div class="field-row">
                        <span class="field-label">Nama: </span>
                        <span>{{ $purchaseOrder->shipping_name ?? '-' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Alamat: </span>
                        <span>{{ $purchaseOrder->shipping_address ?? '-' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">CP: </span>
                        <span>{{ $purchaseOrder->shipping_cp ?? '-' }}</span>
                    </div>
                    <div class="field-row">
                        <span class="field-label">Telepon: </span>
                        <span>{{ $purchaseOrder->shipping_phone ?? '-' }}</span>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <div class="section-title" style="margin-bottom: 0;">DAFTAR BARANG</div>
    <table class="items-table">
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

    <!-- Summary -->
    <div style="margin-top: 12pt;">
        <table class="summary-table">
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

    <!-- QR Code Signature -->
    @if(isset($qrCode))
    <div class="qr-section">
        <table class="qr-table">
            <tr>
                <td class="qr-img">
                    <img src="{{ $qrCode }}" alt="QR Code">
                </td>
                <td class="qr-text">
                    <p>Hormat Kami,</p>
                    <br>
                    <p><strong>CV. PERDANA INTI BERSAUDARA</strong></p>
                    <p><strong>Erwin Darmawan</strong></p>
                    <p style="color: #6b7280;">Direktur</p>
                </td>
            </tr>
        </table>
    </div>
    @else
    <div class="signature-section">
        <div class="signature-box">
            <div class="title">Hormat Kami,</div>
            <div class="name">CV. PERDANA INTI BERSAUDARA</div>
            <div class="position">Direktur</div>
        </div>
    </div>
    @endif
</div>

</body>
</html>
