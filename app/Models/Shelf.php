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

    protected $fillable = ['name'];

    public function compositionRentals()
    {
        return $this->hasMany(CompositionRental::class);
    }
}
