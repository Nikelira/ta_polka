<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;

    public function shelfStatus()
    {
        return $this->belongsTo(ShelfStatus::class);
    }

    protected $fillable = [
        'number_shelv',
        'number_wardrobe',
        'shelf_status_id',
        'length',
        'wigth',
        'cost',
    ];

    public function compositionRentals()
    {
        return $this->hasMany(CompositionRental::class);
    }
}
