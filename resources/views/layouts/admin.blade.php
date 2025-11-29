<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform transition-transform duration-300 lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex items-center justify-between h-16 px-6 bg-gray-800">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-book text-indigo-400 text-xl"></i>
                    <span class="text-lg font-bold">Admin Panel</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-dashboard w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.books.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.books.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-book w-5"></i>
                    <span class="ml-3">Manage Books</span>
                </a>
                <div class="border-t border-gray-700 my-4"></div>
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition">
                    <i class="fas fa-arrow-left w-5"></i>
                    <span class="ml-3">Back to Site</span>
                </a>
            </nav>
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-800">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>
        <div class="flex-1 flex flex-col lg:ml-64">
            <header class="bg-white shadow-sm sticky top-0 z-40">
                <div class="flex items-center justify-between h-16 px-6">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="hidden md:block">
                        <nav class="flex items-center space-x-2 text-sm">
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-home"></i>
                            </a>
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            <span class="text-gray-900 font-medium">@yield('breadcrumb', 'Dashboard')</span>
                        </nav>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit mr-2"></i>Edit Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            @include('components.alert')
            <main class="flex-1 p-6">
                @yield('content')
            </main>
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} YearbookHub Admin Panel. All rights reserved.
                </div>
            </footer>
        </div>
    </div>
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>
    @stack('scripts')
</body>
</html>
