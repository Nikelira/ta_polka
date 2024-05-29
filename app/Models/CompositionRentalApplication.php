<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompositionRentalApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'shelf_id', 'rental_application_id'
    ];
    
    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    public function rentalApplication()
    {
        return $this->belongsTo(RentalApplication::class);
    }
}
