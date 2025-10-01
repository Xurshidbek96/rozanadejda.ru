<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'filename', 'alt_text', 'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the product that owns the image
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope a query to only include primary images
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Get the full URL of the image
     */
    public function getUrlAttribute()
    {
        return asset('images/products/' . $this->filename);
    }
}
