<?php

namespace App\Http\Controllers;

use App\Contracts\Services\PostServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }
    
    /**
     * create function
     *
     * @return post create view
     */
    public function create()
    {
        return view('posts.create'); 
    }

    /**
     * store function
     *
     * @return post list view
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'public_flag' => 'required|boolean',
        ]);

        $validated['user_id'] = auth::id();

        // Ensure public_flag is 1 or 0 (from radio button)
        $validated['public_flag'] = $request->input('public_flag') ? 1 : 0;

        // Use service to store
        $this->postService->storePost($validated);
        // Redirect based on role
        $currentUser = Auth::user();
        if ($currentUser->role == 1) {
            return redirect()->route('users.list')->with('status', 'Post created successfully!');
        } else {
            return redirect()->route('users.show', $currentUser->id)->with('status', 'Post created successfully!');
        } 
    }

    /**
     * postList function
     *
     * @return post list view
     */
    public function postList(){
        $posts = $this->postService->getAllPosts();
        return view('posts.list', compact('posts'));        
    }
    
    /**
     * show user's post detail
     *
     * @param integer $id
     * @return View
     */
    public function show(int $id){
        $post = $this->postService->getPostById($id);

        if (!$post) {
            return redirect()->route('posts.list')->withErrors(['Post not found!']);
        }
        // return view('posts.show', compact('post')); 
        $post->load(['user', 'comments.user']);
        return view('posts.show', compact('post'));
    }
    
    /**
     * edit function
     *
     * @param integer $id
     * @return edit view
     */
    public function edit(int $id)
    {
        $post = $this->postService->getPostById($id);
        if (!$post) {
            return redirect()->route('posts.list')->withErrors(['Post not found!']);
        }
        return view('posts.edit', compact('post'));    
    }

    /**
     * update function
     *
     * @param Request $request
     * @param integer $id
     * @return post list view
     */
    public function update(Request $request, int $id)
    {
        // Validation rules
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'public_flag' => 'required|boolean',
        ]);

        $validated['public_flag'] = $request->input('public_flag') ? 1 : 0;
        $this->postService->updatePost($id, $validated);

        return redirect()->route('posts.show', $id)->with('status', 'Post updated successfully!');
    }

    /**
     * destroy function
     *
     * @param integer $id
     * @return post list view
     */
    public function destroy(int $id)
    {
        
        $this->postService->deletePost($id);
        // Redirect based on role
        $currentUser = Auth::user();
        if ($currentUser->role == 1) {
            // return redirect()->route('users.list');
            return redirect()->route('posts.list');
        } else {
            return redirect()->route('users.show', $currentUser->id)->with('status', 'Post deleted successfully!');
        } 
  
    }
    
    /**
     * home page function
     *
     * @return void
     */
    public function home()
    {
        $user = Auth::user(); // null if not logged in
        $posts = $this->postService->getPublicPosts(5);
        return view('home', compact('posts', 'user'));
    }

    /**
     * showDetails function
     *
     * @param [type] $id
     * @return post detail view from home page
     */
    public function showDetails($id)
    {
        $post = $this->postService->getPostById($id);

        if (!$post) {
            return redirect()->route('posts.list')->withErrors(['Post not found!']);
        }
        return view('posts.showdetails', compact('post'));
    }
}
