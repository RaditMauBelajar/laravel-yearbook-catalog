<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display homepage with book catalog
     */
    public function index(Request $request)
    {
        $query = Book::visible()->with(['pages' => function($q) {
            $q->limit(1); // hanya ambil 1 halaman untuk preview
        }]);

        // Search
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filter by year
        if ($request->has('year') && $request->year) {
            $query->byYear($request->year);
        }

        // Filter by school
        if ($request->has('school') && $request->school) {
            $query->where('school_name', 'like', "%{$request->school}%");
        }

        // Sort
        $sortBy = $request->get('sort', 'year');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $books = $query->paginate(12);

        // Get unique years untuk filter
        $years = Book::visible()
            ->selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get unique schools untuk filter
        $schools = Book::visible()
            ->whereNotNull('school_name')
            ->selectRaw('DISTINCT school_name')
            ->orderBy('school_name')
            ->pluck('school_name');

        return view('home', compact('books', 'years', 'schools'));
    }
}
