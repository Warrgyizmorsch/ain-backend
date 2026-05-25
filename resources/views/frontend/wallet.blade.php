@extends('frontend-layouts.app')

@section('content')
<section class="wallet-page" style="background:#f5f7fb; padding:40px 0;">
    <div class="container">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        @if($errors->has('email'))
            <div class="alert alert-danger mb-3">{{ $errors->first('email') }}</div>
        @endif

        @php
            $displayName = ($walletUser && $walletUser->name) ? $walletUser->name : 'there';
            $displayAmount = is_null($walletAmount) ? null : number_format($walletAmount, 2);
        @endphp

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 style="font-weight:700; margin-bottom:6px; color:#222;">My Wallet</h2>
                    <p style="margin:0; color:#666;">
                        Hello, {{ $displayName }} 👋 | Check your wallet balance & transaction history.
                    </p>
                </div>

                <div class="mt-3 mt-md-0">
                    <a href="/upload-your-assignment"
                       class="btn"
                       style="
                            padding:10px 20px;
                            border-radius:8px;
                            font-weight:600;
                            font-size:14px;
                            background:linear-gradient(to right,#3F159A,#0E8FCE);
                            color:#fff;
                            box-shadow:0 4px 12px rgba(0,0,0,0.15);
                       ">
                        Place New Order
                    </a>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- LEFT: Wallet summary --}}
            <div class="col-lg-4 mb-4">
                <div style="background:#fff; border-radius:16px; padding:24px 20px; box-shadow:0 8px 20px rgba(0,0,0,0.06);">
                    <h5 style="font-weight:600; margin-bottom:12px;">Wallet Balance</h5>

                    <div style="font-size:30px; font-weight:700; color:#3F159A;">
                        £ {{ $displayAmount ?? '--' }}
                    </div>

                    <p style="margin:10px 0 18px; color:#777; font-size:14px;">
                        Verify your email to view wallet balance.
                    </p>

                    {{-- Email Verify --}}
                    <form action="{{ route('wallet.verify') }}" method="POST">
                        @csrf
                        <label style="font-size:13px;">Registered Email</label>
                        <input type="email"
                               name="email"
                               class="form-control mb-2"
                               value="{{ old('email', $walletEmail) }}"
                               required>

                        <button class="btn"
                                style="
                                    padding:8px 16px;
                                    border-radius:8px;
                                    font-size:14px;
                                    background:linear-gradient(to right,#3F159A,#0E8FCE);
                                    color:#fff;
                                ">
                            Verify & Fetch Wallet
                        </button>
                    </form>

                    {{-- ONLY VIEW ORDERS (Add Money REMOVED) --}}
                    <div class="mt-3">
                        <a href="/MyOrders"
                           class="btn"
                           style="
                                padding:8px 18px;
                                border-radius:8px;
                                font-size:14px;
                                border:1px solid #d0d0ff;
                                background:#fff;
                                color:#3F159A;
                           ">
                            View Orders
                        </a>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Transactions --}}
            <div class="col-lg-8 mb-4">
                <div style="background:#fff; border-radius:16px; padding:20px; box-shadow:0 8px 20px rgba(0,0,0,0.06);">

                    <h5 style="font-weight:600; margin-bottom:15px;">Transaction History</h5>

                    @if($walletUser && isset($transactions) && $transactions->count())
                        <div class="table-responsive">
                            <table class="table mb-0" style="font-size:14px;">
                                <thead style="background:#f5f5ff;">
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-right">Amount (£)</th>
                                        <th class="text-right">Balance After (£)</th>
                                        <th class="text-center">Expire On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $txn)
                                        <tr>
                                            <td>{{ $txn->created_at->format('d M Y') }}</td>
                                            <td>{{ $txn->description ?? 'Wallet transaction' }}</td>

                                            <td class="text-center">
                                                @if($txn->type === 'credit')
                                                    <span style="color:#0f7a2a; font-weight:600;">Credit</span>
                                                @else
                                                    <span style="color:#b02020; font-weight:600;">Debit</span>
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                {{ $txn->type === 'credit' ? '+' : '-' }}
                                                {{ number_format($txn->amount, 2) }}
                                            </td>

                                            <td class="text-right">
                                                {{ number_format($txn->balance_after ?? 0, 2) }}
                                            </td>

                                            {{-- EXPIRE COLUMN --}}
                                            <td class="text-center">
                                                @if(!empty($txn->expires_at))
                                                    {{ \Carbon\Carbon::parse($txn->expires_at)->format('d M Y') }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if(method_exists($transactions, 'links'))
                            <div class="mt-3">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            No wallet transactions found.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
