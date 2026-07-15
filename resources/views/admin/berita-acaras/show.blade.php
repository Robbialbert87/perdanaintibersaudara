@extends('layouts.admin')

@section('title', 'Detail Berita Acara')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:#f4f6f8">Detail Berita Acara</h4>
        <small class="text-muted">{{ $beritaAcara->nomor_surat }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('berita-acaras.edit', $beritaAcara->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('berita-acaras.export_pdf', $beritaAcara->id) }}" class="btn btn-primary">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('berita-acaras.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Informasi Dokumen</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td width="120" class="text-muted">Nomor Surat</td>
                        <td>: <strong>{{ $beritaAcara->nomor_surat }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal</td>
                        <td>: {{ $beritaAcara->tanggal->locale('id')->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>:
                            @if($beritaAcara->status === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($beritaAcara->status === 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($beritaAcara->status === 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kegiatan</td>
                        <td>: {{ $beritaAcara->kegiatan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Lokasi</td>
                        <td>: {{ $beritaAcara->lokasi }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Pihak Penyerah</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td width="80" class="text-muted">Nama</td>
                        <td>: {{ $beritaAcara->pihak_penyerah_nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat</td>
                        <td>: {{ $beritaAcara->pihak_penyerah_alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Pihak Penerima</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td width="80" class="text-muted">Nama</td>
                        <td>: {{ $beritaAcara->pihak_penerima_nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat</td>
                        <td>: {{ $beritaAcara->pihak_penerima_alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h6 class="mb-0">Daftar Produk</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Quantity</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beritaAcara->items as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            @if($item->berfungsi)
                                <span class="badge bg-success">Berfungsi</span>
                            @else
                                <span class="badge bg-danger">Tidak Berfungsi</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($beritaAcara->closing_text)
<div class="card mb-4">
    <div class="card-header"><h6 class="mb-0">Penutup</h6></div>
    <div class="card-body">
        <p class="mb-0">{!! nl2br(e($beritaAcara->closing_text)) !!}</p>
    </div>
</div>
@endif
@endsection
