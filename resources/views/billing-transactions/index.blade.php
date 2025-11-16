@extends('layouts.master')
@section('content')
    <!-- DataTables CSS CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" />

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="card-title mb-0">Billing Transactions</h5>

        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end gap-2">
               @if (!empty($billingTransactions->count()))
                   <a href="{{ route('billing-transactions.export', request('periode')) }}" class="btn btn-info" target="_blank">
                    <i class="mdi mdi-download me-1"></i> Export Excel
                </a>
               @endif
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                    <i class="mdi mdi-upload me-1"></i> Bulk Import Excel
                </button>
                <a href="{{ route('billing-transactions.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus me-1"></i> Add Transaction
                </a>
            </div>
            <div class="mb-3">
                <form method="GET" action="{{ route('billing-transactions.index') }}"
                    class="d-flex align-items-center gap-2">
                    <label for="filter_periode" class="mb-0 me-2">Filter Periode:</label>
                    <input type="text" class="form-control form-control-sm" id="filter_periode" name="periode"
                        placeholder="e.g. {{ date('Ym') }}" value="{{ request('periode', date('Ym')) }}" style="width: 140px;">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
                    @if (request('periode'))
                        <a href="{{ route('billing-transactions.index') }}"
                            class="btn btn-sm btn-outline-danger ms-2">Reset</a>
                    @endif
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="billingTransactionsTable" class="table datatables-basic">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>ID Pelanggan</th>
                            <th>Periode</th>
                            <th>Bandwidth</th>
                            <th>Pemakaian (Kbps)</th>
                            <th>Total</th>
                            <th>Harga Satuan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($billingTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->customer->nama ?? '-' }}</td>
                                <td>{{ $transaction->customer->id_pelanggan ?? '-' }}</td>
                                <td>{{ $transaction->periode ?? '-' }}</td>
                                <td>{{ $transaction->bandwith ?? '-' }}</td>
                                <td>
                                    @if (isset($transaction->pemakaian))
                                        {{ number_format($transaction->pemakaian, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if (isset($transaction->total))
                                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if (isset($transaction->harga_satuan))
                                        Rp {{ number_format($transaction->harga_satuan, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('billing-transactions.show', $transaction->id) }}"
                                            class="btn btn-sm btn-icon btn-text-secondary" title="View">
                                            <i class="mdi mdi-eye-outline mdi-20px"></i>
                                        </a>
                                        <a href="{{ route('billing-transactions.edit', $transaction->id) }}"
                                            class="btn btn-sm btn-icon btn-text-secondary" title="Edit">
                                            <i class="mdi mdi-pencil-outline mdi-20px"></i>
                                        </a>
                                        <form action="{{ route('billing-transactions.destroy', $transaction->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this transaction?');"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-text-danger"
                                                title="Delete">
                                                <i class="mdi mdi-delete-outline mdi-20px"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>                        
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bulk Import Modal -->
    <div class="modal fade" id="bulkImportModal" tabindex="-1" aria-labelledby="bulkImportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkImportModalLabel">Bulk Import Billing Transactions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('billing-transactions.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Upload Excel File</label>
                            <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required>
                            <small class="form-text text-muted">
                                File format: Excel (.xlsx, .xls) or CSV (.csv)<br>
                                Required columns: id_pelanggan, periode, pemakaian, total, harga_satuan
                            </small>
                        </div>
                        <div class="alert alert-info">
                            <strong>Excel Format:</strong><br>
                            Row 1 should contain headers: id_pelanggan, periode, bandwith, pemakaian, total, harga_satuan, harga_normal<br>
                            Data should start from row 2.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ asset('template/bulk_transaction_template_excel.xlsx') }}" class="btn btn-outline-primary" download>
                            <i class="mdi mdi-download me-1"></i> Download Template
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-upload me-1"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#billingTransactionsTable').DataTable({
                order: [],
                pageLength: 25,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: -1 } // Disable sorting on Actions column
                ]
            });
        });
    </script>
    @endpush
@endsection
