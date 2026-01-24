<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InquiryReplyMail;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiryAdminController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::latest()->paginate(15);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->load(['messages.attachments']);
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function reply(Request $request, Inquiry $inquiry)
    {
        // message boleh kosong kalau ada attachments (foto), dan sebaliknya
        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:2000', 'required_without:attachments'],

            'attachments' => ['nullable', 'array', 'max:5', 'required_without:message'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB per file
        ]);

        // simpan pesan admin
        $msg = $inquiry->messages()->create([
            'sender'  => 'admin',
            'message' => $data['message'] ?? '',
        ]);

        // simpan foto (kalau ada)
        if ($request->hasFile('attachments')) {
            foreach ((array) $request->file('attachments') as $file) {
                if (! $file) continue;

                $path = $file->storePublicly("inquiries/{$inquiry->id}", 'public');

                // relasi attachments() harus ada di model InquiryMessage
                $msg->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        $inquiry->update(['status' => 'replied']);

        // Kirim email ke user (opsional).
        // Jangan sampai gagal email bikin chat gagal.
        try {
            Mail::to($inquiry->email)->send(new InquiryReplyMail($inquiry, $msg));
            return redirect()
                ->route('admin.inquiries.show', $inquiry)
                ->with('success', 'Balasan tersimpan dan email terkirim.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.inquiries.show', $inquiry)
                ->with('success', 'Balasan tersimpan. (Email belum terkirim: cek konfigurasi mail)');
        }
    }
}
