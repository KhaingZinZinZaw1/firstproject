<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'comment'];
    
    /**
     * Get the post this comment belongs to.
     */
    public function post() {
        return $this->belongsTo(Post::class);
    }
    
    /**
     * Get the user who wrote this comment.
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
