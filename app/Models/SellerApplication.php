<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'store_type',
        'legal_name',
        'id_upload_path',
        'business_registration_path',
        'store_description',
        'facebook_link',
        'instagram_link',
        'website_link',
        'business_address',
        'customer_support_contact',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'product_categories',
        'shipping_methods',
        'terms_accepted',
        'status',
        'admin_notes'
    ];

    /**
     * Define the inverse relationship linking the application back to its Buyer/User account
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}