@extends('layouts.admin')

@section('title', 'Daftar Kartu Garansi')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-shield-check me-2"></i>Daftar Kartu Garansi</h5>
        <a href="{{ route('warranty-cards.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Buat Kartu Garansi
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('warranty-cards.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nomor kartu, nama alat, atau nama RS/Klinik..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="d-none d-sm-table-cell">No</th>
                        <th>Nomor Kartu</th>
                        <th class="d-none d-sm-table-cell">Tanggal</th>
                        <th>Nama Alat</th>
                        <th class="d-none d-sm-table-cell">Type</th>
                        <th>RS/Klinik</th>
                        <th class="d-none d-sm-table-cell">Tgl Instalasi</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warrantyCards as $index => $wc)
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $warrantyCards->firstItem() + $index }}</td>
                        <td class="text-nowrap">{{ $wc->nomor_kartu }}</td>
                        <td class="d-none d-sm-table-cell">{{ date('d/m/Y', strtotime($wc->tanggal)) }}</td>
                        <td>{{ $wc->nama_alat }}</td>
                        <td class="d-none d-sm-table-cell">{{ $wc->type_alat }}</td>
                        <td>{{ $wc->nama_rs_klinik }}</td>
                        <td class="d-none d-sm-table-cell">{{ date('d/m/Y', strtotime($wc->tgl_instalasi)) }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('warranty-cards.show', $wc->id) }}" class="btn btn-info btn-sm text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('warranty-cards.export_pdf', $wc->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Export PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <a href="{{ route('warranty-cards.edit', $wc->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('warranty-cards.destroy', $wc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kartu garansi ini?');">
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
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data Kartu Garansi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $warrantyCards->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
