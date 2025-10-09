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

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
