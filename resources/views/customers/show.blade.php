@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Customer Details</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                <i class="mdi mdi-pencil-outline me-1"></i> Edit
            </a>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID Pelanggan:</th>
                        <td>{{ $customer->id_pelanggan }}</td>
                    </tr>
                    <tr>
                        <th>Nama:</th>
                        <td>{{ $customer->nama }}</td>
                    </tr>
                    <tr>
                        <th>Layanan:</th>
                        <td>{{ $customer->layanan }}</td>
                    </tr>
                    <tr>
                        <th>Region:</th>
                        <td>{{ $customer->region }}</td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $customer->created_at->format('d M Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At:</th>
                        <td>{{ $customer->updated_at->format('d M Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
