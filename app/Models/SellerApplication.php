<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SellerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'logo_path',
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

    protected $appends = [
        'logo_url',
    ];

    public function getLogoUrlAttribute()
    {
        if (empty($this->logo_path)) {
            return asset('images/default-store.png');
        }

        if (str_starts_with($this->logo_path, 'http://') || str_starts_with($this->logo_path, 'https://')) {
            return $this->logo_path;
        }

        $path = str_replace('\\', '/', trim($this->logo_path));

        if (str_contains($path, 'storage/app/public/')) {
            $path = substr($path, strpos($path, 'storage/app/public/') + strlen('storage/app/public/'));
        }

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    /**
     * Define the inverse relationship linking the application back to its Buyer/User account
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}