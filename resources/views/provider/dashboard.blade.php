@extends('layouts.app')

@section('title', 'Provider Dashboard - Infoma')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600 mt-1">Kelola residence, kegiatan, dan booking Anda</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-store text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-lg p-3">
                        <i class="fas fa-building text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_residences'] }}</h3>
                        <p class="text-gray-600 text-sm">Total Residence</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-lg p-3">
                        <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_activities'] }}</h3>
                        <p class="text-gray-600 text-sm">Total Kegiatan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-lg p-3">
                        <i class="fas fa-bookmark text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</h3>
                        <p class="text-gray-600 text-sm">Total Booking</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-lg p-3">
                        <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue']) }}</h3>
                        <p class="text-gray-600 text-sm">Total Pendapatan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('provider.residences.create') }}"
               class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200 group">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-lg p-3 group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-plus text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tambah Residence</h3>
                        <p class="text-gray-600 text-sm">Buat residence baru</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('provider.activities.create') }}"
               class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200 group">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-lg p-3 group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-plus text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tambah Kegiatan</h3>
                        <p class="text-gray-600 text-sm">Buat kegiatan baru</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('provider.bookings.index') }}"
               class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200 group">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-lg p-3 group-hover:bg-yellow-200 transition-colors">
                        <i class="fas fa-tasks text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Kelola Booking</h3>
                        <p class="text-gray-600 text-sm">Approve/reject booking</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('provider.residences.index') }}"
               class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200 group">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-lg p-3 group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-list text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Lihat Semua</h3>
                        <p class="text-gray-600 text-sm">Residence & kegiatan</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Booking Terbaru</h2>
                        <a href="{{ route('provider.bookings.index') }}"
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Lihat semua →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 rounded-lg p-2 mr-3">
                                        <i class="fas fa-{{ $booking->bookable_type === 'App\\Models\\Residence' ? 'building' : 'calendar-alt' }} text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $booking->bookable->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $booking->user->name }} • {{ $booking->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                        @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <a href="{{ route('provider.bookings.show', $booking) }}"
                                       class="text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-bookmark text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">Belum ada booking</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Items -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Item Terbaru</h2>
                        <a href="{{ route('provider.residences.index') }}"
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Lihat semua →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentItems->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentItems as $item)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-{{ $item instanceof App\Models\Residence ? 'blue' : 'green' }}-100 rounded-lg p-2 mr-3">
                                        <i class="fas fa-{{ $item instanceof App\Models\Residence ? 'building' : 'calendar-alt' }} text-{{ $item instanceof App\Models\Residence ? 'blue' : 'green' }}-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $item->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $item instanceof App\Models\Residence ? 'Residence' : 'Kegiatan' }} •
                                            {{ $item->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                    <a href="{{ $item instanceof App\Models\Residence ? route('provider.residences.show', $item) : route('provider.activities.show', $item) }}"
                                       class="text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-plus text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">Belum ada item</p>
                            <a href="{{ route('provider.residences.create') }}"
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 inline-block">
                                Buat residence pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Performance Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Performa Bulan Ini</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['monthly_bookings'] }}</div>
                    <div class="text-sm text-gray-600">Booking Bulan Ini</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">Rp {{ number_format($stats['monthly_revenue']) }}</div>
                    <div class="text-sm text-gray-600">Pendapatan Bulan Ini</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $stats['approval_rate'] }}%</div>
                    <div class="text-sm text-gray-600">Tingkat Persetujuan</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
