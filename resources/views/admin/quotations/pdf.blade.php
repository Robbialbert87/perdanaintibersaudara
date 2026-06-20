<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penawaran {{ $quotation->nomor_surat }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        
        /* Header / Kop Surat */
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

        /* Kepada Yth */
        .kepada {
            margin-bottom: 15px;
            line-height: 1.4;
        }

        /* Isi Surat */
        .isi-surat {
            text-align: justify;
            text-indent: 30px;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .pembuka {
            margin-bottom: 5px;
        }

        /* Tabel Penawaran */
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
        .table-items th {
            text-align: center;
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .item-desc {
            word-break: break-word;
        }

        /* Keterangan & Bank */
        .footer-info {
            line-height: 1.4;
        }
        .keterangan-title {
            margin-bottom: 5px;
        }
        .keterangan-list {
            margin-top: 0;
            padding-left: 20px;
        }
        .bank-info {
            margin-top: 15px;
        }
        .bank-info p {
            margin: 2px 0;
            font-weight: bold;
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
        $fmtDate = \Carbon\Carbon::parse($quotation->tanggal)->locale('id')->translatedFormat('d F Y');

        // Parse Perihal
        $perihalArray = is_array($quotation->perihal) ? $quotation->perihal : (json_decode($quotation->perihal, true) ?? [$quotation->perihal]);
        $perihalText = implode(', ', $perihalArray);
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

    <div class="date-right">
        Jambi, {{ $fmtDate }}
    </div>

    <table class="info-table">
        <tr>
            <td width="70">No</td>
            <td width="10">:</td>
            <td>{{ $quotation->nomor_surat }}</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td>Surat Penawaran</td>
        </tr>
    </table>

    <div class="kepada">
        <strong>Kepada Yth:</strong><br>
        <strong>Direktur</strong><br>
        <strong>{{ $quotation->customer->nama_instansi }}</strong><br>
        <strong>Di</strong><br>
        <span style="margin-left: 20px;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $quotation->customer->kota ?? 'Tempat' }}</strong></span>
    </div>

    <div class="pembuka">
        Dengan Hormat,
    </div>
    
    <div class="isi-surat">
        Dengan ini kami (PIB) Perdana Inti Bersaudara yang berkedudukan di Jambi ingin menawarkan produk berupa {{ $perihalText }} kepada {{ $quotation->customer->nama_instansi }}, adapun harga dan spesifikasi yang ditawarkan adalah sebagai berikut:
    </div>

    <table class="table-items">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Jenis Kegiatan</th>
                <th width="20%" class="text-center">Volume</th>
                <th width="20%" class="text-center">Harga Satuan</th>
                <th width="20%" class="text-center">Jumlah Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    @if(!empty($item->nama_item))
                        <strong>{{ $item->nama_item }}</strong><br>
                    @endif
                    <div class="item-desc">{!! nl2br(e($item->deskripsi)) !!}</div>
                </td>
                <td class="text-center">{{ $item->volume }} {{ $item->satuan ?? '' }}</td>
                <td class="text-center">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-center">Rp {{ number_format((float) $item->volume * $item->harga_satuan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right" style="font-weight: bold; border: 1px solid black; padding: 6px 8px;"><strong>TOTAL</strong></td>
                <td class="text-center" style="font-weight: bold; border: 1px solid black; padding: 6px 8px;"><strong>Rp {{ number_format($quotation->items->sum(fn($item) => (float) $item->volume * $item->harga_satuan), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-info">
        <div class="keterangan-title">Keterangan :</div>
        @if(!empty($quotation->catatan))
            <div style="padding-left: 20px; white-space: pre-line;">{!! nl2br(e($quotation->catatan)) !!}</div>
        @else
            <ul class="keterangan-list">
                <li>Harga sudah termasuk ongkir, transportasi dan akomodasi</li>
                <li>Pembayaran DP 50% setelah serah terima 50%</li>
                <li>Tidak termasuk PPn</li>
                <li>Free uji paparan mandiri</li>
            </ul>
        @endif

        <div class="bank-info">
            <p style="text-decoration: underline;">Pembayaran dapat dilakukan melalui:</p>
            <p>Bank BCA</p>
            <p>No. Rekening 619 801 2733</p>
            <p>(PIB) Perdana Inti Bersaudara</p>
        </div>
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
                            <div class="ttd" style="margin-top: 0; text-align: left;">
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

    @if($quotation->tampilkan_gambar && isset($perihalImages) && count(array_filter(array_column($perihalImages, 'path'))) > 0)
    <div style="page-break-before: always; margin-top: 20px;">
        <h2 style="text-align: center; margin-bottom: 20px;">Lampiran Gambar</h2>
        <table style="width: 100%; border-collapse: collapse;">
            @foreach($perihalImages as $img)
                @if($img['path'])
                @php
                    $imgType = pathinfo($img['path'], PATHINFO_EXTENSION);
                    $imgData = file_exists($img['path']) ? file_get_contents($img['path']) : '';
                    $imgBase64 = $imgData ? 'data:image/' . $imgType . ';base64,' . base64_encode($imgData) : '';
                @endphp
                @if($imgBase64)
                <tr>
                    <td style="text-align: center; padding: 15px; border: 1px solid #ddd; margin-bottom: 15px;">
                        <p style="margin: 0 0 10px; font-weight: bold;">{{ $img['name'] }}</p>
                        <img src="{{ $imgBase64 }}" alt="{{ $img['name'] }}" style="max-width: 450px; max-height: 400px;">
                    </td>
                </tr>
                @endif
                @endif
            @endforeach
        </table>
    </div>
    @endif

</body>
</html>
