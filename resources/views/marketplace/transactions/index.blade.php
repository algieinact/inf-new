@extends('layouts.app')

@section('title', 'Transaksi Marketplace')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
            <li class="breadcrumb-item active">Transaksi Saya</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Transaksi Marketplace</h2>
    </div>

    @if($transactions->count() > 0)
        <div class="row">
            @foreach($transactions as $transaction)
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $transaction->product->main_image }}"
                                         class="img-fluid rounded" alt="{{ $transaction->product->name }}"
                                         style="height: 80px; object-fit: cover;">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $transaction->product->name }}</h6>
                                    <p class="text-muted small mb-1">
                                        @if($transaction->buyer_id === auth()->id())
                                            Dijual oleh: {{ $transaction->seller->name }}
                                        @else
                                            Dibeli oleh: {{ $transaction->buyer->name }}
                                        @endif
                                    </p>
                                    <p class="text-muted small mb-0">
                                        Qty: {{ $transaction->quantity }} × Rp {{ number_format($transaction->unit_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="text-primary mb-1">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h6>
                                    <span class="badge bg-{{ $transaction->status == 'completed' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'info') }}">
                                        {{ $transaction->status_label }}
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-{{ $transaction->payment_status == 'paid' ? 'success' : 'warning' }}">
                                        {{ $transaction->payment_status_label }}
                                    </span>
                                    <p class="text-muted small mb-0 mt-1">
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('marketplace.transactions.show', $transaction) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4>Belum ada transaksi</h4>
            <p class="text-muted">Mulai berbelanja di marketplace</p>
            <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Lihat Produk
            </a>
        </div>
    @endif
</div>
@endsection
