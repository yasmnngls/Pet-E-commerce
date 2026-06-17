<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
public function user() { 
    return $this->belongsTo(User::class); 
}

public function wishlistable() { 
    return $this->morphTo(); 
}
}

