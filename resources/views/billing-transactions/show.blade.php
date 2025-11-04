@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Billing Transaction Details</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('billing-transactions.edit', $billingTransaction->id) }}" class="btn btn-primary">
                <i class="mdi mdi-pencil-outline me-1"></i> Edit
            </a>
            <a href="{{ route('billing-transactions.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Customer:</th>
                        <td>{{ $billingTransaction->customer->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>ID Pelanggan:</th>
                        <td>{{ $billingTransaction->customer->id_pelanggan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Layanan:</th>
                        <td>{{ $billingTransaction->customer->layanan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Region:</th>
                        <td>{{ $billingTransaction->customer->region ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Periode:</th>
                        <td>{{ $billingTransaction->periode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Bandwidth:</th>
                        <td>{{ $billingTransaction->bandwith ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pemakaian (Kbps):</th>
                        <td>
                            @if(isset($billingTransaction->pemakaian))
                                {{ number_format($billingTransaction->pemakaian, 2) }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Harga Satuan:</th>
                        <td>
                            @if(isset($billingTransaction->harga_satuan))
                                Rp{{ number_format($billingTransaction->harga_satuan, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td>
                            @if(isset($billingTransaction->total))
                                Rp{{ number_format($billingTransaction->total, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $billingTransaction->created_at->format('d M Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At:</th>
                        <td>{{ $billingTransaction->updated_at->format('d M Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
