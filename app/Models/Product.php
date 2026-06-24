<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'product_category',
        'seller_id',
        'status',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function cartItems()
    {
        return $this->morphMany(CartItem::class, 'item');
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'item');
    }

    public function getFeaturedImageAttribute()
    {
        if (empty($this->image)) {
            return asset('images/pet3.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        $path = ltrim($this->image, '/');
        $filename = basename($path);

        if ($filename) {
            return asset('banner/' . $filename);
        }

        return asset('images/pet3.jpg');
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image;
    }

    public function getImageUrlAttribute()
    {
        return $this->featured_image;
    }

    public function getCategoryDisplayNameAttribute()
    {
        return $this->category->name ?? 'General';
    }
}
