@extends('layouts.admin')

@section('title', 'Berita Acara')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:#f4f6f8">Berita Acara</h4>
        <small class="text-muted">Daftar Berita Acara Serah Terima dan Uji Fungsi</small>
    </div>
    <a href="{{ route('berita-acaras.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Tambah Baru</span>
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0" style="color:#f4f6f8">Daftar Berita Acara</h6>
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
                        <th>Nomor Surat</th>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Penerima</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beritaAcaras as $ba)
                    <tr>
                        <td>{{ $beritaAcaras->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $ba->nomor_surat }}</strong></td>
                        <td>{{ $ba->tanggal->locale('id')->translatedFormat('d M Y') }}</td>
                        <td>{{ Str::limit($ba->kegiatan, 40) }}</td>
                        <td>{{ Str::limit($ba->pihak_penerima_nama, 20) }}</td>
                        <td>
                            @if($ba->status === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($ba->status === 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($ba->status === 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('berita-acaras.show', $ba->id) }}" class="btn btn-outline-secondary" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('berita-acaras.edit', $ba->id) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('berita-acaras.export_pdf', $ba->id) }}" class="btn btn-outline-secondary" title="PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <form method="POST" action="{{ route('berita-acaras.destroy', $ba->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
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
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data Berita Acara</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="d-md-none">
            @forelse($beritaAcaras as $ba)
            <div class="border-bottom p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong style="color:#f4f6f8; font-size:13px;">{{ $ba->nomor_surat }}</strong>
                        <div class="text-muted" style="font-size:11px;">{{ $ba->tanggal->locale('id')->translatedFormat('d M Y') }}</div>
                    </div>
                    @if($ba->status === 'draft')
                        <span class="badge bg-secondary">Draft</span>
                    @elseif($ba->status === 'dikirim')
                        <span class="badge bg-info">Dikirim</span>
                    @elseif($ba->status === 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-danger">Batal</span>
                    @endif
                </div>
                <div style="font-size:12px; color:#c4cdd5; margin-bottom:4px;">
                    <i class="bi bi-clipboard-check text-muted"></i> {{ Str::limit($ba->kegiatan, 60) }}
                </div>
                <div style="font-size:12px; color:#919eab; margin-bottom:8px;">
                    <i class="bi bi-person text-muted"></i> {{ $ba->pihak_penerima_nama }}
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('berita-acaras.show', $ba->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('berita-acaras.edit', $ba->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="{{ route('berita-acaras.export_pdf', $ba->id) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding:3px 8px;">
                        <i class="bi bi-file-pdf"></i>
                    </a>
                    <form method="POST" action="{{ route('berita-acaras.destroy', $ba->id) }}" onsubmit="return confirm('Yakin hapus?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:11px; padding:3px 8px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-4">Belum ada data Berita Acara</div>
            @endforelse
        </div>
    </div>
    @if($beritaAcaras->hasPages())
    <div class="card-footer">
        {{ $beritaAcaras->links() }}
    </div>
    @endif
</div>
@endsection
