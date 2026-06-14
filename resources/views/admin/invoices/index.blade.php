@extends('layouts.admin')

@section('title', 'Daftar Invoice')

@push('styles')
<style>
.blink-draft {
    animation: blinkRed 1s ease-in-out infinite;
    background-color: #dc3545 !important;
    color: #fff !important;
    cursor: pointer;
}
@keyframes blinkRed {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-receipt me-2"></i>Daftar Invoice</h5>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Buat Invoice
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('invoices.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nomor invoice, perihal, atau nama customer..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="">No</th>
                        <th>Nomor Invoice</th>
                        <th class="">Tanggal</th>
                        <th>Customer</th>
                        <th class="">Perihal</th>
                        <th class="">Total</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $index => $invoice)
                    <tr>
                        <td class="">{{ $invoices->firstItem() + $index }}</td>
                        <td class="text-nowrap">{{ $invoice->nomor_invoice }}</td>
                        <td class="">{{ date('d/m/Y', strtotime($invoice->tanggal)) }}</td>
                        <td class="text-nowrap">{{ $invoice->customer->nama_instansi }}</td>
                        <td class="">
                            @php $items = $invoice->items; @endphp
                            @if($items->count() > 1)
                                <ul class="mb-0 ps-3 list-unstyled">
                                    @foreach($items as $itm)
                                        <li><small>- {!! $itm->nama_item ? '<strong>'.e($itm->nama_item).'</strong>: ' : '' !!}{{ \Illuminate\Support\Str::limit($itm->deskripsi, 30) }}</small></li>
                                    @endforeach
                                </ul>
                            @elseif($items->count() == 1)
                                @php $itm = $items->first(); @endphp
                                {!! $itm->nama_item ? '<strong>'.e($itm->nama_item).'</strong><br>' : '' !!}{{ \Illuminate\Support\Str::limit($itm->deskripsi, 50) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                        <td class="text-nowrap">
                            @if($invoice->status == 'draft')
                                <span class="badge blink-draft" data-bs-toggle="modal" data-bs-target="#paidModal" data-id="{{ $invoice->id }}" data-nomor="{{ $invoice->nomor_invoice }}">Draft</span>
                            @elseif($invoice->status == 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($invoice->status == 'dibayar')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($invoice->status == 'batal')
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('invoices.export_pdf', $invoice->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Export PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus invoice ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data invoice.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $invoices->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('click', function (e) {
    var badge = e.target.closest('.blink-draft');
    if (!badge) return;
    var id = badge.dataset.id;
    var nomor = badge.dataset.nomor;
    document.getElementById('modalInvoiceInfo').textContent = 'Invoice: ' + nomor;
    document.getElementById('formBayar').action = '{{ route("invoices.mark_paid", "INVOICE_ID") }}'.replace('INVOICE_ID', id);
});
</script>
@endpush

@push('modals')
<div class="modal fade" id="paidModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="" id="formBayar" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check-circle text-success me-2"></i>Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalInvoiceInfo" class="text-muted mb-3"></p>
                <div class="mb-3">
                    <label class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_bayar" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bukti Bayar <span class="text-danger">*</span></label>
                    <input type="file" name="bukti_bayar" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    <div class="form-text">Format: JPG, PNG, atau PDF. Maksimal 2MB.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Tandai Lunas</button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection
