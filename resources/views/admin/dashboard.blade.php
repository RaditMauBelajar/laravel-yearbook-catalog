@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

<!-- Welcome Section -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        Selamat Datang, {{ Auth::user()->name }}! 👋
    </h1>
    <p class="text-gray-600">Berikut adalah ringkasan sistem buku tahunan Anda.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

    <!-- Total Books -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Buku</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_books'] }}</p>
            </div>
            <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center">
                <i class="fas fa-book text-indigo-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.books.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Lihat semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Visible Books -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Buku Visible</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['visible_books'] }}</p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-eye text-green-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">
                <span class="text-green-600 font-semibold">{{ $stats['visible_books'] > 0 ? round(($stats['visible_books'] / $stats['total_books']) * 100) : 0 }}%</span> dari total buku
            </p>
        </div>
    </div>

    <!-- Hidden Books -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Buku Hidden</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['hidden_books'] }}</p>
            </div>
            <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-eye-slash text-gray-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Tidak ditampilkan di katalog</p>
        </div>
    </div>

    <!-- Total Views -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Views</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_views']) }}</p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Total kunjungan buku</p>
        </div>
    </div>

    <!-- Total Downloads -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Downloads</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_downloads']) }}</p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-download text-purple-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Total unduhan PDF</p>
        </div>
    </div>

    <!-- Total Pages -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Halaman</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_pages']) }}</p>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-file-alt text-yellow-600 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Halaman di semua buku</p>
        </div>
    </div>

</div>

<!-- Charts & Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- Views Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-chart-area text-indigo-600 mr-2"></i>
            Views 6 Bulan Terakhir
        </h3>
        <div style="height: 250px;">
            <canvas id="viewsChart"></canvas>
        </div>
    </div>

    <!-- Books Per Year -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-chart-pie text-indigo-600 mr-2"></i>
            Buku per Tahun
        </h3>
        <div style="height: 250px;">
            <canvas id="booksPerYearChart"></canvas>
        </div>
    </div>

</div>

<!-- Popular Books & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Popular Books -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-fire text-orange-500 mr-2"></i>
            Top 5 Buku Populer
        </h3>
        <div class="space-y-4">
            @forelse($popularBooks as $index => $book)
                <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition">
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-indigo-600 font-bold text-sm">{{ $index + 1 }}</span>
                    </div>
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-12 h-16 object-cover rounded shadow">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $book->title }}</p>
                        <p class="text-xs text-gray-500">{{ $book->year }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-indigo-600">{{ $book->view_count }}</p>
                        <p class="text-xs text-gray-500">views</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Belum ada data views</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-clock text-blue-500 mr-2"></i>
            Aktivitas Terbaru
        </h3>
        <div class="space-y-3">
            @forelse($recentViews->take(5) as $view)
                <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg transition">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-eye text-blue-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">{{ $view->book->title }}</span> dilihat
                        </p>
                        <p class="text-xs text-gray-500">{{ $view->viewed_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Belum ada aktivitas</p>
            @endforelse

            @foreach($recentDownloads->take(3) as $download)
                <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg transition">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-download text-green-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">{{ $download->book->title }}</span> diunduh
                        </p>
                        <p class="text-xs text-gray-500">{{ $download->downloaded_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Quick Actions -->
<div class="mt-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
    <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="mb-4 md:mb-0">
            <h3 class="text-xl font-bold mb-2">Siap menambah buku baru?</h3>
            <p class="text-indigo-100">Upload buku tahunan dan bagikan kenangan dengan siswa</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition">
            <i class="fas fa-plus mr-2"></i>Tambah Buku Baru
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Views Chart
const viewsCtx = document.getElementById('viewsChart');
if (viewsCtx) {
    new Chart(viewsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($viewsChart, 'month')) !!},
            datasets: [{
                label: 'Views',
                data: {!! json_encode(array_column($viewsChart, 'count')) !!},
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Books Per Year Chart
const booksCtx = document.getElementById('booksPerYearChart');
if (booksCtx) {
    new Chart(booksCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($booksPerYear->pluck('year')->toArray()) !!},
            datasets: [{
                label: 'Jumlah Buku',
                data: {!! json_encode($booksPerYear->pluck('count')->toArray()) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderColor: 'rgb(99, 102, 241)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}
</script>
@endpush
