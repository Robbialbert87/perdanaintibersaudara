<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi {{ $kwitansi->nomor_kwitansi }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 20px 30px;
            padding: 0;
        }

        /* Header / Kop Surat */
        .header {
            width: 100%;
            margin-bottom: 2px;
            border-bottom: 3px solid black;
            padding-bottom: 3px;
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

        /* Informasi Surat */
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

        /* Judul Dokumen */
        .title-kwitansi {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 1px;
            margin: 5px 0 18px;
        }

        /* Tabel Kwitansi */
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-items th, .table-items td {
            border: 1px solid black;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .table-items .detail-label {
            border-right: none;
            width: 25%;
        }
        .table-items .detail-colon {
            border-left: none;
            border-right: none;
            width: 4%;
            text-align: center;
        }
        .table-items .detail-value {
            border-left: none;
        }
        .label-en {
            font-style: italic;
            font-size: 10pt;
        }

        /* Nominal di bawah tabel - lebar otomatis mengikuti teks */
        .nominal-table {
            margin: 35px 0 0 15px;
            border-collapse: collapse;
        }
        .nominal-cell {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 5px 4px;
            font-size: 18pt;
            font-weight: bold;
        }

        /* Catatan pembayaran */
        .catatan {
            margin-top: 25px;
            font-size: 10pt;
            line-height: 1.5;
        }
        .catatan p {
            margin: 2px 0;
        }
        .catatan-title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .catatan-bank {
            margin-left: 20px;
        }

        .ttd {
            font-size: 11pt;
        }
    </style>
</head>
<body>

@php
    // Prepare Logo
    $path = public_path('style/assets/img/pib-logo.png');
    if(!file_exists($path)) {
        $path = public_path('style/assets/img/PIBnew.png');
    }
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_exists($path) ? file_get_contents($path) : '';
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    // Format Date
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
                        Jl. Kepodang 1 N0. 205 RT 24 Kel. Andil Jaya Jambi &nbsp;&nbsp; HP. 0852 6305 6505 &nbsp;&nbsp; E-mail : perdanaintibersaudara@gmail.com
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="date-right">
        Jambi, {{ $fmtDate }}
    </div>

    <div class="title-kwitansi">KWITANSI</div>

    <table class="info-table">
        <tr>
            <td width="30">No</td>
            <td width="10">:</td>
            <td>{{ $kwitansi->nomor_kwitansi }}</td>
        </tr>
    </table>

    <table class="table-items">
        <tr>
            <td class="detail-label">Sudah Terima Dari<br><span class="label-en">Received From</span></td>
            <td class="detail-colon">:</td>
            <td class="detail-value"><strong>{{ $kwitansi->customer->nama_instansi ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="detail-label">Banyak Uang<br><span class="label-en">Amount Received</span></td>
            <td class="detail-colon">:</td>
            <td class="detail-value">{{ ucfirst(terbilangKwitansi($kwitansi->jumlah)) }} rupiah</td>
        </tr>
        <tr>
            <td class="detail-label">Untuk Pembayaran<br><span class="label-en">In Payment Of</span></td>
            <td class="detail-colon">:</td>
            <td class="detail-value">{{ $kwitansi->untuk_pembayaran ?? '-' }}</td>
        </tr>
    </table>

    <table class="nominal-table">
        <tr>
            <td class="nominal-cell"><em>JUMLAH</em> : Rp. {{ number_format($kwitansi->jumlah, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if(!empty($kwitansi->catatan))
        <div class="catatan">
            <p class="catatan-title">Catatan :</p>
            {!! nl2br(e($kwitansi->catatan)) !!}
        </div>
    @endif

    <table style="width: 100%; margin-top: 30px;">
        <tr>
            <td style="text-align: right; vertical-align: middle;">
                <table cellpadding="0" cellspacing="0" style="width: auto; border-collapse: collapse; margin-left: auto;">
                    <tr>
                        <td style="padding: 0; padding-right: 15px; vertical-align: middle; text-align: center;">
                            <img src="{{ $qrCode }}" alt="QR Code" style="width: 100px; height: 100px; display: block;">
                        </td>
                        <td style="padding: 0; vertical-align: middle; text-align: left;" class="ttd">
                            <p style="margin: 0;">Hormat Kami,</p>
                            <br>
                            <p style="margin: 0;"><strong>CV. PERDANA INTI BERSAUDARA</strong></p>
                            <p style="margin: 0;"><strong>Erwin Darmawan</strong></p>
                            <p style="margin: 0;">Direktur</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
