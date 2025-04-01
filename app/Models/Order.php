<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total_amount', 'order_status', 'payment_status', 'shipping_status', 'placed_at'
    ];

    // Many-to-many relationship with products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product'); // 'order_product' is the pivot table
    }
}
