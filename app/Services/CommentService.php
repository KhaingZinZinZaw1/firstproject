<?php

namespace App\Services;
use App\Contracts\Dao\CommentDaoInterface;
use App\Contracts\Services\CommentServiceInterface;
use App\Models\Comment;

class CommentService implements CommentServiceInterface
{
    protected $commentDao;

    public function __construct(CommentDaoInterface $commentDao)
    {
        $this->commentDao = $commentDao;
    }

    /**
     * user addComment function
     * 
     * @param int $postId
     * @param string $commentText
     * @return void
     */
    public function addComment(int $postId, string $commentText)
    {
        return $this->commentDao->addComment($postId, $commentText);
    }
}
