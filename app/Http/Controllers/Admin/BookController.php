<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\BookPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books
     */
    public function index(Request $request)
    {
        $query = Book::query()->with('pages');

        // Search
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filter by year
        if ($request->has('year') && $request->year) {
            $query->byYear($request->year);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'show') {
                $query->visible();
            } elseif ($request->status === 'hide') {
                $query->hidden();
            }
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $books = $query->paginate(10);

        // Get unique years untuk filter dropdown
        $years = Book::selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.books.index', compact('books', 'years'));
    }

    /**
     * Show the form for creating a new book
     */
    public function create()
    {
        return view('admin.books.create');
    }

    /**
     * Store a newly created book
     */
    public function store(BookRequest $request)
    {
        try {
            // Upload cover image
            $coverPath = null;
            if ($request->hasFile('cover_image')) {
                $coverPath = $request->file('cover_image')->store('covers', 'public');
            }

            // Upload PDF
            $pdfPath = null;
            $pdfSize = null;
            if ($request->hasFile('pdf_file')) {
                $pdf = $request->file('pdf_file');
                $pdfPath = $pdf->store('pdfs', 'public');
                $pdfSize = $pdf->getSize();
            }

            // Create book
            $book = Book::create([
                'title' => $request->title,
                'year' => $request->year,
                'school_name' => $request->school_name,
                'description' => $request->description,
                'cover_image' => $coverPath,
                'pdf_file' => $pdfPath,
                'pdf_size' => $pdfSize,
                'video_url' => $request->video_url,
                'access_username' => $request->access_username,
                'access_password' => $request->access_password, // auto hashed di mutator
                'status' => $request->status,
            ]);

            // Upload pages
            if ($request->hasFile('pages')) {
                $this->uploadPages($book, $request->file('pages'));
            }

            return redirect()->route('admin.books.index')
                ->with('success', 'Buku berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified book
     */
    public function edit(Book $book)
    {
        $book->load('pages');
        return view('admin.books.edit', compact('book'));
    }

    /**
     * Update the specified book
     */
    public function update(BookRequest $request, Book $book)
{
    try {
        // Upload cover image (jika ada)
        if ($request->hasFile('cover_image')) {
            // Delete old cover
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $book->cover_image = $request->file('cover_image')->store('covers', 'public');
        }

        // Upload PDF (jika ada)
        if ($request->hasFile('pdf_file')) {
            // Delete old PDF
            if ($book->pdf_file) {
                Storage::disk('public')->delete($book->pdf_file);
            }
            $pdf = $request->file('pdf_file');
            $book->pdf_file = $pdf->store('pdfs', 'public');
            $book->pdf_size = $pdf->getSize();
        }

        // Update data
        $book->title = $request->title;
        $book->year = $request->year;
        $book->school_name = $request->school_name;
        $book->description = $request->description;
        $book->video_url = $request->video_url;
        $book->access_username = $request->access_username;

        // Update password hanya jika diisi
        if ($request->filled('access_password')) {
            $book->access_password = $request->access_password; // Auto hashed di mutator
        }

        $book->status = $request->status;
        $book->save();

        // Upload pages baru (jika ada)
        if ($request->hasFile('pages')) {
            $this->uploadPages($book, $request->file('pages'));
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui!');

    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Gagal memperbarui buku: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified book
     */
    public function destroy(Book $book)
    {
        try {
            $book->delete(); // Files auto deleted di boot method model

            return redirect()->route('admin.books.index')
                ->with('success', 'Buku berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    /**
     * Toggle book status (show/hide)
     */
    public function toggleStatus(Book $book)
    {
        try {
            $newStatus = $book->toggleStatus();

            $message = $newStatus === 'show'
                ? 'Buku sekarang ditampilkan di katalog.'
                : 'Buku disembunyikan dari katalog.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Delete specific page
     */
    public function deletePage(BookPage $page)
    {
        try {
            // Delete file
            if ($page->image_path) {
                Storage::disk('public')->delete($page->image_path);
            }

            $page->delete();

            return back()->with('success', 'Halaman berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus halaman: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Upload multiple pages
     */
    private function uploadPages(Book $book, array $files)
    {
        // Get last page number
        $lastPageNumber = $book->pages()->max('page_number') ?? 0;

        foreach ($files as $index => $file) {
            $pageNumber = $lastPageNumber + $index + 1;

            $path = $file->store("pages/book-{$book->id}", 'public');

            BookPage::create([
                'book_id' => $book->id,
                'page_number' => $pageNumber,
                'image_path' => $path,
                'file_size' => $file->getSize(),
            ]);
        }
    }
}
