@extends('layouts.app')

@section('title', 'Edit Kegiatan - Infoma')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Kegiatan</h1>
            <p class="text-gray-600 mt-2">Ubah informasi kegiatan "{{ $activity->name }}"</p>
        </div>

        <form method="POST" action="{{ route('provider.activities.update', $activity) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kegiatan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', $activity->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror" placeholder="Nama kegiatan">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror" placeholder="Deskripsikan kegiatan Anda...">{{ old('description', $activity->description) }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                @if($category->type === 'activity')
                                    <option value="{{ $category->id }}" {{ old('category_id', $activity->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                        <input type="text" name="location" required value="{{ old('location', $activity->location) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('location') border-red-500 @enderror" placeholder="Lokasi kegiatan">
                        @error('location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Event <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="event_date" required value="{{ old('event_date', $activity->event_date ? $activity->event_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('event_date') border-red-500 @enderror">
                        @error('event_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Batas Registrasi <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="registration_deadline" required value="{{ old('registration_deadline', $activity->registration_deadline ? $activity->registration_deadline->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('registration_deadline') border-red-500 @enderror">
                        @error('registration_deadline')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Kapasitas & Harga</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" required min="0" value="{{ old('price', $activity->price) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('price') border-red-500 @enderror" placeholder="0">
                        @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" required min="1" value="{{ old('capacity', $activity->capacity) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('capacity') border-red-500 @enderror" placeholder="1">
                        @error('capacity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-md font-medium text-gray-900 mb-4">Diskon (Opsional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Diskon</label>
                            <select name="discount_type" id="discount_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('discount_type') border-red-500 @enderror">
                                <option value="">Tidak ada diskon</option>
                                <option value="percentage" {{ old('discount_type', $activity->discount_type) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="flat" {{ old('discount_type', $activity->discount_type) === 'flat' ? 'selected' : '' }}>Nominal (Rp)</option>
                            </select>
                            @error('discount_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Diskon</label>
                            <input type="number" name="discount_value" id="discount_value" min="0" value="{{ old('discount_value', $activity->discount_value) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('discount_value') border-red-500 @enderror" placeholder="0">
                            @error('discount_value')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            @if($activity->images && count($activity->images) > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Gambar Saat Ini</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($activity->images as $image)
                    <div>
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $activity->name }}" class="w-full h-32 object-cover rounded-lg">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Tambah Gambar Baru</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('images.*') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max: 5MB per file)</p>
                    @error('images.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status</h2>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $activity->is_active) ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label class="ml-2 text-sm text-gray-700">Aktifkan kegiatan ini</label>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('provider.activities.show', $activity) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Kegiatan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('discount_type').addEventListener('change', function() {
    const discountValue = document.getElementById('discount_value');
    const label = discountValue.previousElementSibling;
    if (this.value === 'percentage') {
        discountValue.placeholder = '10';
        label.textContent = 'Persentase Diskon (%)';
        discountValue.max = '100';
    } else if (this.value === 'flat') {
        discountValue.placeholder = '100000';
        label.textContent = 'Nominal Diskon (Rp)';
        discountValue.removeAttribute('max');
    } else {
        discountValue.placeholder = '0';
        label.textContent = 'Nilai Diskon';
        discountValue.value = '';
    }
});
</script>
@endpush
@endsection


