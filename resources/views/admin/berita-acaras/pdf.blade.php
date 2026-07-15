<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara {{ $beritaAcara->nomor_surat }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
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

        /* Info Table */
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

        /* Section Title */
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Pihak Section */
        .pihak-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .pihak-table td {
            vertical-align: top;
            padding: 4px 0;
        }
        .pihak-label {
            width: 130px;
            font-weight: bold;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .items-table th, .items-table td {
            border: 1px solid black;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .items-table th {
            text-align: center;
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .text-center { text-align: center; }

        /* Paragraph */
        .paragraf {
            text-align: justify;
            margin-bottom: 15px;
            line-height: 1.6;
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

    $fmtDate = \Carbon\Carbon::parse($beritaAcara->tanggal)->locale('id')->translatedFormat('d F Y');
    $dayName = \Carbon\Carbon::parse($beritaAcara->tanggal)->locale('id')->translatedFormat('l');

    $defaultClosing = 'Demikian Berita Acara Serah Terima dan Uji Fungsi ini kami buat agar dapat dipergunakan sebagaimana mestinya, atas kerjasama dan kepercayaan nya kami ucapkan terima kasih.';
    $closingText = $beritaAcara->closing_text ?: $defaultClosing;
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
        <td width="70">No</td>
        <td width="10">:</td>
        <td>{{ $beritaAcara->nomor_surat }}</td>
    </tr>
    <tr>
        <td>Perihal</td>
        <td>:</td>
        <td>Berita Acara Serah Terima dan Uji Fungsi</td>
    </tr>
</table>

<!-- Paragraf Dinamis -->
<div class="paragraf">
    Pada hari ini, {{ $dayName }} tanggal {{ \Carbon\Carbon::parse($beritaAcara->tanggal)->locale('id')->translatedFormat('d F Y') }} telah dilakukan {{ $beritaAcara->kegiatan }} di {{ $beritaAcara->lokasi }} dan dinyatakan berfungsi dengan baik, yang selanjutnya alat tersebut diserahkan kepada :
</div>

<!-- Pihak Penyerah & Penerima -->
<div class="section-title">Pihak yang menyerahkan :</div>
<table class="pihak-table">
    <tr>
        <td class="pihak-label" width="70">Nama</td>
        <td width="10">:</td>
        <td>{{ $beritaAcara->pihak_penyerah_nama }}</td>
    </tr>
    @if($beritaAcara->pihak_penyerah_alamat)
    <tr>
        <td class="pihak-label">Alamat</td>
        <td>:</td>
        <td>{{ $beritaAcara->pihak_penyerah_alamat }}</td>
    </tr>
    @endif
</table>

<div style="margin-top: 10px;"></div>

<div class="section-title">Pihak yang menerima :</div>
<table class="pihak-table">
    <tr>
        <td class="pihak-label" width="70">Nama</td>
        <td width="10">:</td>
        <td>{{ $beritaAcara->pihak_penerima_nama }}</td>
    </tr>
    @if($beritaAcara->pihak_penerima_alamat)
    <tr>
        <td class="pihak-label">Alamat</td>
        <td>:</td>
        <td>{{ $beritaAcara->pihak_penerima_alamat }}</td>
    </tr>
    @endif
</table>

<!-- Items Table -->
<div class="section-title">Daftar Produk :</div>
<table class="items-table">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="55%">Nama Produk</th>
            <th width="15%">Quantity</th>
            <th width="25%">Keterangan Berfungsi/Tidak Berfungsi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($beritaAcara->items as $idx => $item)
        <tr>
            <td class="text-center">{{ $idx + 1 }}</td>
            <td>{{ $item->nama_produk }}</td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td>&nbsp;</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Penutup -->
<div class="paragraf" style="margin-top: 10px;">
    {!! nl2br(e($closingText)) !!}
</div>

<!-- Tanda Tangan: Kiri = Penerima (manual), Kanan = Penyerah (QR Code) -->
<table style="width: 100%; margin-top: 30px;">
    <tr>
        <!-- Kiri: Pihak Penerima (tanda tangan manual) -->
        <td style="width: 50%; vertical-align: top; padding-right: 20px;">
            <div style="text-align: left;">
                <p style="margin: 0;">Yang Menerima,</p>
                <br><br>
                <p style="margin: 0; border-bottom: 1px solid black; width: 180px; display: inline-block;">&nbsp;</p>
                <p style="margin: 4px 0 0 0;"><strong>( {{ $beritaAcara->pihak_penerima_nama }} )</strong></p>
            </div>
        </td>
        <!-- Kanan: Pihak Penyerah (QR Code) -->
        <td style="width: 50%; vertical-align: top; text-align: right;">
            <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-left: auto;">
                <tr>
                    <td style="padding: 0; padding-right: 15px; vertical-align: middle; text-align: center;">
                        <img src="{{ $qrCode }}" alt="QR Code" style="width: 100px; height: 100px; display: block;">
                    </td>
                    <td style="padding: 0; vertical-align: middle; text-align: left; white-space: nowrap;">
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
