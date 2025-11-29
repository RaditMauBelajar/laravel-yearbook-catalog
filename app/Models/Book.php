<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'year',
        'school_name',
        'description',
        'cover_image',
        'pdf_file',
        'pdf_size',
        'video_url',
        'access_username',
        'access_password',
        'status',
        'view_count',
        'download_count',
    ];

    protected $casts = [
        'year' => 'integer',
        'pdf_size' => 'integer',
        'view_count' => 'integer',
        'download_count' => 'integer',
    ];

    protected $hidden = [
        'access_password',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function pages()
    {
        return $this->hasMany(BookPage::class)->orderBy('page_number');
    }

    public function views()
    {
        return $this->hasMany(BookView::class);
    }

    public function downloads()
    {
        return $this->hasMany(BookDownload::class);
    }

    // ========================================
    // ACCESSORS (Auto Getter)
    // ========================================

    public function getCoverUrlAttribute()
    {
        if ($this->cover_image) {
            return Storage::url($this->cover_image);
        }
        return asset('images/no-cover.jpg'); // fallback image
    }

    public function getPdfUrlAttribute()
    {
        if ($this->pdf_file) {
            return Storage::url($this->pdf_file);
        }
        return null;
    }

    public function getPdfSizeFormattedAttribute()
    {
        if (!$this->pdf_size) return 'N/A';

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->pdf_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    // ========================================
    // MUTATORS (Auto Setter)
    // ========================================

    public function setAccessPasswordAttribute($value)
    {
        // Auto hash password jika belum di-hash
        if ($value && !Hash::needsRehash($value)) {
            $this->attributes['access_password'] = $value;
        } else {
            $this->attributes['access_password'] = Hash::make($value);
        }
    }

    // ========================================
    // SCOPES (Query Helper)
    // ========================================

    public function scopeVisible($query)
    {
        return $query->where('status', 'show');
    }

    public function scopeHidden($query)
    {
        return $query->where('status', 'hide');
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('school_name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    // ========================================
    // METHODS
    // ========================================

    public function verifyPassword($password)
    {
        return Hash::check($password, $this->access_password);
    }

    public function incrementViews($ipAddress = null, $userAgent = null)
    {
        $this->increment('view_count');

        // Log view untuk analytics
        $this->views()->create([
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
        ]);
    }

    public function incrementDownloads($ipAddress = null)
    {
        $this->increment('download_count');

        // Log download untuk analytics
        $this->downloads()->create([
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }

    public function toggleStatus()
    {
        $this->status = $this->status === 'show' ? 'hide' : 'show';
        $this->save();
        return $this->status;
    }

    public function isVisible()
    {
        return $this->status === 'show';
    }

    public function hasPdf()
    {
        return !empty($this->pdf_file);
    }

    public function hasVideo()
    {
        return !empty($this->video_url);
    }

    // ========================================
    // BOOT METHOD
    // ========================================

    protected static function boot()
    {
        parent::boot();

        // Auto delete files saat delete model
        static::deleting(function ($book) {
            // Delete cover image
            if ($book->cover_image) {
                Storage::delete($book->cover_image);
            }

            // Delete PDF
            if ($book->pdf_file) {
                Storage::delete($book->pdf_file);
            }

            // Delete all page images
            foreach ($book->pages as $page) {
                if ($page->image_path) {
                    Storage::delete($page->image_path);
                }
            }
        });
    }
}
