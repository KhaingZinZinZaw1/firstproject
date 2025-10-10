<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'title',
        'description',
        'public_flag',
        'user_id',
        'created_by',
        'updated_by',
    ];
    
    /**
     * Get all comments for this post
     */
    public function comments() {
        return $this->hasMany(Comment::class);
    }
    
    /**
     * Get the user who wrote this post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
