<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BookPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'page_number',
        'image_path',
        'file_size',
    ];

    protected $casts = [
        'book_id' => 'integer',
        'page_number' => 'integer',
        'file_size' => 'integer',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }
        return asset('images/no-page.jpg');
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return 'N/A';

        $units = ['B', 'KB', 'MB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeOrdered($query)
    {
        return $query->orderBy('page_number');
    }
}
