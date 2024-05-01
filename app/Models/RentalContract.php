<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalContract extends Model
{
    use HasFactory;

    public function rentalApplication()
    {
        return $this->belongsTo(RentalApplication::class);
    }

}
