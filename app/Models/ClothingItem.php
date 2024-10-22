<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClothingItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'stock',
        'price',
    ];

    /**
     * Get the categories for the clothing item.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the images for the clothing item.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ClothingImage::class);
    }

    /**
     * Get the rentals for the clothing item.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }
}
