@extends('layouts.admin')

@section('title', 'Daftar Kwitansi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:#f4f6f8">Kwitansi</h4>
        <small class="text-muted">Daftar Kwitansi Pembayaran</small>
    </div>
    <a href="{{ route('kwitansis.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Buat Kwitansi</span>
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0" style="color:#f4f6f8">Daftar Kwitansi</h6>
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
                        <th>Nomor Kwitansi</th>
                        <th>Tanggal</th>
                        <th>Diterima Dari</th>
                        <th>Untuk Pembayaran</th>
                        <th class="text-end">Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kwitansis as $index => $kw)
                    <tr>
                        <td>{{ $kwitansis->firstItem() + $index }}</td>
                        <td><strong>{{ $kw->nomor_kwitansi }}</strong></td>
                        <td>{{ date('d/m/Y', strtotime($kw->tanggal)) }}</td>
                        <td>{{ $kw->customer->nama_instansi ?? '-' }}</td>
                        <td>{{ Str::limit($kw->untuk_pembayaran, 40) ?? '-' }}</td>
                        <td class="text-end">Rp {{ number_format($kw->jumlah, 0, ',', '.') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('kwitansis.show', $kw->id) }}" class="btn btn-outline-secondary" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('kwitansis.export_pdf', $kw->id) }}" class="btn btn-outline-secondary" title="PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <a href="{{ route('kwitansis.edit', $kw->id) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('kwitansis.destroy', $kw->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
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
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data Kwitansi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="d-md-none">
            @forelse($kwitansis as $kw)
            <div class="border-bottom p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong style="color:#f4f6f8; font-size:13px;">{{ $kw->nomor_kwitansi }}</strong>
                        <div class="text-muted" style="font-size:11px;">{{ date('d/m/Y', strtotime($kw->tanggal)) }}</div>
                    </div>
                    <span style="font-size:12px; color:#c4cdd5;">Rp {{ number_format($kw->jumlah, 0, ',', '.') }}</span>
                </div>
                <div style="font-size:12px; color:#c4cdd5; margin-bottom:4px;">
                    <i class="bi bi-person text-muted"></i> {{ $kw->customer->nama_instansi ?? '-' }}
                </div>
                <div style="font-size:11px; color:#919eab; margin-bottom:8px;">
                    <i class="bi bi-card-text text-muted"></i> {{ Str::limit($kw->untuk_pembayaran, 50) ?? '-' }}
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('kwitansis.show', $kw->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('kwitansis.export_pdf', $kw->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-file-pdf"></i>
                    </a>
                    <a href="{{ route('kwitansis.edit', $kw->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('kwitansis.destroy', $kw->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:11px; padding:3px 8px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-4">Belum ada data Kwitansi</div>
            @endforelse
        </div>
    </div>
    @if($kwitansis->hasPages())
    <div class="card-footer">
        {{ $kwitansis->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
