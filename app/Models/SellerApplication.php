<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerApplication extends Model
{
public function user() { 
    return $this->belongsTo(User::class); 
    }
    
protected $casts = ['product_categories' => 'array', 'shipping_methods' => 'array'];
}
