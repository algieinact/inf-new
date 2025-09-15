<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Infoma - Informasi Kebutuhan Mahasiswa')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#1E40AF',
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/Infoma_Branding.png') }}">

    <!-- Additional CSS -->
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    @include('components.navbar')

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-4 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mx-4 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mx-4 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mx-4 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('info') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                        <img src="{{ asset('images/Infoma_Branding-05.png') }}" alt="Infoma Logo" class="w-6 h-6">
                        Infoma
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">Platform terpercaya untuk mahasiswa dalam mencari tempat
                        tinggal dan kegiatan kampus. Memudahkan kehidupan mahasiswa dengan teknologi modern.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-blue-800 transition-colors"><i
                                class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-800 transition-colors"><i
                                class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-800 transition-colors"><i
                                class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-800 transition-colors"><i
                                class="fab fa-linkedin text-xl"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-6">Layanan</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('residences.index') }}" class="text-gray-400 hover:text-white transition-colors">Residence</a></li>
                        <li><a href="{{ route('activities.index') }}" class="text-gray-400 hover:text-white transition-colors">Kegiatan Kampus</a></li>
                        <li><a href="{{ route('search') }}" class="text-gray-400 hover:text-white transition-colors">Cari</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Customer Support</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-6">Kontak</h3>
                    <ul class="space-y-3">
                        <li class="text-gray-400"><i class="fas fa-envelope mr-2"></i>info@infoma.com</li>
                        <li class="text-gray-400"><i class="fas fa-phone mr-2"></i>+62 123 456 7890</li>
                        <li class="text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i>Jakarta, Indonesia</li>
                        <li class="text-gray-400"><i class="fas fa-clock mr-2"></i>24/7 Support</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Infoma. All rights reserved. Made with ❤️ for Indonesian students.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Auto hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100, .bg-yellow-100, .bg-blue-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Add scroll effect to navbar
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('bg-white/95', 'backdrop-blur-sm');
            } else {
                navbar.classList.remove('bg-white/95', 'backdrop-blur-sm');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
