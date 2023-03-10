<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'image', 'content'
    ];

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class)->whereNull('parent_id');
    // }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}
