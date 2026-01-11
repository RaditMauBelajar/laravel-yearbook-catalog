@extends('layouts.admin')

@section('title', 'Manage Books')
@section('breadcrumb', 'Books')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Books</h1>
            <p class="text-gray-600 mt-1">Kelola semua buku tahunan</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Tambah Buku
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.books.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Search -->
            <div class="md:col-span-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul atau sekolah..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <!-- Filter Year -->
            <div>
                <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="show" {{ request('status') == 'show' ? 'selected' : '' }}>Visible</option>
                    <option value="hide" {{ request('status') == 'hide' ? 'selected' : '' }}>Hidden</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-4 flex justify-end gap-2">
                <a href="{{ route('admin.books.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Books Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($books->count() > 0)
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($books as $book)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Cover -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-12 h-16 object-cover rounded shadow-sm">
                                </td>

                                <!-- Book Info -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <p class="text-sm font-medium text-gray-900">{{ $book->title }}</p>
                                        @if($book->school_name)
                                            <p class="text-sm text-gray-500">{{ $book->school_name }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ $book->pages->count() }} halaman</p>
                                    </div>
                                </td>

                                <!-- Year -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">{{ $book->year }}</span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.books.toggle', $book) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 text-xs font-semibold rounded-full transition {{ $book->status === 'show' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                            <i class="fas {{ $book->status === 'show' ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                            {{ $book->status === 'show' ? 'Visible' : 'Hidden' }}
                                        </button>
                                    </form>
                                </td>

                                <!-- Stats -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col text-xs text-gray-600">
                                        <span><i class="fas fa-eye mr-1 text-blue-500"></i>{{ $book->view_count }} views</span>
                                        <span><i class="fas fa-download mr-1 text-green-500"></i>{{ $book->download_count }} downloads</span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.books.edit', $book) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('book.login', $book) }}" target="_blank" class="text-green-600 hover:text-green-900" title="Preview">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden p-4 space-y-4">
                @foreach($books as $book)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex gap-4 mb-3">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-20 h-28 object-cover rounded">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $book->title }}</h3>
                                @if($book->school_name)
                                    <p class="text-sm text-gray-600 mb-1">{{ $book->school_name }}</p>
                                @endif
                                <p class="text-sm text-gray-500">Tahun {{ $book->year }}</p>
                                <form action="{{ route('admin.books.toggle', $book) }}" method="POST" class="inline mt-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $book->status === 'show' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $book->status === 'show' ? 'Visible' : 'Hidden' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <div class="text-gray-600">
                                <span class="mr-3"><i class="fas fa-eye mr-1"></i>{{ $book->view_count }}</span>
                                <span><i class="fas fa-download mr-1"></i>{{ $book->download_count }}</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.books.edit', $book) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $books->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-book text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Buku</h3>
                <p class="text-gray-600 mb-6">Mulai tambahkan buku tahunan pertama Anda</p>
                <a href="{{ route('admin.books.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i>Tambah Buku
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
