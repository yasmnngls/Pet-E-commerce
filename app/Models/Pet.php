<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
protected $fillable = [ /* all columns except id */ ];
public function category() { 
    return $this->belongsTo(Category::class); 
    }

public function seller() { 
    return $this->belongsTo(User::class, 'seller_id'); 
    }

public function images() { 
    return $this->hasMany(PetImage::class); 
}

public function cartItems() { 
    return $this->morphMany(CartItem::class, 'item'); 
    }

public function orderItems() { 

    return $this->morphMany(OrderItem::class, 'item'); 
    }

public function reviews() { 

    return $this->morphMany(Review::class, 'reviewable'); 
    }
}
