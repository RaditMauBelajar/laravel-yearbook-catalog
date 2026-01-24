<?php

namespace App\Mail;

use App\Models\Inquiry;
use App\Models\InquiryMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Inquiry $inquiry,
        public InquiryMessage $messageModel
    ) {}

    public function build()
    {
        $mail = $this->subject('Inquiry Baru: '.$this->inquiry->subject)
            ->replyTo($this->inquiry->email, $this->inquiry->name)
            ->view('emails.inquiry_new');

        foreach ($this->messageModel->attachments as $att) {
            $mail->attach(storage_path('app/public/'.$att->path), [
                'as' => $att->original_name,
                'mime' => $att->mime,
            ]);
        }

        return $mail;
    }
}
