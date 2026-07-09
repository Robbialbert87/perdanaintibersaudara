@extends('layouts.admin')

@section('title', 'Detail Penawaran')

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
            <h5 class="mb-0 text-primary"><i class="bi bi-file-earmark-text me-2"></i>Detail Penawaran</h5>
            <div class="d-flex flex-wrap gap-1">
                <a href="{{ route('quotations.export_pdf', $quotation->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> <span class="d-none d-sm-inline">Export PDF</span>
                </a>
                <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> <span class="d-none d-sm-inline">Edit</span>
                </a>
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Kembali</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-1">Informasi Penawaran</h6>
                <table class="table table-sm table-borderless info-table">
                    <tr>
                        <td width="130">Nomor Surat</td>
                        <td width="10">:</td>
                        <td><strong>{{ $quotation->nomor_surat }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ date('d F Y', strtotime($quotation->tanggal)) }}</td>
                    </tr>
                    @if($quotation->perihal_surat)
                    <tr>
                        <td>Perihal Surat</td>
                        <td>:</td>
                        <td>{{ $quotation->perihal_surat }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td>
                            @php $perihalArray = is_array($quotation->perihal) ? $quotation->perihal : (json_decode($quotation->perihal, true) ?? [$quotation->perihal]); @endphp
                            @if(count($perihalArray) > 1)
                                <ol class="mb-0 ps-3">
                                    @foreach($perihalArray as $p)
                                        <li>{{ $p }}</li>
                                    @endforeach
                                </ol>
                            @else
                                {{ $perihalArray[0] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>
                            @if($quotation->status == 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($quotation->status == 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($quotation->status == 'deal')
                                <span class="badge bg-success">Deal</span>
                            @elseif($quotation->status == 'batal')
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-1">Tujuan (Customer)</h6>
                <table class="table table-sm table-borderless info-table">
                    <tr>
                        <td width="130">Instansi</td>
                        <td width="10">:</td>
                        <td><strong>{{ $quotation->customer->nama_instansi }}</strong></td>
                    </tr>
                    <tr>
                        <td>Contact Person</td>
                        <td>:</td>
                        <td>{{ $quotation->customer->contact_person ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>:</td>
                        <td>{{ $quotation->customer->telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{ $quotation->customer->alamat ?? '-' }} {{ $quotation->customer->kota }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <h6 class="text-muted mb-3">Detail Item</h6>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Deskripsi Pekerjaan / Barang</th>
                        <th width="10%" class="text-center">Vol</th>
                        <th width="20%" class="text-end">Harga Satuan (Rp)</th>
                        <th width="20%" class="text-end">Jumlah Harga (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            @if($item->nama_item)
                                <strong>{{ $item->nama_item }}</strong><br>
                            @endif
                            {!! nl2br(e($item->deskripsi)) !!}
                        </td>
                        <td class="text-center">{{ $item->volume }} {{ $item->satuan ?? $item->product->satuan ?? '' }}</td>
                        <td class="text-end">{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="4" class="text-end fw-bold"><strong>TOTAL</strong></td>
                        <td class="text-end fw-bold"><strong>Rp {{ number_format($quotation->total, 0, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($quotation->catatan)
        <div class="mt-4">
            <h6 class="text-muted mb-1">Catatan:</h6>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($quotation->catatan)) !!}
            </div>
        </div>
        @endif

        @if($quotation->kata_pengantar)
        <div class="mt-4">
            <h6 class="text-muted mb-1">Kata Pengantar:</h6>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($quotation->kata_pengantar)) !!}
            </div>
        </div>
        @endif

        @if($quotation->kata_penutup)
        <div class="mt-4">
            <h6 class="text-muted mb-1">Kata Penutup:</h6>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($quotation->kata_penutup)) !!}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
