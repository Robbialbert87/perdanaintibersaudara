@extends('layouts.admin')

@section('title', 'Detail Kwitansi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:#f4f6f8">Detail Kwitansi</h4>
        <small class="text-muted">{{ $kwitansi->nomor_kwitansi }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('kwitansis.export_pdf', $kwitansi->id) }}" class="btn btn-danger">
            <i class="bi bi-file-pdf"></i> <span class="d-none d-sm-inline">Download PDF</span>
        </a>
        <a href="{{ route('kwitansis.edit', $kwitansi->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> <span class="d-none d-sm-inline">Edit</span>
        </a>
        <a href="{{ route('kwitansis.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Kembali</span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header py-3">
        <h6 class="mb-0" style="color:#f4f6f8"><i class="bi bi-cash-coin me-2"></i>{{ $kwitansi->nomor_kwitansi }}</h6>
    </div>
    <div class="card-body">
        <table class="table table-borderless mb-0">
            <tr>
                <td width="220" class="text-muted">Nomor Kwitansi</td>
                <td>: <strong>{{ $kwitansi->nomor_kwitansi }}</strong></td>
            </tr>
            <tr>
                <td class="text-muted">Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($kwitansi->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="text-muted">Sudah Diterima Dari</td>
                <td>: {{ $kwitansi->customer->nama_instansi ?? '-' }}
                    @if($kwitansi->customer)
                        <br><small class="text-muted ms-3">{{ $kwitansi->customer->alamat }}{{ $kwitansi->customer->kota ? ', '.$kwitansi->customer->kota : '' }}</small>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="text-muted">Uang Sejumlah (Terbilang)</td>
                <td>: <em>{{ ucfirst(terbilangKwitansi($kwitansi->jumlah)) }} rupiah</em></td>
            </tr>
            <tr>
                <td class="text-muted">Jumlah</td>
                <td>: <strong>Rp {{ number_format($kwitansi->jumlah, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td class="text-muted">Untuk Pembayaran</td>
                <td>: {{ $kwitansi->untuk_pembayaran ?? '-' }}</td>
            </tr>
            @if($kwitansi->catatan)
            <tr>
                <td class="text-muted">Catatan</td>
                <td>: {!! nl2br(e($kwitansi->catatan)) !!}</td>
            </tr>
            @endif
            @if($kwitansi->invoice)
            <tr>
                <td class="text-muted">Referensi Invoice</td>
                <td>: {{ $kwitansi->invoice->nomor_invoice }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>
@endsection
