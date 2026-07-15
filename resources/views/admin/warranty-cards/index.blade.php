@extends('layouts.admin')

@section('title', 'Daftar Kartu Garansi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:#f4f6f8">Kartu Garansi</h4>
        <small class="text-muted">Daftar Kartu Garansi</small>
    </div>
    <a href="{{ route('warranty-cards.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Buat Kartu Garansi</span>
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0" style="color:#f4f6f8">Daftar Kartu Garansi</h6>
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
                        <th>Nomor Kartu</th>
                        <th>Tanggal</th>
                        <th>Nama Alat</th>
                        <th>Type</th>
                        <th>RS/Klinik</th>
                        <th>Tgl Instalasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warrantyCards as $index => $wc)
                    <tr>
                        <td>{{ $warrantyCards->firstItem() + $index }}</td>
                        <td><strong>{{ $wc->nomor_kartu }}</strong></td>
                        <td>{{ date('d/m/Y', strtotime($wc->tanggal)) }}</td>
                        <td>{{ $wc->nama_alat }}</td>
                        <td>{{ $wc->type_alat }}</td>
                        <td>{{ $wc->nama_rs_klinik }}</td>
                        <td>{{ date('d/m/Y', strtotime($wc->tgl_instalasi)) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('warranty-cards.show', $wc->id) }}" class="btn btn-outline-secondary" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('warranty-cards.export_pdf', $wc->id) }}" class="btn btn-outline-secondary" title="PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <a href="{{ route('warranty-cards.edit', $wc->id) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('warranty-cards.destroy', $wc->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
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
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data Kartu Garansi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="d-md-none">
            @forelse($warrantyCards as $wc)
            <div class="border-bottom p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong style="color:#f4f6f8; font-size:13px;">{{ $wc->nomor_kartu }}</strong>
                        <div class="text-muted" style="font-size:11px;">{{ date('d/m/Y', strtotime($wc->tanggal)) }}</div>
                    </div>
                </div>
                <div style="font-size:12px; color:#c4cdd5; margin-bottom:4px;">
                    <i class="bi bi-gear text-muted"></i> {{ $wc->nama_alat }} ({{ $wc->type_alat }})
                </div>
                <div style="font-size:12px; color:#919eab; margin-bottom:4px;">
                    <i class="bi bi-hospital text-muted"></i> {{ $wc->nama_rs_klinik }}
                </div>
                <div style="font-size:11px; color:#637381; margin-bottom:8px;">
                    Instalasi: {{ date('d/m/Y', strtotime($wc->tgl_instalasi)) }}
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('warranty-cards.show', $wc->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('warranty-cards.export_pdf', $wc->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-file-pdf"></i>
                    </a>
                    <a href="{{ route('warranty-cards.edit', $wc->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('warranty-cards.destroy', $wc->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:11px; padding:3px 8px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-4">Belum ada data Kartu Garansi</div>
            @endforelse
        </div>
    </div>
    @if($warrantyCards->hasPages())
    <div class="card-footer">
        {{ $warrantyCards->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
