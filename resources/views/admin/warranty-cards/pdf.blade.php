<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Garansi {{ $warrantyCard->nomor_kartu }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            padding: 25px 30px;
        }

        .card-border {
            border: 3px solid #000;
            padding: 25px 30px;
            min-height: 700px;
        }

        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
            padding-right: 12px;
        }
        .header-logo img { width: 70px; }
        .header-content {
            vertical-align: middle;
            text-align: center;
        }
        .header-content h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            line-height: 1.1;
        }
        .header-content h3 {
            font-size: 10pt;
            font-weight: bold;
            margin: 2px 0;
            letter-spacing: 0.5px;
        }
        .header-footer {
            font-size: 7pt;
            font-weight: bold;
            margin-top: 2px;
        }
        .header-line {
            border: none;
            border-top: 3px solid #000;
            margin: 6px 0 15px 0;
        }

        /* Title */
        .card-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        /* Form Fields */
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .form-table td {
            padding: 10px 0;
            vertical-align: bottom;
            border-bottom: 1px dotted #999;
        }
        .form-table .label {
            font-weight: bold;
            width: 160px;
            white-space: nowrap;
        }
        .form-table .value {
            padding-left: 10px;
            min-width: 300px;
        }

        /* Garansi Text */
        .garansi-info {
            text-align: justify;
            margin-bottom: 20px;
            font-size: 10.5pt;
            line-height: 1.5;
        }
        .garansi-info strong {
            text-decoration: underline;
        }

        /* Exclusion List */
        .exclusion-list {
            margin: 10px 0 25px 20px;
            font-size: 10pt;
            line-height: 1.6;
        }
        .exclusion-list li {
            margin-bottom: 4px;
        }

        /* Signature Section */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 15px;
        }
        .signature-label {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature-name {
            font-size: 10pt;
            color: #333;
            margin-top: 5px;
        }
        .signature-line {
            width: 180px;
            border-bottom: 1px solid #000;
            margin: 60px auto 8px auto;
        }
        .signature-sublabel {
            font-size: 8pt;
            color: #666;
            font-style: italic;
        }

        /* Footer */
        .card-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            color: #666;
            text-align: center;
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

        $fmtInstalasi = \Carbon\Carbon::parse($warrantyCard->tgl_instalasi)->locale('id')->translatedFormat('d F Y');
        $fmtBerlaku = \Carbon\Carbon::parse($warrantyCard->tgl_instalasi)->addYear()->locale('id')->translatedFormat('d F Y');
    @endphp

    <div class="card-border">

        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @if(file_exists($path))
                        <img src="{{ $base64 }}" alt="Logo">
                    @else
                        <h1 style="font-size:20pt;">PIB</h1>
                    @endif
                </td>
                <td class="header-content">
                    <h1>CV. PERDANA INTI BERSAUDARA</h1>
                    <h3>RADIOLOGI - SERVICE - SPAREPART - TIMBAL - ACCESSORIES</h3>
                    <div class="header-footer">
                        Jl. Kepodang 1 No. 205 RT 24 Kel. Andil Jaya Jambi &nbsp;&nbsp;|&nbsp;&nbsp; HP. 0852 6305 6505 &nbsp;&nbsp;|&nbsp;&nbsp; perdanaintibersaudara@gmail.com
                    </div>
                </td>
            </tr>
        </table>
        <hr class="header-line">

        <!-- Title -->
        <div class="card-title">KARTU GARANSI</div>

        <!-- Form Fields -->
        <table class="form-table">
            <tr>
                <td class="label">Nama Alat</td>
                <td class="value">: &nbsp; {{ $warrantyCard->nama_alat }}</td>
            </tr>
            <tr>
                <td class="label">Type Alat</td>
                <td class="value">: &nbsp; {{ $warrantyCard->type_alat }}</td>
            </tr>
            <tr>
                <td class="label">Nama RS / Klinik</td>
                <td class="value">: &nbsp; {{ $warrantyCard->nama_rs_klinik }}</td>
            </tr>
            <tr>
                <td class="label">Tgl. Instalasi</td>
                <td class="value">: &nbsp; {{ $fmtInstalasi }}</td>
            </tr>
        </table>

        <!-- Garansi Info -->
        <div class="garansi-info">
            Garansi berlaku <strong>1 (satu) tahun</strong> sejak tanggal pemasangan (instalasi) yaitu tanggal <strong>{{ $fmtInstalasi }}</strong> s.d. <strong>{{ $fmtBerlaku }}</strong>.
        </div>

        <div class="garansi-info">
            Garansi <strong>tidak berlaku</strong> apabila:
        </div>

        <ol class="exclusion-list">
            <li>Kerusakan akibat terjatuh, kebakaran, bencana alam (<em>force majeure</em>).</li>
            <li>Alat pernah diperbaiki/dibuka oleh pihak yang tidak berwenang.</li>
            <li>Pemakaian tidak sesuai prosedur.</li>
        </ol>

        @if($warrantyCard->catatan)
        <div style="margin-bottom: 20px; padding: 10px; border: 1px dashed #999; font-size: 9pt;">
            <strong>Catatan:</strong> {!! nl2br(e($warrantyCard->catatan)) !!}
        </div>
        @endif

        <!-- Signature Section -->
        <table style="width: 100%; margin-top: 40px;">
            <tr>
                <!-- TTD Pembeli -->
                <td style="width: 50%; vertical-align: top; text-align: center; padding: 0 15px;">
                    <p style="font-size: 10pt; font-weight: bold; margin-bottom: 5px;">Tanda Tangan Pembeli</p>
                    <div style="width: 180px; border-bottom: 1px solid #000; margin: 70px auto 8px auto;"></div>
                    <p style="font-size: 8pt; color: #666; font-style: italic; margin: 0;">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</p>
                </td>
                <!-- TTD CV dengan QR Code -->
                <td style="width: 50%; vertical-align: top; text-align: center; padding: 0 15px;">
                    <p style="font-size: 10pt; font-weight: bold; margin-bottom: 5px;">CV. PERDANA INTI BERSAUDARA</p>
                    @if(isset($qrCode))
                    <table cellpadding="0" cellspacing="0" style="width: auto; border-collapse: collapse; margin: 0 auto;">
                        <tr>
                            <td style="padding: 0; padding-right: 12px; vertical-align: middle; text-align: center;">
                                <img src="{{ $qrCode }}" alt="QR Code" style="width: 80px; height: 80px; display: block;">
                            </td>
                            <td style="padding: 0; vertical-align: middle; text-align: left;">
                                <p style="margin: 0; font-size: 9pt;">Hormat Kami,</p>
                                <br>
                                <p style="margin: 0; font-size: 10pt;"><strong>{{ $warrantyCard->verifikator ?: 'Erwin Darmawan' }}</strong></p>
                                <p style="margin: 0; font-size: 9pt; color: #666;">Direktur</p>
                            </td>
                        </tr>
                    </table>
                    @else
                    <div style="width: 180px; border-bottom: 1px solid #000; margin: 70px auto 8px auto;"></div>
                    <p style="font-size: 10pt;"><strong>{{ $warrantyCard->verifikator ?: 'Erwin Darmawan' }}</strong></p>
                    <p style="font-size: 9pt; color: #666;">Direktur</p>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="card-footer">
            Dokumen ini merupakan bukti garansi resmi dari <strong>CV. Perdana Inti Bersaudara</strong> &mdash; Nomor: {{ $warrantyCard->nomor_kartu }}
        </div>

    </div>

</body>
</html>
