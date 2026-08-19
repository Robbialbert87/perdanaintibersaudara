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
        $perihalLabel = count($perihalArray) > 1 ? 'produk/jasa' : 'produk/jasa';

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
                $sisa = $angka - 100;
                $hasil = 'seratus' . ($sisa > 0 ? ' ' . terbilang($sisa) : '');
            } elseif ($angka < 1000) {
                $sisa = $angka % 100;
                $hasil = $bilangan[(int)($angka / 100)] . ' ratus' . ($sisa > 0 ? ' ' . terbilang($sisa) : '');
            } elseif ($angka < 2000) {
                $sisa = $angka - 1000;
                $hasil = 'seribu' . ($sisa > 0 ? ' ' . terbilang($sisa) : '');
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
            <td>{{ $quotation->nomor_surat }}</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td>{{ $quotation->perihal_surat ?: 'Surat Penawaran' }}</td>
        </tr>
    </table>

    <div class="kepada">
        <strong>Kepada Yth:</strong><br>
        <strong>Direktur</strong><br>
        <strong>{{ $quotation->customer->nama_instansi }}</strong><br>
        <strong>Di</strong><br>
        <span style="margin-left: 20px;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $quotation->customer->kota ?? 'Tempat' }}</strong></span>
    </div>

    @if($quotation->kata_pengantar)
        @php
            $paragraphs = preg_split('/\n\s*\n/', trim($quotation->kata_pengantar));
        @endphp
        <div class="pembuka" style="text-align: justify; line-height: 1.6;">
            @foreach($paragraphs as $p)
                @if(trim($p))
                    <p style="text-indent: 30px; margin: 0 0 8px;">{!! nl2br(e(trim($p))) !!}</p>
                @endif
            @endforeach
        </div>
    @else
        <div class="pembuka">
            Dengan Hormat,
        </div>
        
        <div class="isi-surat">
            Dengan ini kami (PIB) Perdana Inti Bersaudara yang berkedudukan di Jambi ingin menawarkan {{ $perihalLabel }} berupa {{ $perihalText }} kepada {{ $quotation->customer->nama_instansi }}, adapun rincian harga yang ditawarkan adalah sebagai berikut:
        </div>
    @endif

    @php
        $itemCount = $quotation->items->count();
        $hasPrice = $quotation->items->contains(fn($item) => (float) $item->harga_satuan > 0);
        $hasDiskon = $quotation->items->contains(fn($item) => (float) ($item->diskon ?? 0) > 0);

        $calcSubtotal = function($item) {
            $volume = (float) $item->volume;
            $harga = (float) $item->harga_satuan;
            $diskon = (float) ($item->diskon ?? 0);
            if ($diskon > 0) {
                return $volume * $harga * (100 - $diskon) / 100;
            }
            return $volume * $harga;
        };
    @endphp
    <table class="table-items">
        <thead>
            <tr>
                @if($hasPrice && $itemCount > 1)
                <th width="5%">No</th>
                @endif
                <th width="{{ !$hasPrice ? '60%' : ($itemCount > 1 ? '30%' : '35%') }}">Jenis Kegiatan</th>
                <th width="{{ !$hasPrice ? '40%' : '15%' }}" class="text-center">Volume</th>
                @if($hasPrice)
                <th width="15%" class="text-center">Harga Satuan</th>
                @if($hasDiskon)
                <th width="10%" class="text-center">Diskon (%)</th>
                @endif
                <th width="{{ $hasDiskon ? '15%' : '20%' }}" class="text-center">{{ $hasDiskon ? 'Harga Nett' : 'Jumlah Harga' }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $index => $item)
            <tr>
                @if($hasPrice && $itemCount > 1)
                <td class="text-center">{{ $index + 1 }}</td>
                @endif
                <td>
                    @if(!empty($item->nama_item) && $item->tampilkan_label)
                        <strong>{{ $item->nama_item }}</strong><br>
                    @endif
                    <div class="item-desc">{!! nl2br(e($item->deskripsi)) !!}</div>
                </td>
                <td class="text-center">{{ $item->volume }} {{ $item->satuan ?? '' }}</td>
                @if($hasPrice)
                <td class="text-center">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                @if($hasDiskon)
                <td class="text-center">{{ $item->diskon ? rtrim(rtrim(number_format((float) $item->diskon, 2, ',', '.'), '0'), ',.') . '%' : '-' }}</td>
                @endif
                <td class="text-center">Rp {{ number_format($calcSubtotal($item), 0, ',', '.') }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
        @if($hasPrice)
        <tfoot>
            <tr>
                <td colspan="{{ ($itemCount > 1 ? 4 : 3) + ($hasDiskon ? 1 : 0) }}" class="text-right" style="font-weight: bold; border: 1px solid black; padding: 6px 8px;"><strong>TOTAL</strong></td>
                <td class="text-center" style="font-weight: bold; border: 1px solid black; padding: 6px 8px;"><strong>Rp {{ number_format($quotation->items->sum(fn($item) => $calcSubtotal($item)), 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="{{ ($itemCount > 1 ? 5 : 4) + ($hasDiskon ? 1 : 0) }}" style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-top: none; padding-top: 8px; font-style: italic;">
                    <strong>Terbilang :</strong> {{ ucfirst(terbilang($quotation->items->sum(fn($item) => $calcSubtotal($item)))) }} rupiah
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer-info">
        <div class="keterangan-title">Keterangan :</div>
        @if(!empty($quotation->catatan))
            <div style="padding-left: 20px;">{!! nl2br(e($quotation->catatan)) !!}</div>
        @endif

        @if(!empty($quotation->kata_penutup))
            <div style="padding-top: 10px; padding-left: 20px;">{!! nl2br(e($quotation->kata_penutup)) !!}</div>
        @endif

        <div class="bank-info">
            <p style="text-decoration: underline;">Pembayaran dapat dilakukan melalui:</p>
            <p>Bank BCA</p>
            <p>No. Rekening 619 801 2191</p>
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
