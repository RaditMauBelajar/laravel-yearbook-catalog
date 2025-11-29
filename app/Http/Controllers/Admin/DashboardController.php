<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookView;
use App\Models\BookDownload;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $stats = [
            'total_books' => Book::count(),
            'visible_books' => Book::visible()->count(),
            'hidden_books' => Book::hidden()->count(),
            'total_views' => Book::sum('view_count'),
            'total_downloads' => Book::sum('download_count'),
            'total_pages' => \App\Models\BookPage::count(),
        ];

        // Buku paling populer (berdasarkan views)
        $popularBooks = Book::orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        // Buku terbaru
        $latestBooks = Book::latest()
            ->limit(5)
            ->get();

        // View terakhir (last 10)
        $recentViews = BookView::with('book')
            ->latest('viewed_at')
            ->limit(10)
            ->get();

        // Download terakhir (last 10)
        $recentDownloads = BookDownload::with('book')
            ->latest('downloaded_at')
            ->limit(10)
            ->get();

        // Chart data: Views per bulan (6 bulan terakhir)
        $viewsChart = $this->getMonthlyViews();

        // Chart data: Books per tahun
        $booksPerYear = Book::selectRaw('year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'popularBooks',
            'latestBooks',
            'recentViews',
            'recentDownloads',
            'viewsChart',
            'booksPerYear'
        ));
    }

    private function getMonthlyViews()
    {
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');

            $count = BookView::whereYear('viewed_at', $date->year)
                ->whereMonth('viewed_at', $date->month)
                ->count();

            $data[] = [
                'month' => $month,
                'count' => $count,
            ];
        }

        return $data;
    }
}
