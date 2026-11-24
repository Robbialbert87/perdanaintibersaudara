<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $surat->judul }} {{ $surat->nomor_surat }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 25px 35px;
            padding: 0;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid black;
            padding-bottom: 5px;
        }
        .header-table { width: 100%; border-collapse: collapse; }
        .header-logo {
            width: 90px;
            text-align: center;
            vertical-align: middle;
            padding-right: 15px;
            padding-top: 10px;
        }
        .header-logo img { width: 80px; }
        .header-content { vertical-align: middle; text-align: center; }
        .header-content h1 { margin: 0; font-size: 22pt; font-weight: bold; line-height: 1.1; }
        .header-content h3 { margin: 1px 0; font-size: 12pt; font-weight: bold; letter-spacing: 0.5px; }
        .header-footer { font-size: 7.5pt; margin-top: 1px; font-weight: bold; text-align: center; }
        .date-right { text-align: right; margin-bottom: 10px; }
        .surat-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 15px;
        }
        .info-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .info-label { width: 80px; font-weight: bold; }
        .info-separator { width: 15px; }
        .tujuan { margin-bottom: 15px; }
        .tujuan p { margin: 0; }
        .isi { text-align: justify; line-height: 1.7; }
        .isi p { margin: 0 0 10px 0; }
    </style>
</head>
<body>
@php
    $path = public_path('style/assets/img/pib-logo.png');
    if (!file_exists($path)) {
        $path = public_path('style/assets/img/PIBnew.png');
    }
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_exists($path) ? file_get_contents($path) : '';
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $fmtDate = \Carbon\Carbon::parse($surat->tanggal)->locale('id')->translatedFormat('d F Y');
    $jumlahLampiran = is_array($surat->lampiran) ? count($surat->lampiran) : 0;
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

    <div class="date-right">Jambi, {{ $fmtDate }}</div>

    <div class="surat-title">{{ $surat->judul }}</div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nomor</td>
            <td class="info-separator">:</td>
            <td>{{ $surat->nomor_surat }}</td>
        </tr>
        <tr>
            <td class="info-label">Perihal</td>
            <td class="info-separator">:</td>
            <td>{{ $surat->perihal }}</td>
        </tr>
        <tr>
            <td class="info-label">Lampiran</td>
            <td class="info-separator">:</td>
            <td>{{ $jumlahLampiran ? $jumlahLampiran.' berkas' : '-' }}</td>
        </tr>
    </table>

    <div class="tujuan">
        <p><strong>Kepada:</strong></p>
        <p><strong>{{ $surat->tujuan_nama }}</strong></p>
        @if($surat->tujuan_alamat)
        <p>{{ $surat->tujuan_alamat }}</p>
        @endif
    </div>

    <div class="isi">
        {!! nl2br(e($surat->isi)) !!}
    </div>

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
                                <p style="margin: 0;"><strong>{{ $surat->nama_penandatangan }}</strong></p>
                                <p style="margin: 0;">{{ $surat->jabatan_penandatangan }}</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @endif

@if($jumlahLampiran > 0)
    @foreach(array_chunk($surat->lampiran, 2) as $chunk)
        <div style="page-break-before: always; padding: 25px 30px;">
            <h2 style="text-align: center; text-decoration: underline; margin-bottom: 20px; font-size: 14pt;">Lampiran Surat</h2>
            @foreach($chunk as $imgPath)
                @php
                    $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($imgPath);
                    $imgBase64 = '';
                    if (file_exists($fullPath)) {
                        $imgType = pathinfo($fullPath, PATHINFO_EXTENSION) ?: 'jpg';
                        $imgBase64 = 'data:image/'.$imgType.';base64,'.base64_encode(file_get_contents($fullPath));
                    }
                @endphp
                @if($imgBase64)
                    <div style="text-align: center; margin-bottom: 15px;">
                        <img src="{{ $imgBase64 }}" style="max-width: 100%; max-height: 360px;">
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach
@endif

</body>
</html>
