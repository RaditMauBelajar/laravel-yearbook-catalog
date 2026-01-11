@extends('layouts.admin')

@section('title', 'Tambah Buku')
@section('breadcrumb', 'Tambah Buku')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Buku Baru</h1>
        <p class="text-gray-600 mt-1">Isi form di bawah untuk menambahkan buku tahunan</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                Informasi Dasar
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Buku <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                        placeholder="Contoh: Buku Tahunan SMA Negeri 1 - Angkatan 2024"
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="year"
                        value="{{ old('year', date('Y')) }}"
                        required
                        min="1900"
                        max="{{ date('Y') + 5 }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('year') border-red-500 @enderror"
                    >
                    @error('year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- School Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Sekolah
                    </label>
                    <input
                        type="text"
                        name="school_name"
                        value="{{ old('school_name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('school_name') border-red-500 @enderror"
                        placeholder="Contoh: SMA Negeri 1 Jakarta"
                    >
                    @error('school_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                        placeholder="Deskripsi singkat tentang buku tahunan ini..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Files Upload -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-upload text-indigo-600 mr-2"></i>
                Upload Files
            </h2>

            <div class="space-y-6">
                <!-- Cover Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cover Buku <span class="text-red-500">*</span>
                    </label>
                    <div x-data="{ imagePreview: null }">
                        <input
                            type="file"
                            name="cover_image"
                            accept="image/*"
                            required
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = e => imagePreview = e.target.result; reader.readAsDataURL(file); }"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                        >
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Max 5MB</p>
                        <!-- Preview -->
                        <div x-show="imagePreview" class="mt-4">
                            <img :src="imagePreview" class="w-32 h-44 object-cover rounded-lg shadow-md">
                        </div>
                    </div>
                    @error('cover_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PDF File -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File PDF (Optional)
                    </label>
                    <input
                        type="file"
                        name="pdf_file"
                        accept=".pdf"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >
                    <p class="mt-1 text-xs text-gray-500">Format: PDF. Max 100MB</p>
                    @error('pdf_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Book Pages -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Halaman Buku (Multiple Images)
                    </label>
                    <input
                        type="file"
                        name="pages[]"
                        accept="image/*"
                        multiple
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Max 10MB per file. Bisa pilih multiple files sekaligus</p>
                    @error('pages.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Video URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Video URL (Optional)
                    </label>
                    <input
                        type="url"
                        name="video_url"
                        value="{{ old('video_url') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('video_url') border-red-500 @enderror"
                        placeholder="https://www.youtube.com/embed/..."
                    >
                    <p class="mt-1 text-xs text-gray-500">URL embed YouTube/Vimeo</p>
                    @error('video_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Access Credentials -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-lock text-indigo-600 mr-2"></i>
                Akses Buku
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="access_username"
                        value="{{ old('access_username') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('access_username') border-red-500 @enderror"
                        placeholder="Contoh: sma1_2024"
                    >
                    <p class="mt-1 text-xs text-gray-500">Hanya huruf, angka, dash (-), dan underscore (_)</p>
                    @error('access_username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        name="access_password"
                        required
                        minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('access_password') border-red-500 @enderror"
                        placeholder="Minimal 6 karakter"
                    >
                    @error('access_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-toggle-on text-indigo-600 mr-2"></i>
                Status
            </h2>

            <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="show" {{ old('status', 'show') == 'show' ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700"><i class="fas fa-eye text-green-600 mr-2"></i>Visible (Tampilkan di katalog)</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="hide" {{ old('status') == 'hide' ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700"><i class="fas fa-eye-slash text-red-600 mr-2"></i>Hidden (Sembunyikan)</span>
                </label>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.books.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save mr-2"></i>Simpan Buku
            </button>
        </div>
    </form>

</div>
@endsection
