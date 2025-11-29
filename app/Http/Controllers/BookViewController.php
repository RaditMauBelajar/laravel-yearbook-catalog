<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookViewController extends Controller
{
    /**
     * Show flipbook viewer
     */
    public function show($bookId)
    {
        $book = Book::with('pages')->findOrFail($bookId);

        // Increment view count (hanya sekali per session)
        $viewKey = 'book_viewed_' . $book->id;
        if (!session()->has($viewKey)) {
            $book->incrementViews(
                request()->ip(),
                request()->userAgent()
            );
            session()->put($viewKey, true);
        }

        return view('book.view', compact('book'));
    }

    /**
     * Get pages data (untuk AJAX/API)
     */
    public function getPages($bookId)
    {
        $book = Book::with('pages')->findOrFail($bookId);

        $pages = $book->pages->map(function($page) {
            return [
                'id' => $page->id,
                'page_number' => $page->page_number,
                'image_url' => $page->image_url,
                'file_size' => $page->file_size_formatted,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'book' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'year' => $book->year,
                    'school_name' => $book->school_name,
                    'total_pages' => $book->pages->count(),
                ],
                'pages' => $pages,
            ],
        ]);
    }

    /**
     * Download PDF
     */
    public function download($bookId)
    {
        $book = Book::findOrFail($bookId);

        // Cek apakah ada PDF
        if (!$book->hasPdf()) {
            return back()->with('error', 'PDF tidak tersedia untuk buku ini.');
        }

        // Increment download count
        $book->incrementDownloads(request()->ip());

        // Get file path
        $filePath = storage_path('app/public/' . $book->pdf_file);

        // Cek file exist
        if (!file_exists($filePath)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }

        // Download file
        $fileName = $book->title . ' - ' . $book->year . '.pdf';

        return response()->download($filePath, $fileName);
    }

    /**
     * Stream video (opsional, kalau pakai local video)
     */
    public function streamVideo($bookId)
    {
        $book = Book::findOrFail($bookId);

        if (!$book->hasVideo()) {
            return response()->json([
                'success' => false,
                'message' => 'Video tidak tersedia.',
            ], 404);
        }

        // Kalau video_url adalah embed link (YouTube/Vimeo)
        // Return URL langsung
        return response()->json([
            'success' => true,
            'video_url' => $book->video_url,
        ]);
    }
}
