<?php

namespace App\Http\Middleware;

use App\Models\Post;
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
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // If the route has a post ID
        if ($request->route('post')) {
            $post = Post::find($request->route('post'));

            // Post does not exist
            if (!$post) {
                abort(404, 'Post not found');
            }

            // If post is private and user is not the owner
            if ($post->is_private && Auth::id() !== $post->user_id) {
                abort(403, 'Access denied');
            }
        }

        return $next($request);
    }
}
