<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'user_name', 'user_phone', 'user_email', 'user_address',
        'street', 'home_number', 'postal_code', 'full_price', 'status',
        'adminShow'
    ];

    protected $casts = [
        'full_price' => 'decimal:2',
        'adminShow' => 'boolean',
    ];

    /**
     * Get the user that owns the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed orders
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Calculate total amount from order items
     */
    public function calculateTotal()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}
