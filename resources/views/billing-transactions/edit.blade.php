@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit Billing Transaction</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('billing-transactions.update', $billingTransaction->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $billingTransaction->customer_id) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->nama }} ({{ $customer->id_pelanggan }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="periode" class="form-label">Periode <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('periode') is-invalid @enderror" id="periode" name="periode" value="{{ old('periode', $billingTransaction->periode) }}" placeholder="e.g., 2024-01" required>
                @error('periode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="bandwith" class="form-label">Bandwidth</label>
                <input type="text" class="form-control @error('bandwith') is-invalid @enderror" id="bandwith" name="bandwith" value="{{ old('bandwith', $billingTransaction->bandwith) }}" placeholder="e.g., 100 Mbps">
                @error('bandwith')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="pemakaian" class="form-label">Pemakaian (Kbps)</label>
                <input type="number" step="0.01" class="form-control @error('pemakaian') is-invalid @enderror" id="pemakaian" name="pemakaian" value="{{ old('pemakaian', $billingTransaction->pemakaian) }}" placeholder="0.00">
                @error('pemakaian')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="harga_satuan" class="form-label">Harga Satuan</label>
                <input type="number" step="0.01" class="form-control @error('harga_satuan') is-invalid @enderror" id="harga_satuan" name="harga_satuan" value="{{ old('harga_satuan', $billingTransaction->harga_satuan) }}" placeholder="0.00">
                @error('harga_satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="number" step="0.01" class="form-control @error('total') is-invalid @enderror" id="total" name="total" value="{{ old('total', $billingTransaction->total) }}" placeholder="0.00">
                @error('total')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save me-1"></i> Update
                </button>
                <a href="{{ route('billing-transactions.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-close me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
