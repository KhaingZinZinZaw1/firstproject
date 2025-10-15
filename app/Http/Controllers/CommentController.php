<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\Services\CommentServiceInterface;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * store function
     *
     * @param [int] $postId
     * @param Request $request
     * @return redirect
     */
    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);
        $this->commentService->addComment($postId, $request->comment);

        return redirect()->back()->with('status', 'Comment added successfully!');
    }
}
