<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'item_type', 'item_id',
        'price', 'quantity', 'seller_id', 'status',
    ];

    public function order()  { return $this->belongsTo(Order::class); }
    public function item()   { return $this->morphTo(); }
    public function seller() { return $this->belongsTo(User::class, 'seller_id'); }
}