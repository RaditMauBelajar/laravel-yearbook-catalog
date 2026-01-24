<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryMessage extends Model
{
    protected $fillable = ['inquiry_id','sender','message'];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function attachments()
    {
        return $this->hasMany(InquiryAttachment::class, 'message_id');
    }
}

