<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'discount_price',
        'stock',
        'description',
        'image',
        'additional_images',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'additional_images' => 'array',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price')->withTimestamps();
    }


    /**
     * Scope a query to search products by name and filter by price range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $name
     * @param float|null $minPrice
     * @param float|null $maxPrice
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $name = null, $minPrice = null, $maxPrice = null)
    {
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($minPrice && $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        return $query;
    }

    /**
     * Set the slug attribute before saving the product.
     *
     * @param string $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['slug'] = Str::slug($this->name);
        } else {
            $this->attributes['slug'] = $value;
        }
    }
}
