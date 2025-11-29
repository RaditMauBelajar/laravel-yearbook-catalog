<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BookAccessController extends Controller
{
    /**
     * Show login form for specific book
     */
    public function showLogin($bookId)
    {
        $book = Book::findOrFail($bookId);

        // Cek apakah sudah login
        if (session()->has('book_access_' . $book->id)) {
            return redirect()->route('book.view', $book->id);
        }

        return view('book.login', compact('book'));
    }

    /**
     * Process login for book
     */
    public function login(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cek username
        if ($book->access_username !== $request->username) {
            return back()
                ->withInput()
                ->withErrors(['username' => 'Username salah.']);
        }

        // Cek password
        if (!$book->verifyPassword($request->password)) {
            return back()
                ->withInput()
                ->withErrors(['password' => 'Password salah.']);
        }

        // Set session
        session()->put('book_access_' . $book->id, true);
        session()->put('book_access_time_' . $book->id, now());

        // Redirect to book viewer
        return redirect()->route('book.view', $book->id)
            ->with('success', 'Login berhasil! Selamat membaca.');
    }

    /**
     * Logout from book
     */
    public function logout($bookId)
    {
        $book = Book::findOrFail($bookId);

        // Remove session
        session()->forget('book_access_' . $book->id);
        session()->forget('book_access_time_' . $book->id);

        return redirect()->route('home')
            ->with('success', 'Anda telah keluar dari buku.');
    }
}
