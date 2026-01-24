<?php

namespace App\Mail;

use App\Models\Inquiry;
use App\Models\InquiryMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InquiryReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public Inquiry $inquiry;
    public InquiryMessage $messageModel;

    public function __construct(Inquiry $inquiry, InquiryMessage $messageModel)
    {
        $this->inquiry = $inquiry;
        $this->messageModel = $messageModel;
    }

    public function build()
    {
        return $this->subject('Balasan Admin: ' . $this->inquiry->subject)
            ->view('emails.inquiry_reply', [
                'inquiry' => $this->inquiry,
                'messageModel' => $this->messageModel,
            ]);
    }
}
