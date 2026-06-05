@extends('layouts.admin')

@section('title', 'Manajemen Kegiatan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="m-0 font-weight-bold">Daftar Kegiatan</h6>
        <a href="{{ route('activities.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Kegiatan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="d-none d-sm-table-cell">No</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th width="120" class="d-none d-sm-table-cell">Media</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $index => $activity)
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $index + 1 }}</td>
                        <td class="text-nowrap">{{ $activity->title }}</td>
                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}</td>
                        <td class="d-none d-sm-table-cell">
                            @php $imgCount = count($activity->active_images ?? []); @endphp
                            @php $vidCount = count($activity->active_videos ?? []); @endphp
                            @if($imgCount > 0 || $vidCount > 0)
                                <span class="badge bg-info text-white">{{ $imgCount }} Foto</span>
                                @if($vidCount > 0)
                                    <span class="badge bg-danger text-white">{{ $vidCount }} Video</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-sm btn-info text-white" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data kegiatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
