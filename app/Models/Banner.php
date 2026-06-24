<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    // Add this line right here to tell Laravel these columns are completely safe to write to:
    protected $fillable = ['slot', 'title', 'image_path'];
}