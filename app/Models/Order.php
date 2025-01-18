<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_phone',
        'shipping_address',
        'total_price',
        'status',
        'notes',
        'shipped_at',
    ];

    protected $dates = [
        'shipped_at',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
