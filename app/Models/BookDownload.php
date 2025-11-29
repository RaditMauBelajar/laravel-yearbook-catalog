<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'ip_address',
        'downloaded_at',
    ];

    protected $casts = [
        'book_id' => 'integer',
        'downloaded_at' => 'datetime',
    ];

    public $timestamps = false;

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // ========================================
    // BOOT METHOD
    // ========================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($download) {
            if (!$download->downloaded_at) {
                $download->downloaded_at = now();
            }
        });
    }
}
