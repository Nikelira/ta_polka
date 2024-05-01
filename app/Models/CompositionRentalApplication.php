<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompositionRentalApplication extends Model
{
    use HasFactory;

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    public function rentalApplication()
    {
        return $this->belongsTo(RentalApplication::class);
    }
}
