@extends('layouts.admin')

@section('title', 'Detail Surat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:var(--foreground)">Detail Surat</h4>
        <small class="text-muted">{{ $surat->nomor_surat }}</small>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('surats.preview_pdf', $surat->id) }}" class="btn btn-outline-secondary" target="_blank">
            <i class="bi bi-file-earmark-text"></i> Preview PDF
        </a>
        <a href="{{ route('surats.edit', $surat->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('surats.export_pdf', $surat->id) }}" class="btn btn-primary">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('surats.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Informasi Surat</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td width="130" class="text-muted">Nomor Surat</td>
                        <td>: <strong>{{ $surat->nomor_surat }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jenis</td>
                        <td>: {{ $surat->jenis }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal</td>
                        <td>: {{ $surat->tanggal->locale('id')->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>:
                            @if($surat->status === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($surat->status === 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($surat->status === 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Perihal</td>
                        <td>: {{ $surat->perihal }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Tujuan & Penandatangan</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td width="130" class="text-muted">Tujuan</td>
                        <td>: {{ $surat->tujuan_nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat Tujuan</td>
                        <td>: {{ $surat->tujuan_alamat ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Penandatangan</td>
                        <td>: {{ $surat->nama_penandatangan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jabatan</td>
                        <td>: {{ $surat->jabatan_penandatangan ?: '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h6 class="mb-0">Isi Surat</h6></div>
    <div class="card-body">
        <p class="mb-0">{!! nl2br(e($surat->isi)) !!}</p>
    </div>
</div>

@if(!empty($surat->lampiran))
<div class="card mb-4">
    <div class="card-header"><h6 class="mb-0">Lampiran</h6></div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            @foreach($surat->lampiran as $img)
                <a href="{{ asset('storage/'.$img) }}" target="_blank">
                    <img src="{{ asset('storage/'.$img) }}" alt="Lampiran" style="width:120px;height:90px;object-fit:cover;border-radius:4px;border:1px solid #ddd;">
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
