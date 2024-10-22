<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the clothing items for the category.
     */
    public function clothingItems(): BelongsToMany
    {
        return $this->belongsToMany(ClothingItem::class, 'category_clothing_item');
    }
}
