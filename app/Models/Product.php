<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku', 'name', 'detail', 'price', 'image'
    ];

    // Many-to-many relationship with orders
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product'); // 'order_product' is the pivot table
    }
}

