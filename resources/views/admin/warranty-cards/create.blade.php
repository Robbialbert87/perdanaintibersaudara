@extends('layouts.admin')

@section('title', 'Buat Kartu Garansi')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-shield-plus me-2"></i>Form Buat Kartu Garansi</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('warranty-cards.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Customer (Opsional)</label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" onchange="if(this.value==='__add__'){ window.open('{{ route('customers.create') }}','_blank'); this.value=''; }">
                        <option value="">-- Pilih Customer --</option>
                        <option value="__add__">+ Tambah Customer Baru</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_instansi }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Verifikator (Opsional)</label>
                    <input type="text" name="verifikator" class="form-control" placeholder="Nama penanggung jawab" value="{{ old('verifikator') }}">
                </div>
            </div>

            <hr>
            <h6 class="mb-3">Data Alat & Instalasi</h6>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                    <input type="text" name="nama_alat" class="form-control @error('nama_alat') is-invalid @enderror" placeholder="Contoh: CT Scan, X-Ray, USG" value="{{ old('nama_alat') }}" required>
                    @error('nama_alat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Type Alat <span class="text-danger">*</span></label>
                    <input type="text" name="type_alat" class="form-control @error('type_alat') is-invalid @enderror" placeholder="Contoh: Somatom Scope, Model XYZ" value="{{ old('type_alat') }}" required>
                    @error('type_alat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama RS/Klinik <span class="text-danger">*</span></label>
                    <input type="text" name="nama_rs_klinik" class="form-control @error('nama_rs_klinik') is-invalid @enderror" placeholder="Contoh: RS Mitra Keluarga, Klinik Sehat" value="{{ old('nama_rs_klinik') }}" required>
                    @error('nama_rs_klinik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Instalasi <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_instalasi" class="form-control @error('tgl_instalasi') is-invalid @enderror" value="{{ old('tgl_instalasi', date('Y-m-d')) }}" required>
                    @error('tgl_instalasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan jika diperlukan...">{{ old('catatan') }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('warranty-cards.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Buat Kartu Garansi</button>
            </div>
        </form>
    </div>
</div>
@endsection
