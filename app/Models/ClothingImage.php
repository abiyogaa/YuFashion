<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClothingImage extends Model
{
    protected $fillable = [
        'image_path',
        'clothing_item_id',
    ];

    public function clothingItem(): BelongsTo
    {
        return $this->belongsTo(ClothingItem::class);
    }
}
