<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->nomor_invoice }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }

        .header {
            width: 100%;
            margin-bottom: 5px;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
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
            padding-top: 25px;
        }
        .header-logo img {
            width: 80px;
        }
        .header-content {
            vertical-align: middle;
            padding-top: 5px;
            text-align: center;
        }
        .header-content h1 {
            margin: 0;
            font-size: 22pt;
            font-weight: bold;
        }
        .header-content h3 {
            margin: 3px 0;
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-footer {
            font-size: 8.5pt;
            margin-top: 5px;
            padding-top: 2px;
            font-weight: bold;
            width: 100%;
        }
        .header-footer-left {
            float: left;
        }
        .header-footer-right {
            float: right;
        }

        .info-table {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .info-table td {
            vertical-align: top;
        }
        .date-right {
            text-align: right;
            padding-bottom: 10px;
        }

        .title-invoice {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0;
        }

        .kepada {
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-items th, .table-items td {
            border: 1px solid black;
            padding: 6px 8px;
            vertical-align: top;
        }
        .table-items th {
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .item-desc {
            white-space: pre-line;
            margin-top: 3px;
        }

        .footer-info {
            line-height: 1.4;
        }
        .keterangan-title {
            margin-bottom: 5px;
        }
        .bank-info {
            margin-top: 15px;
        }
        .bank-info p {
            margin: 2px 0;
            font-weight: bold;
        }

        .terbilang {
            margin-top: 15px;
            font-style: italic;
        }

        .ttd {
            margin-top: 40px;
            text-align: right;
        }
        .ttd p {
            margin: 2px 0;
        }
    </style>
</head>
<body>

@php
    $path = public_path('style/assets/img/pib-logo.png');
    if(!file_exists($path)) {
        $path = public_path('style/assets/img/PIBnew.png');
    }
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_exists($path) ? file_get_contents($path) : '';
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $fmtDate = \Carbon\Carbon::parse($invoice->tanggal)->locale('id')->translatedFormat('d F Y');

    function terbilang($angka) {
        $angka = abs((int)$angka);
        $bilangan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        $hasil = '';
        if ($angka < 12) {
            $hasil = $bilangan[$angka];
        } elseif ($angka < 20) {
            $hasil = $bilangan[$angka - 10] . ' belas';
        } elseif ($angka < 100) {
            $hasil = $bilangan[(int)($angka / 10)] . ' puluh ' . $bilangan[$angka % 10];
        } elseif ($angka < 200) {
            $hasil = 'seratus ' . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $hasil = $bilangan[(int)($angka / 100)] . ' ratus ' . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $hasil = 'seribu ' . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $sisa = $angka % 1000;
            $hasil = terbilang((int)($angka / 1000)) . ' ribu' . ($sisa > 0 ? ' ' . terbilang($sisa) : '');
        } elseif ($angka < 1000000000) {
            $sisa = $angka % 1000000;
            $hasil = terbilang((int)($angka / 1000000)) . ' juta' . ($sisa > 0 ? ' ' . terbilang($sisa) : '');
        } elseif ($angka < 1000000000000) {
            $sisa = $angka % 1000000000;
            $hasil = terbilang((int)($angka / 1000000000)) . ' miliar' . ($sisa > 0 ? ' ' . terbilang($sisa) : '');
        } else {
            $hasil = '~';
        }
        return trim($hasil) ?: 'nol';
    }
@endphp

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
                        <span class="header-footer-left">Jl. Kepodang 1 N0. 205 RT 24 Kel. Andil Jaya Jambi HP. 0852 6305 6505</span>
                        <span class="header-footer-right">E-mail : perdanaintibersaudara@gmail.com</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="padding: 0 35px 15px 35px;">

    <div class="title-invoice">INVOICE</div>

    <table class="info-table">
        <tr>
            <td width="110">Nomor Invoice</td>
            <td width="10">:</td>
            <td>{{ $invoice->nomor_invoice }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ $fmtDate }}</td>
        </tr>
    </table>

    <div class="kepada">
        <strong>Kepada Yth:</strong><br>
        <strong>{{ $invoice->customer->nama_instansi }}</strong><br>
        @if($invoice->customer->alamat)
            {{ $invoice->customer->alamat }}
        @endif
    </div>

    <table class="table-items">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Deskripsi Pekerjaan / Barang</th>
                <th width="17%">Tgl Kegiatan</th>
                <th width="8%">Volume</th>
                <th width="20%">Harga Satuan</th>
                <th width="20%">Jumlah Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedItems = $invoice->items->sortBy('group_no')->groupBy('group_no');
                $groupSeq = 1;
            @endphp
            @foreach($groupedItems as $groupNo => $items)
                @foreach($items as $itemIndex => $item)
                <tr>
                    @if($itemIndex === 0)
                        <td rowspan="{{ count($items) }}" style="text-align: center; vertical-align: middle;">{{ $groupSeq++ }}</td>
                    @endif
                    <td>
                        @if(!empty($item->nama_item))
                            <strong>{{ $item->nama_item }}</strong><br>
                        @endif
                        <div class="item-desc">{!! nl2br(e($item->deskripsi)) !!}</div>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">{{ $item->tanggal_kegiatan ? \Carbon\Carbon::parse($item->tanggal_kegiatan)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                    <td style="text-align: center; vertical-align: middle;">{{ $item->volume }} {{ $item->satuan ?? '' }}</td>
                    <td style="text-align: center; vertical-align: middle;">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: center; vertical-align: middle;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right" style="font-weight: bold;"><strong>TOTAL</strong></td>
                <td class="text-right" style="font-weight: bold;"><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="6" style="border: none; padding-top: 8px; font-style: italic;">
                    <strong>Terbilang :</strong> {{ ucfirst(terbilang($invoice->total)) }} rupiah
                </td>
            </tr>
        </tfoot>
    </table>

    @if(!empty($invoice->catatan))
    <div class="footer-info">
        <div class="keterangan-title">Keterangan :</div>
        <div style="padding-left: 20px;">{!! nl2br(e($invoice->catatan)) !!}</div>
    </div>
    @endif

    <div class="bank-info">
        <p style="text-decoration: underline; font-weight: bold;">Pembayaran Melalui</p>
        <p>Bank BCA No Rekening 619-801-2191</p>
        <p>An. CV. Perdana Inti Bersaudara</p>
    </div>

    <table style="width: 100%; margin-top: 40px;">
        <tr>
            <td style="text-align: right; vertical-align: middle;">
                <table cellpadding="0" cellspacing="0" style="width: auto; border-collapse: collapse; margin-left: auto;">
                    <tr>
                        <td style="padding: 0; padding-right: 15px; vertical-align: middle; text-align: center;">
                            <img src="{{ $qrCode }}" alt="QR Code" style="width: 100px; height: 100px; display: block;">
                        </td>
                        <td style="padding: 0; vertical-align: middle; text-align: left;">
                            <div class="ttd" style="margin-top: 0; text-align: left;">
                                <p style="margin: 0; padding-bottom: 10px;">Ditandatangani secara elektronik oleh:</p>
                                <p style="margin: 0;"><strong>CV. PERDANA INTI BERSAUDARA</strong></p>
                                @if($invoice->customer->email !== 'info.jmb@rsrapha.com')
                                <p style="margin: 0;"><strong>Erwin Darmawan</strong></p>
                                @endif
                                <p style="margin: 0;">Direktur</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</div>

</body>
</html>
