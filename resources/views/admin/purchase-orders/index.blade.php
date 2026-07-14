@extends('layouts.admin')

@section('title', 'Daftar Purchase Order')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-cart-check me-2"></i>Daftar Purchase Order</h5>
        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Buat PO
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase-orders.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nomor surat, perihal, atau nama customer..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="d-none d-sm-table-cell">No</th>
                        <th>Nomor Surat</th>
                        <th class="d-none d-sm-table-cell">Tanggal</th>
                        <th>Customer</th>
                        <th class="d-none d-sm-table-cell">Perihal</th>
                        <th class="d-none d-sm-table-cell">Total</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchaseOrders as $index => $po)
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $purchaseOrders->firstItem() + $index }}</td>
                        <td class="text-nowrap">{{ $po->nomor_surat }}</td>
                        <td class="d-none d-sm-table-cell">{{ date('d/m/Y', strtotime($po->tanggal)) }}</td>
                        <td class="text-nowrap">{{ $po->customer->nama_instansi }}</td>
                        <td class="d-none d-sm-table-cell">
                            @php $perihalArray = is_array($po->perihal) ? $po->perihal : (json_decode($po->perihal, true) ?? [$po->perihal]); @endphp
                            @if(count($perihalArray) > 1)
                                <ul class="mb-0 ps-3 list-unstyled">
                                    @foreach($perihalArray as $p)
                                        <li>- {{ \Illuminate\Support\Str::limit($p, 30) }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ \Illuminate\Support\Str::limit($perihalArray[0], 50) }}
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                        <td class="text-nowrap">
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
                        <td class="text-center text-nowrap">
                            <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-info btn-sm text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('purchase-orders.export_pdf', $po->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Export PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <a href="{{ route('purchase-orders.edit', $po->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('purchase-orders.destroy', $po->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus Purchase Order ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data Purchase Order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            {{ $purchaseOrders->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
