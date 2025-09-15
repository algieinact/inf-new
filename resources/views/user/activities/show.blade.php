@extends('layouts.app')

@section('title', $activity->name . ' - Infoma')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-green-600">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('activities.index') }}" class="hover:text-green-600">Kegiatan</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900">{{ $activity->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                    @if($activity->images && count($activity->images) > 0)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $activity->images[0]) }}"
                                 alt="{{ $activity->name }}"
                                 class="w-full h-96 object-cover" id="mainImage">
                            @if(count($activity->images) > 1)
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="flex space-x-2 overflow-x-auto">
                                        @foreach($activity->images as $index => $image)
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 alt="{{ $activity->name }}"
                                                 class="w-16 h-16 object-cover rounded cursor-pointer border-2 {{ $index === 0 ? 'border-green-500' : 'border-white' }}"
                                                 onclick="changeMainImage('{{ asset('storage/' . $image) }}', this)">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-6xl text-gray-400"></i>
                        </div>
                    @endif
                </div>

                <!-- Activity Details -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $activity->name }}</h1>
                            <div class="flex items-center text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $activity->location }}</span>
                            </div>
                            @if($activity->ratings_avg_rating)
                                <div class="flex items-center">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $activity->ratings_avg_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">
                                        {{ number_format($activity->ratings_avg_rating, 1) }} ({{ $activity->ratings_count }} ulasan)
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            @auth
                                <button onclick="toggleBookmark({{ $activity->id }}, 'activity')"
                                        class="p-2 rounded-full {{ $isBookmarked ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }} hover:bg-red-100 hover:text-red-600 transition-colors">
                                    <i class="fas fa-heart"></i>
                                </button>
                            @endauth
                        </div>
                    </div>

                    <div class="prose max-w-none">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $activity->description }}</p>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Kegiatan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-green-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Kegiatan</p>
                                <p class="font-medium">{{ $activity->event_date->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-green-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Batas Pendaftaran</p>
                                <p class="font-medium">{{ $activity->registration_deadline->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users text-green-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Kapasitas</p>
                                <p class="font-medium">{{ $activity->capacity }} peserta</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-ticket-alt text-green-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Slot Tersisa</p>
                                <p class="font-medium text-green-600">{{ $activity->available_slots }} slot</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                @if($activity->ratings && $activity->ratings->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ulasan ({{ $activity->ratings->count() }})</h3>
                    <div class="space-y-4">
                        @foreach($activity->ratings as $rating)
                        <div class="border-b border-gray-200 pb-4 last:border-b-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-green-600">{{ substr($rating->user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $rating->user->name }}</p>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $rating->created_at->format('d M Y') }}</span>
                            </div>
                            @if($rating->comment)
                                <p class="text-gray-700">{{ $rating->comment }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Booking Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <div class="text-center mb-6">
                        @if($activity->discount_type && $activity->discount_value)
                            <div class="text-sm text-gray-500 line-through mb-1">
                                Rp {{ number_format($activity->price) }}
                            </div>
                            <div class="text-3xl font-bold text-green-600">
                                Rp {{ number_format($activity->getDiscountedPrice()) }}
                            </div>
                            <div class="text-sm text-green-600 font-medium">
                                @if($activity->discount_type === 'percentage')
                                    Hemat {{ $activity->discount_value }}%
                                @else
                                    Hemat Rp {{ number_format($activity->discount_value) }}
                                @endif
                            </div>
                        @else
                            <div class="text-3xl font-bold text-green-600">
                                Rp {{ number_format($activity->price) }}
                            </div>
                        @endif
                        <div class="text-sm text-gray-600">per peserta</div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Tanggal</span>
                            <span class="font-medium">{{ $activity->event_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Waktu</span>
                            <span class="font-medium">{{ $activity->event_date->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Lokasi</span>
                            <span class="font-medium text-right">{{ Str::limit($activity->location, 20) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Tersisa</span>
                            <span class="font-medium text-green-600">{{ $activity->available_slots }} slot</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Batas Daftar</span>
                            <span class="font-medium">{{ $activity->registration_deadline->format('d M Y') }}</span>
                        </div>
                    </div>

                    @if($isRegistrationOpen)
                        @if($activity->available_slots > 0)
                            @auth
                                <a href="{{ route('user.bookings.create', ['type' => 'activity', 'id' => $activity->id]) }}"
                                   class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors text-center block">
                                    <i class="fas fa-calendar-plus mr-2"></i>Daftar Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors text-center block">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Login untuk Daftar
                                </a>
                            @endauth
                        @else
                            <div class="w-full bg-gray-400 text-white py-3 px-4 rounded-lg font-medium text-center">
                                <i class="fas fa-times mr-2"></i>Kuota Penuh
                            </div>
                        @endif
                    @else
                        <div class="w-full bg-red-400 text-white py-3 px-4 rounded-lg font-medium text-center">
                            <i class="fas fa-clock mr-2"></i>Pendaftaran Ditutup
                        </div>
                    @endif
                </div>

                <!-- Provider Info -->
                <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Penyelenggara</h3>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $activity->provider->name }}</p>
                            <p class="text-sm text-gray-600">{{ $activity->provider->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@auth
@push('scripts')
<script>
function toggleBookmark(id, type) {
    fetch('{{ route("user.bookmarks.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            bookmarkable_id: id,
            bookmarkable_type: 'App\\Models\\' + type.charAt(0).toUpperCase() + type.slice(1)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function changeMainImage(src, element) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.border-green-500').forEach(el => el.classList.remove('border-green-500', 'border-white'));
    element.classList.add('border-green-500');
    element.classList.remove('border-white');
}
</script>
@endpush
@endauth
@endsection
