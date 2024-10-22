<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the clothing items for the category.
     */
    public function clothingItems(): HasMany
    {
        return $this->hasMany(ClothingItem::class);
    }
}
