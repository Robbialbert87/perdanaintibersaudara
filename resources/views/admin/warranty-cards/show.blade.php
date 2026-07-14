@extends('layouts.admin')

@section('title', 'Detail Kartu Garansi')

@push('styles')
<style>
@media (max-width: 576px) {
    .info-table td { display: block; padding: 4px 0 !important; border: none !important; }
    .info-table tr { display: block; margin-bottom: 4px; }
    .info-table td:first-child { font-weight: 600; color: #637381; }
    .info-table td:nth-child(2) { display: none; }
    .info-table td:first-child::after { content: " :"; font-weight: normal; color: #637381; }
}
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
            <h5 class="mb-0 text-primary"><i class="bi bi-shield-check me-2"></i>Detail Kartu Garansi</h5>
            <div class="d-flex flex-wrap gap-1">
                <a href="{{ route('warranty-cards.export_pdf', $warrantyCard->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> <span class="d-none d-sm-inline">Export PDF</span>
                </a>
                <a href="{{ route('warranty-cards.edit', $warrantyCard->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> <span class="d-none d-sm-inline">Edit</span>
                </a>
                <a href="{{ route('warranty-cards.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Kembali</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-1">Informasi Kartu Garansi</h6>
                <table class="table table-sm table-borderless info-table">
                    <tr>
                        <td width="140">Nomor Kartu</td>
                        <td width="10">:</td>
                        <td><strong>{{ $warrantyCard->nomor_kartu }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ date('d F Y', strtotime($warrantyCard->tanggal)) }}</td>
                    </tr>
                    @if($warrantyCard->verifikator)
                    <tr>
                        <td>Verifikator</td>
                        <td>:</td>
                        <td>{{ $warrantyCard->verifikator }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-1">Data Alat</h6>
                <table class="table table-sm table-borderless info-table">
                    <tr>
                        <td width="140">Nama Alat</td>
                        <td width="10">:</td>
                        <td><strong>{{ $warrantyCard->nama_alat }}</strong></td>
                    </tr>
                    <tr>
                        <td>Type Alat</td>
                        <td>:</td>
                        <td>{{ $warrantyCard->type_alat }}</td>
                    </tr>
                    <tr>
                        <td>RS/Klinik</td>
                        <td>:</td>
                        <td>{{ $warrantyCard->nama_rs_klinik }}</td>
                    </tr>
                    <tr>
                        <td>Tgl Instalasi</td>
                        <td>:</td>
                        <td>{{ date('d F Y', strtotime($warrantyCard->tgl_instalasi)) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($warrantyCard->catatan)
        <div class="mt-4">
            <h6 class="text-muted mb-1">Catatan:</h6>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($warrantyCard->catatan)) !!}
            </div>
        </div>
        @endif

        <div class="mt-4 p-3 bg-light rounded border">
            <h6 class="text-muted mb-2"><i class="bi bi-info-circle me-1"></i> Garansi berlaku 1 tahun sejak tanggal pemasangan (instalasi).</h6>
            <p class="small text-muted mb-0">Klik "Export PDF" untuk mencetak Kartu Garansi.</p>
        </div>
    </div>
</div>
@endsection
