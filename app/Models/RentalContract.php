<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RentalContract extends Model
{
    use HasFactory;

    protected $dates = ['date_begin', 'date_end'];

    public function rentalApplication()
    {
        return $this->belongsTo(RentalApplication::class);
    }

}
