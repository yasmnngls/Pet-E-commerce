<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        return $this->resolveImageUrl($this->image);
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image;
    }

    public function getImageUrlAttribute()
    {
        return $this->featured_image;
    }

    protected function resolveImageUrl($path)
    {
        $fallback = asset('images/pet3.jpg');

        if (empty($path)) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $p = str_replace('\\', '/', trim($path));
        $p = ltrim($p, '/');

        if (str_contains($p, 'storage/app/public/')) {
            $p = substr($p, strpos($p, 'storage/app/public/') + strlen('storage/app/public/'));
        }
        if (str_starts_with($p, 'app/public/')) {
            $p = substr($p, strlen('app/public/'));
        }
        if (str_starts_with($p, 'public/')) {
            $p = substr($p, strlen('public/'));
        }
        if (str_starts_with($p, 'storage/')) {
            $p = substr($p, strlen('storage/'));
        }

        if (str_starts_with($p, 'storage/')) {
            return asset($p);
        }

        if (file_exists(public_path($p))) {
            return asset($p);
        }

        if (file_exists(storage_path('app/public/' . $p))) {
            return asset('storage/' . $p);
        }

        return asset('storage/' . $p);
    }}