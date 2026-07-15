@extends('layouts.admin')

@section('title', 'Berita Acara')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1" style="color:#f4f6f8">Berita Acara</h4>
        <small class="text-muted">Daftar Berita Acara Serah Terima dan Uji Fungsi</small>
    </div>
    <a href="{{ route('berita-acaras.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Baru
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0" style="color:#f4f6f8">Daftar Berita Acara</h6>
        <form method="GET" class="d-flex" style="max-width:250px">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nomor/kegiatan..." value="{{ request('search') }}">
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
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
                        <td>{{ $ba->tanggal->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>{{ Str::limit($ba->kegiatan, 50) }}</td>
                        <td>{{ $ba->pihak_penerima_nama }}</td>
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
                                <a href="{{ route('berita-acaras.export_pdf', $ba->id) }}" class="btn btn-outline-secondary" title="Export PDF">
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
    </div>
    @if($beritaAcaras->hasPages())
    <div class="card-footer">
        {{ $beritaAcaras->links() }}
    </div>
    @endif
</div>
@endsection
