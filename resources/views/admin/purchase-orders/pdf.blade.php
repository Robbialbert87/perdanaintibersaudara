<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $purchaseOrder->nomor_surat }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 25px 35px;
            padding: 0;
        }

        /* Header / Kop Surat */
        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 3px solid black;
            padding-bottom: 5px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 90px;
            text-align: center;
            vertical-align: middle;
            padding-right: 15px;
            padding-top: 10px;
        }
        .header-logo img {
            width: 80px;
        }
        .header-content {
            vertical-align: middle;
            padding-top: 0;
            text-align: center;
        }
        .header-content h1 {
            margin: 0;
            font-size: 22pt;
            font-weight: bold;
            line-height: 1.1;
        }
        .header-content h3 {
            margin: 1px 0;
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-footer {
            font-size: 7.5pt;
            margin-top: 1px;
            padding-top: 0;
            font-weight: bold;
            width: 100%;
            text-align: center;
        }

        .date-right {
            text-align: right;
            padding-top: 5px;
            padding-bottom: 10px;
        }

        /* Section Info */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            vertical-align: top;
            padding: 1px 0;
        }
        .info-label {
            width: 100px;
            font-weight: bold;
        }
        .info-colon {
            width: 15px;
        }

        .section-title {
            font-weight: bold;
        }

        .vendor-section {
            margin-bottom: 15px;
            line-height: 1.8;
        }
        .vendor-section .row {
            margin: 0;
        }
        .vendor-section .label {
            display: inline-block;
            width: 80px;
        }

        .two-col-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .two-col-table td {
            border: 1px solid black;
            padding: 6px 8px;
            vertical-align: top;
            width: 50%;
        }
        .two-col-table .col-header {
            font-weight: bold;
            text-align: center;
            background: #f9f9f9;
            padding: 4px 8px;
            border-bottom: 1px solid black;
        }
        .field-row {
            margin-bottom: 3px;
            font-size: 10pt;
        }
        .field-label {
            font-weight: bold;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .items-table th {
            border: 1px solid black;
            padding: 5px 6px;
            vertical-align: middle;
            font-weight: bold;
            text-align: center;
            background: #f9f9f9;
            font-size: 9pt;
        }
        .items-table td {
            border: 1px solid black;
            padding: 4px 6px;
            vertical-align: middle;
            text-align: center;
            font-size: 10pt;
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

<!-- Header / Kop Surat -->
<div class="header">
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if(file_exists($path))
                    <img src="{{ $base64 }}" alt="Logo">
                @else
                    <h1>PIB</h1>
                @endif
            </td>
            <td class="header-content">
                <h1>CV. PERDANA INTI BERSAUDARA</h1>
                <h3>RADIOLOGI-SERVICE-SPAREPART-TIMBAL-ACCESORIES</h3>
                <div class="header-footer">
                    Jl. Kepodang 1 N0. 205 RT 24 Kel. Andil Jaya Jambi &nbsp;&nbsp; HP. 0852 6305 6505 &nbsp;&nbsp; E-mail : perdanaintibersaudara@gmail.com
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="date-right">
    Jambi, {{ $fmtDate }}
</div>

<table class="info-table">
    <tr>
        <td class="info-label">No</td>
        <td class="info-colon">:</td>
        <td>{{ $purchaseOrder->nomor_surat }}</td>
    </tr>
    <tr>
        <td>Perihal</td>
        <td>:</td>
        <td>Purchase Order</td>
    </tr>
</table>

<!-- Vendor -->
<div class="section-title">Kepada Yth:</div>
<div class="vendor-section">
    <div><span class="label">Nama</span>: {{ $purchaseOrder->vendor ?? '-' }}</div>
    @if($purchaseOrder->vendor_address)
    <div><span class="label">Alamat</span>: {{ $purchaseOrder->vendor_address }}</div>
    @endif
    @if($purchaseOrder->vendor_cp)
    <div><span class="label">CP</span>: {{ $purchaseOrder->vendor_cp }}</div>
    @endif
    @if($purchaseOrder->vendor_phone)
    <div><span class="label">Telepon</span>: {{ $purchaseOrder->vendor_phone }}</div>
    @endif
</div>

<!-- Two Column: Pesanan Pembelian & Alamat Pengiriman -->
<table class="two-col-table">
    <tr>
        <td>
            <div class="col-header">PESANAN PEMBELIAN</div>
            <div class="field-row"><span class="field-label">Nama:</span> {{ $purchaseOrder->buyer_name ?? '-' }}</div>
            <div class="field-row"><span class="field-label">Alamat:</span> {{ $purchaseOrder->buyer_address ?? '-' }}</div>
            <div class="field-row"><span class="field-label">CP:</span> {{ $purchaseOrder->buyer_cp ?? '-' }}</div>
            <div class="field-row"><span class="field-label">Telepon:</span> {{ $purchaseOrder->buyer_phone ?? '-' }}</div>
        </td>
        <td>
            <div class="col-header">ALAMAT PENGIRIMAN</div>
            <div class="field-row"><span class="field-label">Nama:</span> {{ $purchaseOrder->shipping_name ?? '-' }}</div>
            <div class="field-row"><span class="field-label">Alamat:</span> {{ $purchaseOrder->shipping_address ?? '-' }}</div>
            <div class="field-row"><span class="field-label">CP:</span> {{ $purchaseOrder->shipping_cp ?? '-' }}</div>
            <div class="field-row"><span class="field-label">Telepon:</span> {{ $purchaseOrder->shipping_phone ?? '-' }}</div>
        </td>
    </tr>
</table>

<!-- Items Table -->
<div class="section-title">Daftar Barang</div>
<table class="items-table">
    <thead>
        <tr>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Jenis Barang</th>
            <th>Harga Satuan</th>
            <th>DP %</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseOrder->items as $item)
        <tr>
            <td>{{ $item->volume }}</td>
            <td>{{ $item->satuan ?? '-' }}</td>
            <td>{{ $item->deskripsi }}</td>
            <td>Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
            <td>{{ (int)$item->dp_persentase }}%</td>
            <td>Rp{{ number_format($item->subtotal - $item->dp_nominal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        </tbody>
</table>

@if($purchaseOrder->catatan)
<div style="margin-bottom: 15px;">
    <div class="section-title">Keterangan:</div>
    <div style="line-height: 1.6;">{!! nl2br(e($purchaseOrder->catatan)) !!}</div>
</div>
@endif

@if(isset($qrCode))
<table style="width: 100%; margin-top: 30px;">
    <tr>
        <td style="text-align: right; vertical-align: middle;">
            <table cellpadding="0" cellspacing="0" style="width: auto; border-collapse: collapse; margin-left: auto;">
                <tr>
                    <td style="padding: 0; padding-right: 15px; vertical-align: middle; text-align: center;">
                        <img src="{{ $qrCode }}" alt="QR Code" style="width: 100px; height: 100px; display: block;">
                    </td>
                    <td style="padding: 0; vertical-align: middle; text-align: left;">
                        <div style="margin-top: 0; text-align: left;">
                            <p style="margin: 0;">Hormat Kami,</p>
                            <br>
                            <p style="margin: 0;"><strong>CV. PERDANA INTI BERSAUDARA</strong></p>
                            <p style="margin: 0;"><strong>Erwin Darmawan</strong></p>
                            <p style="margin: 0;">Direktur</p>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif

</body>
</html>
