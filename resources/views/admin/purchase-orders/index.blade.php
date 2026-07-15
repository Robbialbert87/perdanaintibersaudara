@extends('layouts.admin')

@section('title', 'Daftar Purchase Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:#f4f6f8">Purchase Order</h4>
        <small class="text-muted">Daftar Purchase Order</small>
    </div>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Buat PO</span>
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0" style="color:#f4f6f8">Daftar Purchase Order</h6>
        <form method="GET" class="d-flex" style="max-width:250px">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari..." value="{{ request('search') }}">
        </form>
    </div>
    <div class="card-body p-0">
        {{-- Desktop Table --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nomor PO</th>
                        <th>Tanggal</th>
                        <th>Vendor</th>
                        <th>Dipesan Oleh</th>
                        <th>Grand Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $index => $po)
                    <tr>
                        <td>{{ $purchaseOrders->firstItem() + $index }}</td>
                        <td><strong>{{ $po->nomor_surat }}</strong></td>
                        <td>{{ date('d/m/Y', strtotime($po->tanggal)) }}</td>
                        <td>{{ $po->vendor ?? '-' }}</td>
                        <td>{{ $po->buyer_name ?? '-' }}</td>
                        <td>Rp {{ number_format($po->grand_total, 0, ',', '.') }}</td>
                        <td>
                            @if($po->status == 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($po->status == 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($po->status == 'dikonfirmasi')
                                <span class="badge bg-success">Dikonfirmasi</span>
                            @elseif($po->status == 'batal')
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-outline-secondary" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('purchase-orders.export_pdf', $po->id) }}" class="btn btn-outline-secondary" title="PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <a href="{{ route('purchase-orders.edit', $po->id) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('purchase-orders.destroy', $po->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data Purchase Order</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="d-md-none">
            @forelse($purchaseOrders as $po)
            <div class="border-bottom p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong style="color:#f4f6f8; font-size:13px;">{{ $po->nomor_surat }}</strong>
                        <div class="text-muted" style="font-size:11px;">{{ date('d/m/Y', strtotime($po->tanggal)) }}</div>
                    </div>
                    @if($po->status == 'draft')
                        <span class="badge bg-secondary">Draft</span>
                    @elseif($po->status == 'dikirim')
                        <span class="badge bg-info">Dikirim</span>
                    @elseif($po->status == 'dikonfirmasi')
                        <span class="badge bg-success">Dikonfirmasi</span>
                    @elseif($po->status == 'batal')
                        <span class="badge bg-danger">Batal</span>
                    @endif
                </div>
                <div style="font-size:12px; color:#c4cdd5; margin-bottom:4px;">
                    <i class="bi bi-shop text-muted"></i> {{ $po->vendor ?? '-' }}
                </div>
                <div style="font-size:12px; color:#919eab; margin-bottom:4px;">
                    <i class="bi bi-person text-muted"></i> {{ $po->buyer_name ?? '-' }}
                </div>
                <div style="font-size:12px; color:#c4cdd5; margin-bottom:8px;">
                    <i class="bi bi-cash text-muted"></i> Rp {{ number_format($po->grand_total, 0, ',', '.') }}
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('purchase-orders.export_pdf', $po->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-file-pdf"></i>
                    </a>
                    <a href="{{ route('purchase-orders.edit', $po->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('purchase-orders.destroy', $po->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:11px; padding:3px 8px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-4">Belum ada data Purchase Order</div>
            @endforelse
        </div>
    </div>
    @if($purchaseOrders->hasPages())
    <div class="card-footer">
        {{ $purchaseOrders->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
