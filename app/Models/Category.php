<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_uz', 'name_ru', 'name_en'
    ];

    /**
     * Get the products that belong to the category
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Scope a query to search categories by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name_uz', 'like', '%' . $search . '%')
                    ->orWhere('name_ru', 'like', '%' . $search . '%')
                    ->orWhere('name_en', 'like', '%' . $search . '%');
    }

    /**
     * Get the category name based on locale
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"name_$locale"} ?? $this->name_uz;
    }
}
