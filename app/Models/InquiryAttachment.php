<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryAttachment extends Model
{
    protected $fillable = ['message_id','path','original_name','mime','size'];

    public function message()
    {
        return $this->belongsTo(InquiryMessage::class, 'message_id');
    }
}
