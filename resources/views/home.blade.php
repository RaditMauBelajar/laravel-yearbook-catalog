@extends('layouts.app')

@section('title', 'Katalog Buku Tahunan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Katalog Buku Tahunan
        </h1>
        <p class="text-lg text-gray-600">
            Kenangan indah dalam setiap halaman. Temukan buku tahunan sekolahmu di sini.
        </p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form action="{{ route('home') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Buku</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul, sekolah, atau tahun..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
            </div>

            <!-- Filter Year -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter School -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sekolah</label>
                <select name="school" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Semua Sekolah</option>
                    @foreach($schools as $school)
                        <option value="{{ $school }}" {{ request('school') == $school ? 'selected' : '' }}>
                            {{ $school }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="md:col-span-4 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Ditemukan <span class="font-semibold text-indigo-600">{{ $books->total() }}</span> buku
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Books Grid -->
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($books as $book)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <!-- Cover Image -->
                    <div class="relative h-64 bg-gray-200 overflow-hidden group">
                        <img
                            src="{{ $book->cover_url }}"
                            alt="{{ $book->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                            onerror="this.src='https://via.placeholder.com/400x600/6366f1/ffffff?text=No+Cover'"
                        >

                        <!-- Overlay on Hover -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition duration-300 flex items-center justify-center">
                            <a href="{{ route('book.login', $book->id) }}"
                               class="opacity-0 group-hover:opacity-100 transform scale-90 group-hover:scale-100 transition duration-300 px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700">
                                <i class="fas fa-book-open mr-2"></i>Buka Buku
                            </a>
                        </div>

                        <!-- Year Badge -->
                        <div class="absolute top-3 right-3 bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                            {{ $book->year }}
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2" title="{{ $book->title }}">
                            {{ $book->title }}
                        </h3>

                        @if($book->school_name)
                            <p class="text-sm text-gray-600 mb-3 flex items-center">
                                <i class="fas fa-school mr-2 text-indigo-600"></i>
                                {{ $book->school_name }}
                            </p>
                        @endif

                        <!-- Stats -->
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span>
                                <i class="fas fa-eye mr-1"></i>
                                {{ $book->view_count }} views
                            </span>
                            <span>
                                <i class="fas fa-download mr-1"></i>
                                {{ $book->download_count }} downloads
                            </span>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('book.login', $book->id) }}"
                           class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                            <i class="fas fa-lock mr-2"></i>Akses Buku
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                <i class="fas fa-book text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                Tidak Ada Buku Ditemukan
            </h3>
            <p class="text-gray-600 mb-6">
                Coba ubah filter pencarian atau kata kunci yang berbeda.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-redo mr-2"></i>
                Reset Filter
            </a>
        </div>
    @endif

</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection
