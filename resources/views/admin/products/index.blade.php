@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="m-0 font-weight-bold">Daftar Produk</h6>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="d-none d-sm-table-cell">No</th>
                        <th>Nama Produk</th>
                        <th class="d-none d-sm-table-cell">Kategori</th>
                        <th width="100" class="d-none d-sm-table-cell">Media</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $index + 1 }}</td>
                        <td class="text-nowrap">{{ $product->name }}</td>
                        <td class="d-none d-sm-table-cell">{{ $product->category }}</td>
                        <td class="d-none d-sm-table-cell">
                            @php $imgCount = count($product->active_images ?? []); @endphp
                            @php $vidCount = count($product->active_videos ?? []); @endphp
                            @if($imgCount > 0 || $vidCount > 0)
                                <span class="badge bg-info">{{ $imgCount }} Foto</span>
                                @if($vidCount > 0)
                                    <span class="badge bg-danger">{{ $vidCount }} Video</span>
                                @endif
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info text-white" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
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
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
