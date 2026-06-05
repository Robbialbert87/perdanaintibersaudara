@extends('layouts.admin')

@section('title', 'Master Customer')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-people me-2"></i>Daftar Customer</h5>
        <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Customer
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('customers.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama instansi, contact person, telepon, email..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="d-none d-sm-table-cell">No</th>
                        <th>Nama Instansi</th>
                        <th class="d-none d-sm-table-cell">Contact Person</th>
                        <th>Telepon</th>
                        <th class="d-none d-sm-table-cell">Email</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $index => $customer)
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $customers->firstItem() + $index }}</td>
                        <td>{{ $customer->nama_instansi }}</td>
                        <td class="d-none d-sm-table-cell">{{ $customer->contact_person }}</td>
                        <td>{{ $customer->telepon }}</td>
                        <td class="d-none d-sm-table-cell">{{ $customer->email }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus customer ini?');">
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
                        <td colspan="6" class="text-center py-4 text-muted">Data customer belum tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            {{ $customers->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
