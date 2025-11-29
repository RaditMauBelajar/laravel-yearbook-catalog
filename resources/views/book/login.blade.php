@extends('layouts.app')

@section('title', 'Login - ' . $book->title)

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-br from-indigo-50 to-blue-50">
    <div class="max-w-4xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">

                <!-- Left Side - Book Preview -->
                <div class="relative bg-gradient-to-br from-indigo-600 to-purple-600 p-8 flex flex-col justify-center items-center text-white">
                    <div class="absolute inset-0 bg-black opacity-10"></div>

                    <div class="relative z-10 text-center">
                        <!-- Book Cover -->
                        <div class="mb-6 transform hover:scale-105 transition duration-300">
                            <img
                                src="{{ $book->cover_url }}"
                                alt="{{ $book->title }}"
                                class="w-48 h-64 object-cover rounded-lg shadow-2xl mx-auto border-4 border-white"
                                onerror="this.src='https://via.placeholder.com/400x600/6366f1/ffffff?text=No+Cover'"
                            >
                        </div>

                        <!-- Book Info -->
                        <h2 class="text-2xl font-bold mb-2">{{ $book->title }}</h2>

                        @if($book->school_name)
                            <p class="text-indigo-100 mb-2">
                                <i class="fas fa-school mr-2"></i>{{ $book->school_name }}
                            </p>
                        @endif

                        <p class="text-indigo-200 text-sm">
                            <i class="fas fa-calendar mr-2"></i>Tahun {{ $book->year }}
                        </p>

                        @if($book->description)
                            <div class="mt-4 px-4">
                                <p class="text-sm text-indigo-100 line-clamp-3">
                                    {{ $book->description }}
                                </p>
                            </div>
                        @endif

                        <!-- Stats -->
                        <div class="flex justify-center gap-6 mt-6 text-sm">
                            <div>
                                <i class="fas fa-eye mb-1"></i>
                                <p class="font-semibold">{{ $book->view_count }}</p>
                                <p class="text-xs text-indigo-200">Views</p>
                            </div>
                            <div>
                                <i class="fas fa-download mb-1"></i>
                                <p class="font-semibold">{{ $book->download_count }}</p>
                                <p class="text-xs text-indigo-200">Downloads</p>
                            </div>
                            <div>
                                <i class="fas fa-file-alt mb-1"></i>
                                <p class="font-semibold">{{ $book->pages->count() }}</p>
                                <p class="text-xs text-indigo-200">Halaman</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="p-8 md:p-12">
                    <div class="mb-8">
                        <h3 class="text-3xl font-bold text-gray-900 mb-2">
                            <i class="fas fa-lock text-indigo-600 mr-2"></i>Akses Buku
                        </h3>
                        <p class="text-gray-600">
                            Masukkan username dan password untuk membuka buku ini
                        </p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('book.login.submit', $book->id) }}" class="space-y-6">
                        @csrf

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-indigo-600"></i>Username
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('username') border-red-500 @enderror"
                                placeholder="Masukkan username"
                            >
                            @error('username')
                                <p class="mt-2 text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-2 text-indigo-600"></i>Password
                            </label>
                            <div class="relative" x-data="{ showPassword: false }">
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                                    placeholder="Masukkan password"
                                >
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700"
                                >
                                    <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-1">Informasi Login</p>
                                    <p>Username dan password buku ini diberikan oleh sekolah atau pengelola buku tahunan.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition transform hover:scale-105 active:scale-95"
                        >
                            <i class="fas fa-sign-in-alt mr-2"></i>Buka Buku
                        </button>

                        <!-- Back Link -->
                        <div class="text-center">
                            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection
