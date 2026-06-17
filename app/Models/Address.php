<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
protected $fillable = ['user_id', 'label', 'full_name', 'phone', 'street', 'barangay', 'city', 'province', 'is_default'];
public function user() { 
    return $this->belongsTo(User::class); 
    }

public function orders() { 
    return $this->hasMany(Order::class); 
    }
    
}
