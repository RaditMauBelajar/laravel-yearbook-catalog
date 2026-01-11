@extends('layouts.admin')

@section('title', 'Edit Buku')
@section('breadcrumb', 'Edit Buku')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Buku</h1>
        <p class="text-gray-600 mt-1">{{ $book->title }}</p>
    </div>

    <form id="editBookForm" action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Info Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4"> Informasi Dasar</h2>

            <div class="space-y-4">
                <div>
                    <label class="block font-medium mb-2">Judul Buku *</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-2">Tahun *</label>
                        <input type="number" name="year" value="{{ old('year', $book->year) }}" required
                               class="w-full px-4 py-2 border rounded-lg">
                        @error('year')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-medium mb-2">Nama Sekolah</label>
                        <input type="text" name="school_name" value="{{ old('school_name', $book->school_name) }}"
                               class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg">{{ old('description', $book->description) }}</textarea>
                </div>

                <div>
                    <label class="block font-medium mb-2">Video URL (YouTube Embed)</label>
                    <input type="url" name="video_url" value="{{ old('video_url', $book->video_url) }}"
                           placeholder="https://www.youtube.com/embed/VIDEO_ID"
                           class="w-full px-4 py-2 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Format: https://www.youtube.com/embed/VIDEO_ID</p>
                </div>
            </div>
        </div>

        <!-- Files Upload Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4"> Files</h2>

            <div class="space-y-4">
                <div>
                    <label class="block font-medium mb-2">Cover Saat Ini</label>
                    <img src="{{ $book->cover_url }}" alt="Cover" class="w-32 h-44 object-cover rounded shadow mb-2">
                    <label class="block font-medium mb-2">Ganti Cover (opsional)</label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full">
                    <p class="text-xs text-gray-500 mt-1">Max 5MB</p>
                </div>

                <div>
                    <label class="block font-medium mb-2">PDF File</label>
                    @if($book->hasPdf())
                        <p class="text-sm text-green-700 mb-2">✓ PDF tersedia ({{ $book->pdf_size_formatted }})</p>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada PDF</p>
                    @endif
                    <input type="file" name="pdf_file" accept=".pdf" class="w-full">
                    <p class="text-xs text-gray-500 mt-1">Max 100MB</p>
                </div>

                @if($book->pages->count() > 0)
                <div>
                    <label class="block font-medium mb-2">Halaman ({{ $book->pages->count() }})</label>
                    <div class="grid grid-cols-6 gap-2 mb-2">
                        @foreach($book->pages->take(12) as $page)
                            <img src="{{ $page->image_url }}" class="w-full h-20 object-cover rounded">
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <label class="block font-medium mb-2">Tambah Halaman Baru</label>
                    <input type="file" name="pages[]" accept="image/*" multiple class="w-full">
                </div>
            </div>
        </div>

        <!-- Access Credentials Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4"> Akses Buku</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-2">Username *</label>
                    <input type="text" name="access_username" value="{{ old('access_username', $book->access_username) }}" required
                           class="w-full px-4 py-2 border rounded-lg">
                    @error('access_username')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block font-medium mb-2">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" name="access_password" minlength="6"
                           class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4"> Status</h2>

            <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="show" {{ $book->status == 'show' ? 'checked' : '' }} class="mr-2">
                    <span> Visible</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="hide" {{ $book->status == 'hide' ? 'checked' : '' }} class="mr-2">
                    <span> Hidden</span>
                </label>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.books.index') }}"
                   class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium">
                     Batal
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">
                     UPDATE BUKU
                </button>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
// Debug info
console.log('Edit form loaded');
console.log('Form action:', document.getElementById('editBookForm')?.action);
console.log('Form method:', document.getElementById('editBookForm')?.method);

// Prevent double submit
let isSubmitting = false;
document.getElementById('editBookForm').addEventListener('submit', function(e) {
    if (isSubmitting) {
        e.preventDefault();
        return false;
    }
    isSubmitting = true;
    console.log('Form submitting...');
});
</script>
@endpush
@endsection
