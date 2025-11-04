@extends('layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Pelanggan</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped mb-4">
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>ID</th>
                    <th>BW</th>
                    <th>LAYANAN</th>
                    <th>PEMAKAIAN AVG (Kbps)</th>
                    <th>TRAFFIK TOTAL (GB)</th>
                    <th>PERIOD</th>
                    <th>HARGA PER LOKASI</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($billingTransactions as $billingTransaction)
                    <tr>
                        <td>{{ $billingTransaction->customer->nama ?? '-' }}</td>
                        <td>{{ $billingTransaction->customer->id_pelanggan ?? '-' }}</td>
                        <td>{{ $billingTransaction->bandwith ?? '-' }}</td>
                        <td>{{ $billingTransaction->customer->layanan ?? '-' }}</td>
                        <td>
                            @if(isset($billingTransaction->pemakaian))
                                {{ number_format($billingTransaction->pemakaian, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if(isset($billingTransaction->pemakaian))
                                {{ number_format(($billingTransaction->pemakaian * 30 * 24 * 60 * 60) / 8 / 1024 / 1024, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $billingTransaction->periode ?? '-' }}</td>
                        <td>
                            @if(isset($billingTransaction->harga_satuan))
                                Rp{{ number_format($billingTransaction->harga_satuan, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>        
    </div>
</div>
@endsection