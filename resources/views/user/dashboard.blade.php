@extends('layouts.app')

@section('title', 'Dashboard - Infoma')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600 mt-1">Kelola booking dan temukan residence serta kegiatan terbaik</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-user text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('residences.index') }}"
               class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200 group">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-lg p-3 group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-building text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Residence</h3>
                        <p class="text-gray-600 text-sm">Cari tempat tinggal</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('activities.index') }}"
               class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200 group">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-lg p-3 group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Kegiatan</h3>
                        <p class="text-gray-600 text-sm">Ikuti kegiatan kampus</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('user.bookings.index') }}"
               class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200 group">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-lg p-3 group-hover:bg-yellow-200 transition-colors">
                        <i class="fas fa-bookmark text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Booking Saya</h3>
                        <p class="text-gray-600 text-sm">Kelola booking</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('user.bookmarks.index') }}"
               class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200 group">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-lg p-3 group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-heart text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Bookmark</h3>
                        <p class="text-gray-600 text-sm">Item tersimpan</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Booking Terbaru</h2>
                </div>
                <div class="p-6">
                    @if(auth()->user()->bookings()->count() > 0)
                        <div class="space-y-4">
                            @foreach(auth()->user()->bookings()->latest()->limit(3)->get() as $booking)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 rounded-lg p-2 mr-3">
                                        <i class="fas fa-{{ $booking->bookable_type === 'App\\Models\\Residence' ? 'building' : 'calendar-alt' }} text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $booking->bookable->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $booking->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                    @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('user.bookings.index') }}"
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Lihat semua booking →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-bookmark text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">Belum ada booking</p>
                            <a href="{{ route('residences.index') }}"
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 inline-block">
                                Mulai booking sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Statistik</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ auth()->user()->bookings()->count() }}</div>
                            <div class="text-sm text-gray-600">Total Booking</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ auth()->user()->bookmarks()->count() }}</div>
                            <div class="text-sm text-gray-600">Bookmark</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ auth()->user()->bookings()->where('status', 'pending')->count() }}</div>
                            <div class="text-sm text-gray-600">Pending</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ auth()->user()->bookings()->where('status', 'approved')->count() }}</div>
                            <div class="text-sm text-gray-600">Disetujui</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
