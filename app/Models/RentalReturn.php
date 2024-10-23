<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalReturn extends Model
{
    protected $fillable = [
        'rental_id',
        'returned_date',
        'additional_charges',
    ];

    protected $casts = [
        'returned_date' => 'date',
        'additional_charges' => 'integer',
    ];

    protected $attributes = [
        'additional_charges' => 0,
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }
}
