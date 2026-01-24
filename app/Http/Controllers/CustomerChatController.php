<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class CustomerChatController extends Controller
{
    // Gerbang login → balik landing + buka popup
    public function open()
    {
        return redirect()->route('home', ['openChat' => 1]);
    }

    // Ambil chat thread user (include attachments)
    public function thread(Request $request)
    {
        $user = $request->user();

        $inquiry = Inquiry::where('user_id', $user->id)
            ->latest()
            ->first();

        if (! $inquiry) {
            return response()->json([
                'hasInquiry' => false,
                'messages' => [],
            ]);
        }

        $messages = $inquiry->messages()
            ->with('attachments')
            ->orderBy('id')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'sender' => $m->sender,
                    'message' => $m->message,
                    'created_at' => optional($m->created_at)->toDateTimeString(),
                    'attachments' => $m->attachments->map(fn ($a) => [
                        'id' => $a->id,
                        'url' => asset('storage/' . $a->path),
                        'original_name' => $a->original_name,
                        'mime' => $a->mime,
                        'size' => $a->size,
                    ])->values(),
                ];
            });

        return response()->json([
            'hasInquiry' => true,
            'messages' => $messages,
        ]);
    }

    // Kirim pesan (support attachments)
    public function send(Request $request)
    {
        $user = $request->user();

        $inquiry = Inquiry::where('user_id', $user->id)->latest()->first();

        // bisa text kosong kalau ada gambar, dan sebaliknya
        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:2000', 'required_without:attachments'],

            // untuk pertama kali wajib
            'phone'   => [$inquiry ? 'nullable' : 'required', 'string', 'max:30'],
            'subject' => [$inquiry ? 'nullable' : 'required', 'string', 'max:120'],

            'attachments' => ['nullable', 'array', 'max:5', 'required_without:message'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB
        ]);

        // kalau belum ada inquiry, buat dulu
        if (! $inquiry) {
            $inquiry = Inquiry::create([
                'user_id' => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $data['phone'],
                'subject' => $data['subject'],
                'status'  => 'new',
            ]);
        }

        $msg = $inquiry->messages()->create([
            'sender'  => 'user',
            'message' => $data['message'] ?? '',
        ]);

        // simpan attachments ke storage public
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

        $msg->load('attachments');

        return response()->json([
            'ok' => true,
            'message' => [
                'id' => $msg->id,
                'sender' => $msg->sender,
                'message' => $msg->message,
                'created_at' => optional($msg->created_at)->toDateTimeString(),
                'attachments' => $msg->attachments->map(fn ($a) => [
                    'id' => $a->id,
                    'url' => asset('storage/' . $a->path),
                    'original_name' => $a->original_name,
                    'mime' => $a->mime,
                    'size' => $a->size,
                ])->values(),
            ],
        ]);
    }
}
