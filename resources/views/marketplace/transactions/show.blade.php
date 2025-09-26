@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
            <li class="breadcrumb-item"><a href="{{ route('marketplace.transactions.index') }}">Transaksi Saya</a></li>
            <li class="breadcrumb-item active">Detail Transaksi</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <!-- Transaction Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Detail Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Kode Transaksi:</strong> {{ $transaction->transaction_code }}</p>
                            <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>
                            <p><strong>Status:</strong>
                                <span class="badge bg-{{ $transaction->status == 'completed' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'info') }}">
                                    {{ $transaction->status_label }}
                                </span>
                            </p>
                            <p><strong>Status Pembayaran:</strong>
                                <span class="badge bg-{{ $transaction->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ $transaction->payment_status_label }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Metode Pengambilan:</strong> {{ $transaction->pickup_method_label }}</p>
                            <p><strong>Metode Pembayaran:</strong> {{ $transaction->payment_method }}</p>
                            @if($transaction->completed_at)
                                <p><strong>Selesai:</strong> {{ $transaction->completed_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Detail Produk</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{ $transaction->product->main_image }}"
                                 class="img-fluid rounded" alt="{{ $transaction->product->name }}">
                        </div>
                        <div class="col-md-9">
                            <h6>{{ $transaction->product->name }}</h6>
                            <p class="text-muted">{{ $transaction->product->description }}</p>
                            <p><strong>Harga Satuan:</strong> Rp {{ number_format($transaction->unit_price, 0, ',', '.') }}</p>
                            <p><strong>Jumlah:</strong> {{ $transaction->quantity }}</p>
                            <p><strong>Total:</strong> Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buyer Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Pembeli</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> {{ $transaction->buyer_name }}</p>
                            <p><strong>Telepon:</strong> {{ $transaction->buyer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Alamat:</strong> {{ $transaction->buyer_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pickup Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Pengambilan</h5>
                </div>
                <div class="card-body">
                    <p><strong>Metode:</strong> {{ $transaction->pickup_method_label }}</p>
                    @if($transaction->pickup_address)
                        <p><strong>Alamat Pengambilan:</strong> {{ $transaction->pickup_address }}</p>
                    @endif
                    @if($transaction->pickup_notes)
                        <p><strong>Catatan:</strong> {{ $transaction->pickup_notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Payment Proof -->
            @if($transaction->payment_proof)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <img src="{{ $transaction->payment_proof_url }}" class="img-fluid rounded" alt="Bukti Pembayaran">
                    </div>
                </div>
            @endif

            <!-- Rating -->
            @if($transaction->rating)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Rating & Ulasan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <strong>{{ $transaction->rating->user->name }}</strong>
                            <div class="ms-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $transaction->rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                        @if($transaction->rating->review)
                            <p>{{ $transaction->rating->review }}</p>
                        @endif
                        <small class="text-muted">{{ $transaction->rating->created_at->format('d M Y') }}</small>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5>Aksi</h5>
                </div>
                <div class="card-body">
                    @if($transaction->buyer_id === auth()->id())
                        <!-- Buyer Actions -->
                        @if($transaction->status === 'pending' && $transaction->payment_status === 'pending')
                            <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#uploadPaymentModal">
                                <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                            </button>
                        @endif

                        @if($transaction->status === 'completed' && !$transaction->rating)
                            <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#ratingModal">
                                <i class="fas fa-star"></i> Beri Rating
                            </button>
                        @endif

                        @if($transaction->canBeCancelled())
                            <form method="POST" action="{{ route('marketplace.transactions.update-status', $transaction) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="button" class="btn btn-danger w-100" onclick="cancelTransaction()">
                                    <i class="fas fa-times"></i> Batalkan Transaksi
                                </button>
                            </form>
                        @endif
                    @else
                        <!-- Seller Actions -->
                        @if($transaction->status === 'pending')
                            <form method="POST" action="{{ route('marketplace.transactions.update-status', $transaction) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-check"></i> Konfirmasi Transaksi
                                </button>
                            </form>
                        @endif

                        @if($transaction->status === 'confirmed')
                            <form method="POST" action="{{ route('marketplace.transactions.update-status', $transaction) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="btn btn-info w-100 mb-2">
                                    <i class="fas fa-truck"></i> Proses Pengiriman
                                </button>
                            </form>
                        @endif

                        @if($transaction->status === 'in_progress')
                            <form method="POST" action="{{ route('marketplace.transactions.update-status', $transaction) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-check-circle"></i> Selesaikan Transaksi
                                </button>
                            </form>
                        @endif
                    @endif

                    <a href="{{ route('marketplace.show', $transaction->product) }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-eye"></i> Lihat Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Payment Proof Modal -->
<div class="modal fade" id="uploadPaymentModal" tabindex="-1" aria-labelledby="uploadPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('marketplace.transactions.upload-payment-proof', $transaction) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadPaymentModalLabel">Upload Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*" required>
                        <div class="form-text">Upload foto bukti pembayaran (JPG, PNG, GIF)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rating Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('marketplace.transactions.rate', $transaction) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Beri Rating</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="review" class="form-label">Ulasan</label>
                        <textarea class="form-control" id="review" name="review" rows="3" placeholder="Bagikan pengalaman Anda..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="images" class="form-label">Foto (Opsional)</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_recommended" name="is_recommended" value="1">
                            <label class="form-check-label" for="is_recommended">
                                Rekomendasikan produk ini
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    font-size: 1.5rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.rating label:hover,
.rating label:hover ~ label,
.rating input:checked ~ label {
    color: #ffc107;
}
</style>

<script>
function cancelTransaction() {
    if (confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')) {
        const form = event.target.closest('form');
        const reason = prompt('Alasan pembatalan:');
        if (reason) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cancellation_reason';
            input.value = reason;
            form.appendChild(input);
            form.submit();
        }
    }
}
</script>
@endsection
