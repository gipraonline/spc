@extends('layouts.app')

@section('content')

<div class="card filter-card">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="card-title-custom mb-0">Payout Batch Report</h5>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered">

                <thead class="table-dark">
                    <tr>
                        <th>Sl No.</th>
                        <th>Batch No</th>
                        <th>Type</th>
                        <th>Employees</th>
                        <th>Total Amount</th>
                        <th>Processed Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($batches as $key => $batch)
                    <tr>
                        <td>{{ $batches->firstItem() + $key }}</td>

                        <td>{{ $batch->batch_no }}</td>

                        <td>{{ ucfirst($batch->payout_type) }}</td>

                        <td>{{ $batch->employee_count }}</td>

                        <td>{{ number_format($batch->total_amount, 2) }}</td>

                        <td>{{ $batch->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td>

                        <td>
                            @can('payout-reports.view')
                            <a href="{{ route('admin.payout-reports.show', $batch->id) }}"
                                class="btn btn-sm btn-primary">
                                View
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No payout batches found</td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $batches->links() }}
        </div>

    </div>
</div>

@endsection