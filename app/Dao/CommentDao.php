<?php

namespace App\Dao;

use App\Contracts\Dao\CommentDaoInterface;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentDao implements CommentDaoInterface
{
    /**
     * user addComment function
     * 
     * @param int $postId
     * @param string $comment
     * @return comment
     */
    public function addComment(int $postId, string $comment)
    {
        return Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'comment' => $comment,
        ]);
    }
}

