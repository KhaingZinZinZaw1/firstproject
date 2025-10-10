<?php

namespace App\Http\Middleware;

use App\Models\Post;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
        $loggedInUser = Auth::user(); // logged-in user
        logger($loggedInUser);
            if ($loggedInUser->role == 2) {
                $userIdFromRoute = $request->route('id');
                if ($userIdFromRoute) {
                    $routeUser = User::find($userIdFromRoute); 

                    // Check if member is trying to view another user's details
                    if ($routeUser && $routeUser->id != $loggedInUser->id) {
                        abort(403, 'Access denied:cannot view other user details');
                    }
                }
            }
        }
        if (!Auth::check()) {
            // If trying to access a private post OR trying to create a post
            if ($request->routeIs('posts.create')) {
                abort(403, 'Access denied');
            }
            if ($request->route('id')) {
                $postId = $request->route('id');//get route post id
                $post = Post::find($postId);
                if ($post && $post->public_flag == 0) {
                    abort(403, 'Access denied: private post');
                }
            }
        }
        return $next($request);
    }
}
