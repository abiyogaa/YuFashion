<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'clothing_item_id',
        'rental_date',
        'return_date',
        'total_price',
        'quantity',
        'status',
    ];

    protected $casts = [
        'rental_date' => 'date',
        'return_date' => 'date',
        'total_price' => 'integer',
        'quantity' => 'integer',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clothingItem(): BelongsTo
    {
        return $this->belongsTo(ClothingItem::class);
    }

    public function rentalReturn(): HasOne
    {
        return $this->hasOne(RentalReturn::class);
    }
}
