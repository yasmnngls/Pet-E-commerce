<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
protected $fillable = ['title', 'slug', 'excerpt', 'content', 'featured_image', 'author_id', 'published', 'published_at'];
public function author() { 
    return $this->belongsTo(User::class, 'author_id'); 
    }
    
}
