<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'product_status_id', 
        'rental_application_id', 
        'product_category_id', 
        'description', 
        'photo_path', 
        'cost', 
        'message', 
        'count'
    ];
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function status()
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }
    
    public function rentalApplication()
    {
        return $this->belongsTo(RentalApplication::class);
    }

    public function orderCompositions()
    {
        return $this->hasMany(orderComposition::class);
    }
}
