@extends('layouts.admin')

@section('title', 'Manajemen Layanan')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="m-0 font-weight-bold">Daftar Layanan</h6>
        <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Layanan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="d-none d-sm-table-cell">No</th>
                        <th width="120" class="d-none d-sm-table-cell">Gambar</th>
                        <th>Layanan</th>
                        <th class="d-none d-sm-table-cell">Deskripsi</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $index => $service)
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $index + 1 }}</td>
                        <td class="d-none d-sm-table-cell">
                            @if($service->image)
                                <img src="{{ Storage::url($service->image) }}" alt="{{ $service->title }}" class="img-thumbnail" style="max-height: 80px; width: auto;">
                            @else
                                <span class="text-muted small">No Image</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $service->title }}</strong>
                            @if(!empty($service->features))
                                <div class="small text-muted mt-1">
                                    {{ count($service->features) }} fitur
                                </div>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell">{{ Str::limit($service->description, 80) }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-info text-white" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
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
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data layanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
