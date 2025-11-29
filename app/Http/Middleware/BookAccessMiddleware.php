<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Book;

class BookAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $book = $request->route('book');

        // Jika parameter bukan object Book, cari dulu
        if (!$book instanceof Book) {
            $book = Book::findOrFail($book);
        }

        // Cek apakah user sudah login untuk buku ini
        $sessionKey = 'book_access_' . $book->id;

        if (!session()->has($sessionKey)) {
            return redirect()->route('book.login', $book->id)
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses buku ini.');
        }

        return $next($request);
    }
}
