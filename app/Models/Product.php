<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function productStatus()
    {
        return $this->belongsTo(ProductStatus::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function rentalApplication()
    {
        return $this->belongsTo(RentalApplication::class);
    }

    protected $fillable = ['name'];

    public function bookingCompositions()
    {
        return $this->hasMany(BookingComposition::class);
    }
}
