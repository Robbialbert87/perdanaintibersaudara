@extends('layouts.admin')

@section('title', 'Detail Invoice')

@push('styles')
<style>
.blink-draft {
    animation: blinkRed 1s ease-in-out infinite;
    background-color: #dc3545 !important;
    color: #fff !important;
}
@keyframes blinkRed {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
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
            <h5 class="mb-0 text-primary"><i class="bi bi-receipt me-2"></i>Detail Invoice</h5>
            <div class="d-flex flex-wrap gap-1">
                <a href="{{ route('invoices.export_pdf', $invoice->id) }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> <span class="d-none d-sm-inline">Export PDF</span>
                </a>
                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> <span class="d-none d-sm-inline">Edit</span>
                </a>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Kembali</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <h6 class="text-muted mb-2">Informasi Invoice</h6>
                <table class="table table-sm table-borderless info-table">
                    <tr>
                        <td width="130">Nomor Invoice</td>
                        <td width="10">:</td>
                        <td><strong>{{ $invoice->nomor_invoice }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ date('d F Y', strtotime($invoice->tanggal)) }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>
                            @if($invoice->status == 'draft')
                                <span class="badge bg-secondary blink-draft">Draft</span>
                            @elseif($invoice->status == 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($invoice->status == 'dibayar')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($invoice->status == 'batal')
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                    </tr>
                    @if($invoice->status == 'dibayar')
                    <tr>
                        <td>Tgl Bayar</td>
                        <td>:</td>
                        <td>{{ $invoice->tanggal_bayar ? date('d F Y', strtotime($invoice->tanggal_bayar)) : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Bukti Bayar</td>
                        <td>:</td>
                        <td>
                            @if($invoice->bukti_bayar)
                                @php $ext = pathinfo($invoice->bukti_bayar, PATHINFO_EXTENSION); @endphp
                                @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                    <a href="{{ asset('storage/' . $invoice->bukti_bayar) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $invoice->bukti_bayar) }}" alt="Bukti Bayar" style="max-width:200px;max-height:150px;border:1px solid #ddd;border-radius:4px;" class="img-fluid">
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $invoice->bukti_bayar) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat Bukti (PDF)
                                    </a>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endif
                    @if($invoice->quotation)
                    <tr>
                        <td>Dari Penawaran</td>
                        <td>:</td>
                        <td><a href="{{ route('quotations.show', $invoice->quotation_id) }}">{{ $invoice->quotation->nomor_surat }}</a></td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Tujuan (Customer)</h6>
                <table class="table table-sm table-borderless info-table">
                    <tr>
                        <td width="130">Instansi</td>
                        <td width="10">:</td>
                        <td><strong>{{ $invoice->customer->nama_instansi }}</strong></td>
                    </tr>
                    <tr>
                        <td>Contact Person</td>
                        <td>:</td>
                        <td>{{ $invoice->customer->contact_person ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>:</td>
                        <td>{{ $invoice->customer->telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{ $invoice->customer->alamat ?? '-' }} {{ $invoice->customer->kota }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <h6 class="text-muted mb-3">Detail Item</h6>
        <div class="table-responsive">
            <table class="table table-bordered align-middle" style="min-width:500px;">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="">No</th>
                        <th>Deskripsi Pekerjaan / Barang</th>
                        <th width="12%" class="">Tgl Kegiatan</th>
                        <th width="8%" class="text-center">Vol</th>
                        <th width="18%" class="text-end ">Harga Satuan (Rp)</th>
                        <th width="18%" class="text-end">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $groupedItems = $invoice->items->sortBy('group_no')->groupBy('group_no');
                        $groupSeq = 1;
                    @endphp
                    @foreach($groupedItems as $groupNo => $items)
                        @foreach($items as $itemIndex => $item)
                        <tr>
                            @if($itemIndex === 0)
                                <td class="text-center align-middle" rowspan="{{ count($items) }}">{{ $groupSeq++ }}</td>
                            @endif
                            <td>
                                @if($item->nama_item)
                                    <strong>{{ $item->nama_item }}</strong><br>
                                @endif
                                <div class="text-muted small mt-1" style="white-space: pre-line;">{!! nl2br(e($item->deskripsi)) !!}</div>
                            </td>
                            <td class="">{{ $item->tanggal_kegiatan ? date('d/m/Y', strtotime($item->tanggal_kegiatan)) : '-' }}</td>
                            <td class="text-center">{{ $item->volume }} {{ $item->satuan ?? '' }}</td>
                            <td class="text-end ">{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    @if($invoice->ppn_active)
                    @php
                        $ppn = round($invoice->total * 0.11);
                        $grandTotal = $invoice->total + $ppn;
                    @endphp
                    <tr>
                        <td colspan="5" class="text-end">Sub Total</td>
                        <td class="text-end">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end">PPN (11%)</td>
                        <td class="text-end">Rp {{ number_format($ppn, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>GRAND TOTAL</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="5" class="text-end"><strong>TOTAL</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>

        @if($invoice->catatan)
        <div class="mt-4">
            <h6 class="text-muted mb-1">Catatan:</h6>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($invoice->catatan)) !!}
            </div>
        </div>
        @endif

        @if($invoice->kata_penutup)
        <div class="mt-4">
            <h6 class="text-muted mb-1">Kata Penutup:</h6>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($invoice->kata_penutup)) !!}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
