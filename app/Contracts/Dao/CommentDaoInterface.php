<?php

namespace App\Contracts\Dao;
use App\Models\Comment;

interface CommentDaoInterface
{
    public function addComment(int $postId, string $comment);
}


