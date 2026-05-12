<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_uz', 'name_ru', 'name_en', 'slug', 'year', 'breeder',
        'latest', 'color', 'petal', 'shape', 'height', 'smell',
        'price', 'quantity', 'yesorno', 'about',
        'seo_tag', 'seo_title', 'seo_description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'year' => 'integer',
    ];

    /**
     * Get the categories that belong to the product
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Product media files (images / GIF / video), ordered for display.
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class)->orderBy('sort_order');
    }

    /**
     * Get the order items for the product
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include available products
     */
    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Scope a query to search products by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name_uz', 'like', '%' . $search . '%')
                    ->orWhere('name_ru', 'like', '%' . $search . '%')
                    ->orWhere('name_en', 'like', '%' . $search . '%');
    }

    /**
     * Get the product's main image
     */
    public function getMainImageAttribute()
    {
        return $this->images()->first()?->filename;
    }
}
