<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_application', 'user_id', 'application_status_id', 'message'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicationStatus()
    {
        return $this->belongsTo(ApplicationStatus::class);
    }

    public function compositions()
    {
        return $this->hasMany(CompositionRentalApplication::class);
    }

    public function rentalContracts()
    {
        return $this->hasMany(RentalContracts::class);
    }

    public function shelves()
    {
        return $this->belongsToMany(Shelf::class, 'composition_rental_applications', 'rental_application_id', 'shelf_id');
    }
}
