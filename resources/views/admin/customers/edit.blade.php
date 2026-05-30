@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-person-lines-fill me-2"></i>Form Edit Customer</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Instansi <span class="text-danger">*</span></label>
                    <input type="text" name="nama_instansi" class="form-control @error('nama_instansi') is-invalid @enderror" value="{{ old('nama_instansi', $customer->nama_instansi) }}" required>
                    @error('nama_instansi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person', $customer->contact_person) }}">
                    @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon', $customer->telepon) }}">
                    @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $customer->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Kota</label>
                    <input type="text" name="kota" class="form-control @error('kota') is-invalid @enderror" value="{{ old('kota', $customer->kota) }}">
                    @error('kota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $customer->alamat) }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui Customer</button>
            </div>
        </form>
    </div>
</div>
@endsection
