<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi {{ $kwitansi->nomor_kwitansi }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8.5pt;
            line-height: 1.35;
            margin: 0;
            padding: 12px 18px;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid black;
            padding-bottom: 4px;
            margin-bottom: 14px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 42px;
            text-align: center;
            vertical-align: middle;
            padding-right: 8px;
        }
        .header-logo img {
            width: 38px;
        }
        .header-content {
            vertical-align: middle;
            text-align: left;
        }
        .header-content h1 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
        }
        .header-content h3 {
            margin: 1px 0;
            font-size: 6.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-footer {
            font-size: 6pt;
            margin-top: 2px;
            font-weight: bold;
            width: 100%;
        }
        .header-footer-left {
            float: left;
        }
        .header-footer-right {
            float: right;
        }

        .header-title {
            width: 130px;
            vertical-align: middle;
            text-align: center;
            border-left: 1px solid black;
            padding-left: 10px;
        }
        .title-kwitansi {
            font-size: 15pt;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .nomor-kwitansi {
            font-size: 7.5pt;
            margin-top: 2px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 8px;
        }
        .detail-table td {
            vertical-align: top;
            padding: 3px 0;
        }
        .detail-label {
            width: 105px;
        }
        .detail-colon {
            width: 8px;
        }

        .ttd {
            font-size: 8.5pt;
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

    $fmtDate = \Carbon\Carbon::parse($kwitansi->tanggal)->locale('id')->translatedFormat('d F Y');
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
                <td class="header-title">
                    <div class="title-kwitansi">KWITANSI</div>
                    <div class="nomor-kwitansi">No : {{ $kwitansi->nomor_kwitansi }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="detail-table">
        <tr>
            <td class="detail-label">Sudah diterima dari</td>
            <td class="detail-colon">:</td>
            <td><strong>{{ $kwitansi->customer->nama_instansi ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="detail-label">Uang sejumlah</td>
            <td class="detail-colon">:</td>
            <td>{{ ucfirst(terbilangKwitansi($kwitansi->jumlah)) }} rupiah</td>
        </tr>
        <tr>
            <td class="detail-label">Untuk pembayaran</td>
            <td class="detail-colon">:</td>
            <td>{{ $kwitansi->untuk_pembayaran ?? '-' }}</td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 10px;">
        <tr>
            <td style="text-align: right; vertical-align: middle;">
                <table cellpadding="0" cellspacing="0" style="width: auto; border-collapse: collapse; margin-left: auto;">
                    <tr>
                        <td style="padding: 0; padding-right: 15px; vertical-align: middle; text-align: center;">
                            <img src="{{ $qrCode }}" alt="QR Code" style="width: 55px; height: 55px; display: block;">
                        </td>
                        <td style="padding: 0; vertical-align: middle; text-align: left;" class="ttd">
                            <p style="margin: 0 0 3px 0; font-size: 11pt;"><strong>Rp {{ number_format($kwitansi->jumlah, 0, ',', '.') }},-</strong></p>
                            <p style="margin: 0;">Jambi, {{ $fmtDate }}</p>
                            <p style="margin: 0; padding-bottom: 20px;">Hormat Kami,</p>
                            <p style="margin: 0;"><strong>CV. PERDANA INTI BERSAUDARA</strong></p>
                            <p style="margin: 0;"><strong><u>Erwin Darmawan</u></strong></p>
                            <p style="margin: 0;">Direktur</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
