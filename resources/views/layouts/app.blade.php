<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Book Catalog') }} - @yield('title', 'Yearbook Catalog')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3">
                            <i class="fas fa-book text-indigo-600 text-2xl"></i>
                            <span class="text-xl font-bold text-gray-900">YearbookHub</span>
                        </a>
                    </div>

                    <!-- Search Bar (Desktop) -->
                    <div class="hidden md:flex items-center flex-1 max-w-md mx-8">
                        <form action="{{ route('home') }}" method="GET" class="w-full">
                            <div class="relative">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Cari buku tahunan..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                >
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </form>
                    </div>

                    <!-- Right Menu -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <!-- Admin Menu -->
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-dashboard mr-2"></i>Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-user mr-2"></i>Admin Login
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Search -->
                <div class="md:hidden pb-3">
                    <form action="{{ route('home') }}" method="GET">
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari buku tahunan..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            >
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Alert Messages -->
        @include('components.alert')

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- About -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tentang YearbookHub</h3>
                        <p class="text-gray-600 text-sm">
                            Platform digital untuk menyimpan dan membaca buku tahunan sekolah.
                            Kenangan indah dapat diakses kapan saja, di mana saja.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600">Beranda</a></li>
                            <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600">Katalog Buku</a></li>
                            @auth
                                <li><a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-indigo-600">Admin Panel</a></li>
                            @endauth
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Kontak</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><i class="fas fa-envelope mr-2 text-indigo-600"></i> info@yearbookhub.com</li>
                            <li><i class="fas fa-phone mr-2 text-indigo-600"></i> +62 123-456-7890</li>
                            <li><i class="fas fa-map-marker-alt mr-2 text-indigo-600"></i> Jakarta, Indonesia</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-8 pt-6 text-center">
                    <p class="text-gray-500 text-sm">
                        &copy; {{ date('Y') }} YearbookHub. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
