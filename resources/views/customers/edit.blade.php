@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit Customer</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="id_pelanggan" class="form-label">ID Pelanggan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('id_pelanggan') is-invalid @enderror" id="id_pelanggan" name="id_pelanggan" value="{{ old('id_pelanggan', $customer->id_pelanggan) }}" required>
                @error('id_pelanggan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $customer->nama) }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="layanan" class="form-label">Layanan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('layanan') is-invalid @enderror" id="layanan" name="layanan" value="{{ old('layanan', $customer->layanan) }}" required>
                @error('layanan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region', $customer->region) }}" required>
                @error('region')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save me-1"></i> Update
                </button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-close me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
