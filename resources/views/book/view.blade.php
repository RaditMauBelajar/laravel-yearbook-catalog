@extends('layouts.app')

@section('title', $book->title)

@section('content')
<style>
/* 3D Book Page Curl Animation - Sisi kiri tetap, sisi kanan curl */
.book-page {
    transform-style: preserve-3d;
    backface-visibility: hidden;
}

/* Animation untuk flip halaman ke depan (next page) - Curl dari kanan, kiri tetap */
@keyframes flipNext {
    0% {
        transform: perspective(2500px) rotateY(0deg);
        transform-origin: left center;
        box-shadow: 0 10px 50px rgba(0,0,0,0.3);
    }
    50% {
        transform: perspective(2500px) rotateY(-90deg) translateZ(100px);
        transform-origin: left center;
        box-shadow: -30px 10px 80px rgba(0,0,0,0.6);
    }
    100% {
        transform: perspective(2500px) rotateY(-180deg);
        transform-origin: left center;
        box-shadow: 0 10px 50px rgba(0,0,0,0.3);
    }
}

/* Animation untuk flip halaman ke belakang (previous page) - Curl dari kiri ke kanan */
@keyframes flipPrev {
    0% {
        transform: perspective(2500px) rotateY(-180deg);
        transform-origin: left center;
        box-shadow: 0 10px 50px rgba(0,0,0,0.3);
    }
    50% {
        transform: perspective(2500px) rotateY(-90deg) translateZ(100px);
        transform-origin: left center;
        box-shadow: -30px 10px 80px rgba(0,0,0,0.6);
    }
    100% {
        transform: perspective(2500px) rotateY(0deg);
        transform-origin: left center;
        box-shadow: 0 10px 50px rgba(0,0,0,0.3);
    }
}

.page-flip-next {
    animation: flipNext 2s ease-in-out;
}

.page-flip-prev {
    animation: flipPrev 2s ease-in-out;
}
</style>

<div class="min-h-screen bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-16 h-20 object-cover rounded shadow-lg">
                    <div class="text-white">
                        <h1 class="text-xl font-bold">{{ $book->title }}</h1>
                        <p class="text-sm text-gray-400">{{ $book->school_name }} - {{ $book->year }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    @if($book->hasPdf())
                        <a href="{{ route('book.download', $book->id) }}"
                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </a>
                    @endif

                    @if($book->hasVideo())
                        <button
                            onclick="openVideoModal()"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                            <i class="fab fa-youtube mr-2"></i>Tonton Video
                        </button>
                    @endif

                    <form method="POST" action="{{ route('book.logout', $book->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Simple Flipbook -->
        <div class="bg-gray-800 rounded-lg shadow-2xl p-6" x-data="simpleFlipbook()">

            <!-- Controls -->
            <div class="flex items-center justify-between mb-4 text-white">
                <div class="flex items-center space-x-4">
                    <button @click="goToPage(1)" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-700'" class="p-2 rounded transition">
                        <i class="fas fa-fast-backward"></i>
                    </button>
                    <button @click="previousPage()" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-700'" class="p-2 rounded transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="px-4 py-2 bg-gray-700 rounded text-sm font-medium">
                        Halaman <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
                    </div>
                    <button @click="nextPage()" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-700'" class="p-2 rounded transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button @click="goToPage(totalPages)" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-700'" class="p-2 rounded transition">
                        <i class="fas fa-fast-forward"></i>
                    </button>
                </div>

                <div class="flex items-center space-x-2">
                    <button @click="zoomOut()" class="p-2 hover:bg-gray-700 rounded transition">
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <span class="px-3 py-1 bg-gray-700 rounded text-sm" x-text="zoom + '%'"></span>
                    <button @click="zoomIn()" class="p-2 hover:bg-gray-700 rounded transition">
                        <i class="fas fa-search-plus"></i>
                    </button>
                </div>
            </div>

            <!-- Page Viewer with 3D Flip Effect -->
            <div class="relative bg-gray-900 rounded-lg overflow-hidden" style="min-height: 600px; max-height: 80vh; perspective: 2500px;">
                <div class="flex items-center justify-center p-8 h-full">
                    @if($book->pages->count() > 0)
                        @foreach($book->pages as $page)
                            <div x-show="currentPage === {{ $page->page_number }}"
                                 x-transition:enter="transition-all duration-2000 ease-in-out"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 :class="pageDirection === 'next' ? 'page-flip-next' : 'page-flip-prev'"
                                 class="bg-white rounded-lg shadow-2xl overflow-hidden book-page"
                                 :style="'transform: scale(' + (zoom/100) + '); max-width: 100%;'">
                                <img
                                    src="{{ $page->image_url }}"
                                    alt="Page {{ $page->page_number }}"
                                    class="max-w-full h-auto"
                                    style="max-height: 70vh;"
                                    loading="lazy"
                                >
                            </div>
                        @endforeach
                    @else
                        <div class="text-white text-center py-12">
                            <i class="fas fa-book-open text-4xl mb-4"></i>
                            <p>Tidak ada halaman tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mobile Controls -->
            <div class="flex md:hidden items-center justify-center space-x-4 mt-4 text-white">
                <button @click="previousPage()" class="px-6 py-3 bg-indigo-600 rounded-lg">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="px-4 py-2 bg-gray-700 rounded">
                    <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
                </div>
                <button @click="nextPage()" class="px-6 py-3 bg-indigo-600 rounded-lg">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

        </div>

    </div>
</div>

<!-- Video Modal (Pure JavaScript - No Alpine) -->
@if($book->hasVideo())
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 items-center justify-center p-4 hidden" style="z-index: 9999;">
    <div class="relative w-full max-w-5xl">
        <!-- Close Button -->
        <button
            onclick="closeVideoModal()"
            class="absolute -top-12 right-0 text-white hover:text-red-500 transition text-3xl font-bold z-10">
            <i class="fas fa-times-circle"></i>
        </button>

        <!-- Video Container -->
        <div class="bg-gray-900 rounded-lg overflow-hidden shadow-2xl">
            <div class="relative" style="padding-bottom: 56.25%; height: 0;">
                <iframe
                    id="youtubePlayer"
                    src=""
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                    referrerpolicy="strict-origin-when-cross-origin"
                    class="absolute top-0 left-0 w-full h-full">
                </iframe>
            </div>
        </div>

        <!-- Info -->
        <div class="text-center mt-4 text-white">
            <p class="text-sm text-gray-400">
                <i class="fab fa-youtube text-red-500 mr-2"></i>
                Video Yearbook {{ $book->title }}
            </p>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
// Flipbook Controller
function simpleFlipbook() {
    return {
        currentPage: 1,
        totalPages: {{ $book->pages->count() }},
        zoom: 100,
        pageDirection: 'next', // Track direction for flip animation

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.pageDirection = 'next';
                this.currentPage++;
            }
        },

        previousPage() {
            if (this.currentPage > 1) {
                this.pageDirection = 'prev';
                this.currentPage--;
            }
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.pageDirection = page > this.currentPage ? 'next' : 'prev';
                this.currentPage = page;
            }
        },

        zoomIn() {
            if (this.zoom < 150) {
                this.zoom += 10;
            }
        },

        zoomOut() {
            if (this.zoom > 50) {
                this.zoom -= 10;
            }
        }
    }
}

// Video Modal Functions (Pure JavaScript)
@if($book->hasVideo())
const videoUrl = "{{ $book->video_url }}";

function openVideoModal() {
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('youtubePlayer');

    // Extract video ID dengan regex yang lebih reliable
    let videoId = '';

    // Regex untuk extract YouTube video ID dari berbagai format
    const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = videoUrl.match(regExp);

    if (match && match[2].length === 11) {
        videoId = match[2];
    } else {
        // Fallback manual parsing
        if (videoUrl.includes('embed/')) {
            videoId = videoUrl.split('embed/')[1].split('?')[0].split('&')[0].split('/')[0];
        } else if (videoUrl.includes('watch?v=')) {
            videoId = videoUrl.split('watch?v=')[1].split('&')[0];
        } else if (videoUrl.includes('youtu.be/')) {
            videoId = videoUrl.split('youtu.be/')[1].split('?')[0].split('&')[0];
        }
    }

    console.log('Original URL:', videoUrl);
    console.log('Extracted Video ID:', videoId);

    if (!videoId) {
        alert('Invalid YouTube URL');
        return;
    }

    // Buat URL embed yang bersih
    const cleanUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`;

    console.log('Clean embed URL:', cleanUrl);

    // Set iframe src dan show modal
    iframe.src = cleanUrl;
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeVideoModal() {
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('youtubePlayer');

    // Stop video by removing src
    iframe.src = '';

    // Hide modal
    modal.classList.add('hidden');
    modal.classList.remove('flex');

    // Enable body scroll
    document.body.style.overflow = '';
}

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVideoModal();
    }
});

// Close on click outside
document.getElementById('videoModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeVideoModal();
    }
});
@endif

// Keyboard navigation for flipbook
document.addEventListener('keydown', (e) => {
    // Jangan handle arrow keys kalau video modal terbuka
    if (!document.getElementById('videoModal')?.classList.contains('hidden')) {
        return;
    }

    if (e.key === 'ArrowLeft') {
        const event = new CustomEvent('previous-page');
        window.dispatchEvent(event);
    } else if (e.key === 'ArrowRight') {
        const event = new CustomEvent('next-page');
        window.dispatchEvent(event);
    }
});
</script>
@endpush
@endsection
