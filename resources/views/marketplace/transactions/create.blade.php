@extends('layouts.app')

@section('title', 'Beli Produk')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
            <li class="breadcrumb-item"><a href="{{ route('marketplace.show', $product) }}">{{ $product->name }}</a></li>
            <li class="breadcrumb-item active">Beli Produk</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-shopping-cart"></i> Beli Produk</h4>
                </div>
                <div class="card-body">
                    <!-- Product Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <img src="{{ $product->main_image }}" class="img-fluid rounded" alt="{{ $product->name }}">
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $product->name }}</h5>
                            <p class="text-muted">{{ Str::limit($product->description, 100) }}</p>
                            <p><strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p><strong>Stok Tersedia:</strong> {{ $product->stock_quantity }}</p>
                            <p><strong>Penjual:</strong> {{ $product->seller->name }}</p>
                        </div>
                    </div>

                    <form action="{{ route('marketplace.transactions.store', $product) }}" method="POST">
                        @csrf

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                   id="quantity" name="quantity" value="{{ old('quantity', 1) }}"
                                   min="1" max="{{ $product->stock_quantity }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buyer Info -->
                        <div class="mb-3">
                            <label for="buyer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('buyer_name') is-invalid @enderror"
                                   id="buyer_name" name="buyer_name" value="{{ old('buyer_name', auth()->user()->name) }}" required>
                            @error('buyer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="buyer_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('buyer_phone') is-invalid @enderror"
                                   id="buyer_phone" name="buyer_phone" value="{{ old('buyer_phone', auth()->user()->phone) }}" required>
                            @error('buyer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="buyer_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('buyer_address') is-invalid @enderror"
                                      id="buyer_address" name="buyer_address" rows="3" required
                                      placeholder="Alamat lengkap untuk pengiriman">{{ old('buyer_address', auth()->user()->address) }}</textarea>
                            @error('buyer_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pickup Method -->
                        <div class="mb-3">
                            <label for="pickup_method" class="form-label">Metode Pengambilan <span class="text-danger">*</span></label>
                            <select class="form-select @error('pickup_method') is-invalid @enderror"
                                    id="pickup_method" name="pickup_method" required>
                                <option value="">Pilih Metode</option>
                                <option value="pickup" {{ old('pickup_method') == 'pickup' ? 'selected' : '' }}>Ambil Sendiri</option>
                                <option value="delivery" {{ old('pickup_method') == 'delivery' ? 'selected' : '' }}>Diantar</option>
                                <option value="meetup" {{ old('pickup_method') == 'meetup' ? 'selected' : '' }}>Bertemu</option>
                            </select>
                            @error('pickup_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pickup Address (conditional) -->
                        <div class="mb-3" id="pickup_address_field" style="display: none;">
                            <label for="pickup_address" class="form-label">Alamat Pengambilan</label>
                            <textarea class="form-control @error('pickup_address') is-invalid @enderror"
                                      id="pickup_address" name="pickup_address" rows="2"
                                      placeholder="Alamat tempat pengambilan barang">{{ old('pickup_address') }}</textarea>
                            @error('pickup_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pickup Notes -->
                        <div class="mb-3">
                            <label for="pickup_notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control @error('pickup_notes') is-invalid @enderror"
                                      id="pickup_notes" name="pickup_notes" rows="2"
                                      placeholder="Catatan tambahan untuk penjual">{{ old('pickup_notes') }}</textarea>
                            @error('pickup_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method') is-invalid @enderror"
                                    id="payment_method" name="payment_method" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="E-Wallet" {{ old('payment_method') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Order Summary -->
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Ringkasan Pesanan</h6>
                                <div class="d-flex justify-content-between">
                                    <span>Harga Satuan:</span>
                                    <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Jumlah:</span>
                                    <span id="quantity_display">1</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong id="total_display">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('marketplace.show', $product) }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Buat Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const pickupMethodSelect = document.getElementById('pickup_method');
    const pickupAddressField = document.getElementById('pickup_address_field');
    const quantityDisplay = document.getElementById('quantity_display');
    const totalDisplay = document.getElementById('total_display');
    const productPrice = {{ $product->price }};

    // Update quantity and total
    function updateOrderSummary() {
        const quantity = parseInt(quantityInput.value) || 1;
        const total = productPrice * quantity;

        quantityDisplay.textContent = quantity;
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Show/hide pickup address field
    function togglePickupAddress() {
        if (pickupMethodSelect.value === 'pickup') {
            pickupAddressField.style.display = 'block';
            document.getElementById('pickup_address').required = true;
        } else {
            pickupAddressField.style.display = 'none';
            document.getElementById('pickup_address').required = false;
        }
    }

    // Event listeners
    quantityInput.addEventListener('input', updateOrderSummary);
    pickupMethodSelect.addEventListener('change', togglePickupAddress);

    // Initial setup
    updateOrderSummary();
    togglePickupAddress();
});
</script>
@endsection
