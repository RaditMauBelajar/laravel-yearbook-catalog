<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewInquiryMail;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'email' => ['required','email','max:120'],
            'phone' => ['required','string','max:30'],
            'subject' => ['required','string','max:120'],
            'message' => ['required','string','max:2000'],

            'attachments' => ['nullable','array','max:10'],
            'attachments.*' => ['file','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        $inquiry = Inquiry::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => $data['subject'],
            'status' => 'new',
        ]);

        $msg = $inquiry->messages()->create([
            'sender' => 'user',
            'message' => $data['message'],
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->storePublicly("inquiries/{$inquiry->id}", 'public');

                $msg->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // kirim email ke admin
        $adminEmail = config('mail.admin_email') ?? env('ADMIN_EMAIL');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new NewInquiryMail($inquiry, $msg));
        }

        return response()->json(['ok' => true]);
    }
}
