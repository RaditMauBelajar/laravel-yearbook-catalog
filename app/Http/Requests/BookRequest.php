<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Sudah dihandle oleh middleware auth
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $bookId = $this->route('book') ? $this->route('book')->id : null;

        return [
            // Basic Info
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'school_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',

            // Files
            'cover_image' => $this->isMethod('post')
                ? 'required|image|mimes:jpeg,jpg,png,webp|max:5120' // 5MB untuk create
                : 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120', // optional untuk update

            'pdf_file' => 'nullable|file|mimes:pdf|max:102400', // 100MB

            'pages.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240', // 10MB per page

            'video_url' => 'nullable|url|max:255',

            // Credentials
            'access_username' => [
                'required',
                'string',
                'max:100',
                'alpha_dash', // hanya huruf, angka, dash, underscore
                'unique:books,access_username,' . $bookId,
            ],

            'access_password' => $this->isMethod('post')
                ? 'required|string|min:6|max:255' // wajib untuk create
                : 'nullable|string|min:6|max:255', // optional untuk update

            // Status
            'status' => 'required|in:show,hide',
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul buku wajib diisi.',
            'year.required' => 'Tahun wajib diisi.',
            'year.integer' => 'Tahun harus berupa angka.',
            'year.min' => 'Tahun minimal 1900.',
            'year.max' => 'Tahun maksimal ' . (date('Y') + 5) . '.',

            'cover_image.required' => 'Cover buku wajib diupload.',
            'cover_image.image' => 'File cover harus berupa gambar.',
            'cover_image.mimes' => 'Format cover harus: JPEG, JPG, PNG, atau WEBP.',
            'cover_image.max' => 'Ukuran cover maksimal 5MB.',

            'pdf_file.mimes' => 'File harus berupa PDF.',
            'pdf_file.max' => 'Ukuran PDF maksimal 100MB.',

            'pages.*.image' => 'Setiap halaman harus berupa gambar.',
            'pages.*.mimes' => 'Format halaman harus: JPEG, JPG, PNG, atau WEBP.',
            'pages.*.max' => 'Ukuran setiap halaman maksimal 10MB.',

            'video_url.url' => 'URL video tidak valid.',

            'access_username.required' => 'Username akses wajib diisi.',
            'access_username.unique' => 'Username ini sudah digunakan buku lain.',
            'access_username.alpha_dash' => 'Username hanya boleh huruf, angka, dash (-), dan underscore (_).',

            'access_password.required' => 'Password akses wajib diisi.',
            'access_password.min' => 'Password minimal 6 karakter.',

            'status.required' => 'Status buku wajib dipilih.',
            'status.in' => 'Status harus "show" atau "hide".',
        ];
    }

    /**
     * Custom attribute names
     */
    public function attributes(): array
    {
        return [
            'title' => 'Judul',
            'year' => 'Tahun',
            'school_name' => 'Nama Sekolah',
            'description' => 'Deskripsi',
            'cover_image' => 'Cover Buku',
            'pdf_file' => 'File PDF',
            'pages' => 'Halaman Buku',
            'video_url' => 'URL Video',
            'access_username' => 'Username Akses',
            'access_password' => 'Password Akses',
            'status' => 'Status',
        ];
    }
}
