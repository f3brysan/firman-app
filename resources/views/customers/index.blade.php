@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Customers</h5>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus me-1"></i> Add Customer
            </a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table datatables-basic">
                    <thead>
                        <tr>
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>Layanan</th>
                            <th>Region</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->id_pelanggan }}</td>
                                <td>{{ $customer->nama }}</td>
                                <td>{{ $customer->layanan }}</td>
                                <td>{{ $customer->region }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('customers.show', $customer->id) }}"
                                            class="btn btn-sm btn-icon btn-text-secondary" title="View">
                                            <i class="mdi mdi-eye-outline mdi-20px"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                            class="btn btn-sm btn-icon btn-text-secondary" title="Edit">
                                            <i class="mdi mdi-pencil-outline mdi-20px"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this customer?');"
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
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No customers found. <a
                                        href="{{ route('customers.create') }}">Create one now</a>.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.datatables-basic').DataTable();
            });
        </script>
    @endpush
@endsection
