@extends('layouts.admin')

@section('title', 'Surat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:var(--foreground)">Surat</h4>
        <small class="text-muted">Daftar surat permintaan, pengantar, keterangan, dan lainnya</small>
    </div>
    <a href="{{ route('surats.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Tambah Baru</span>
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0" style="color:var(--foreground)">Daftar Surat</h6>
        <form method="GET" class="d-flex" style="max-width:250px">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari..." value="{{ request('search') }}">
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nomor Surat</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Perihal</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surats as $surat)
                    <tr>
                        <td>{{ $surats->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $surat->nomor_surat }}</strong></td>
                        <td>{{ $surat->jenis }}</td>
                        <td>{{ $surat->tanggal->locale('id')->translatedFormat('d M Y') }}</td>
                        <td>{{ Str::limit($surat->perihal, 40) }}</td>
                        <td>{{ Str::limit($surat->tujuan_nama, 25) }}</td>
                        <td>
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
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('surats.show', $surat->id) }}" class="btn btn-outline-secondary" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('surats.edit', $surat->id) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('surats.preview_pdf', $surat->id) }}" class="btn btn-outline-secondary" title="Preview PDF" target="_blank">
                                    <i class="bi bi-file-earmark-text"></i>
                                </a>
                                <a href="{{ route('surats.export_pdf', $surat->id) }}" class="btn btn-outline-secondary" title="Export PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <form method="POST" action="{{ route('surats.destroy', $surat->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
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
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data Surat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-md-none">
            @forelse($surats as $surat)
            <div class="border-bottom p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong style="color:var(--foreground); font-size:13px;">{{ $surat->nomor_surat }}</strong>
                        <div class="text-muted" style="font-size:11px;">{{ $surat->jenis }} &middot; {{ $surat->tanggal->locale('id')->translatedFormat('d M Y') }}</div>
                    </div>
                    @if($surat->status === 'draft')
                        <span class="badge bg-secondary">Draft</span>
                    @elseif($surat->status === 'dikirim')
                        <span class="badge bg-info">Dikirim</span>
                    @elseif($surat->status === 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-danger">Batal</span>
                    @endif
                </div>
                <div style="font-size:12px; color:var(--muted-foreground); margin-bottom:4px;">
                    <i class="bi bi-file-text text-muted"></i> {{ Str::limit($surat->perihal, 60) }}
                </div>
                <div style="font-size:12px; color:var(--muted-foreground); margin-bottom:8px;">
                    <i class="bi bi-person text-muted"></i> {{ $surat->tujuan_nama }}
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('surats.show', $surat->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('surats.edit', $surat->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="{{ route('surats.preview_pdf', $surat->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;" target="_blank">
                        <i class="bi bi-file-earmark-text"></i>
                    </a>
                    <a href="{{ route('surats.export_pdf', $surat->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-file-pdf"></i>
                    </a>
                    <form method="POST" action="{{ route('surats.destroy', $surat->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:11px; padding:3px 8px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-4">Belum ada data Surat</div>
            @endforelse
        </div>
    </div>
    @if($surats->hasPages())
    <div class="card-footer">
        {{ $surats->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
