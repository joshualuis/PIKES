<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'contact',
        'total_price',
        'discounted_price',
        'MOP',
        'POP',
        'type',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function updateTotalPrice()
    {
        // Calculate the total price based on the associated products and their quantities
        $newTotalPrice = $this->products->sum(function ($product) {
            return ($product->pivot->quantity * $product->price) + 50;
        });

        // Update the total_price column in the orders table
        $this->update(['total_price' => $newTotalPrice]);
    }
}
