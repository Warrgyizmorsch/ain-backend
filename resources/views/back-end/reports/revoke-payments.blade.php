@extends('layouts.app')

@section('content')

<div class=" d-flex flex-column flex-column-fluid">
   @php
        $filterRoute = route('payments.revoke.filter');
        $filterTitle = 'Filtered Revoked Payments';
    @endphp

    @include('back-end.order.partials.filter', [
        'overdueCount' => $overdueCount,
        'filterRoute' => route('payments.revoke.filter'),
        'filterTitle' => 'Filtered Revoked Payments',
        'filterStorageKey' => 'revoke_payment_filters',
        'hideOrderQuickFilters' => true
    ])
    <div class="card">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title">
                <span class="card-label fw-bolder fs-3 mb-1">Revoked Payments List</span>
            </h3>
        </div>

        <div class="card-body py-3">
            <div class="table-responsive" style="overflow-x:auto;">
                <table class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bolder text-dark bg-light">
                            <th class="min-w-50px text-center" style="padding-right: 0px; background: #F5F8FA;">Sr.</th>
                            <th class="text-center" style="position: sticky; left: 0; background: #F5F8FA; z-index: 6;">Action</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Comment</th>
                            <th class="min-w-100px text-center" style="background: #F5F8FA;">Order ID</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">User</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Order Date</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Deadline</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Project Title</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Status</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Ticket Status</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Words</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Price</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Received</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Due</th>
                            <th class="min-w-150px text-center" style="background: #F5F8FA;">Writer</th>
                            @if(auth()->user()->role_id == 1)
                                <th class="min-w-200px text-center" style="background: #F5F8FA;">Other</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($data['payments'] as $payment)
                            @php
                                $order = $payment->order;
                                $index = $loop->index + ($data['payments']->perPage() * ($data['payments']->currentPage() - 1));
                            @endphp

                            @if($order)
                                @include('back-end.reports.partials.revoked-payment-row', [
                                    'order' => $order,
                                    'payment' => $payment,
                                    'index' => $index
                                ])
                            @endif
                        @endforeach
                    </tbody>
                </table>

                {{ $data['payments']->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</div>

@endsection