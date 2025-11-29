<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookView extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];

    protected $casts = [
        'book_id' => 'integer',
        'viewed_at' => 'datetime',
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

        static::creating(function ($view) {
            if (!$view->viewed_at) {
                $view->viewed_at = now();
            }
        });
    }
}
