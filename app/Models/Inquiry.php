<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    /**
     * Kolom yang boleh di–mass assign
     */
    protected $fillable = [
        'user_id',   // relasi ke users (customer)
        'name',
        'email',
        'phone',
        'subject',
        'status',
    ];

    /* =========================
     | RELATIONS
     |========================= */

    /**
     * Inquiry dimiliki oleh satu user (customer)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Inquiry punya banyak pesan (chat)
     */
    public function messages()
    {
        return $this->hasMany(InquiryMessage::class);
    }

    /**
     * Inquiry bisa punya banyak attachment (opsional)
     */
    public function attachments()
    {
        return $this->hasMany(InquiryAttachment::class);
    }
}
