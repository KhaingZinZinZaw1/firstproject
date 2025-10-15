<?php

namespace App\Dao;

use App\Contracts\Dao\PostDaoInterface;
use App\Models\Post;
use App\Models\User;

class PostDao implements PostDaoInterface
{
    /**
     * user storePost function
     * 
     * @param array $data
     * @return Post
     */
    public function storePost(array $data)
    {
        return Post::create($data); // Returns the created Post model
    }

    /**
     * user getAllPosts function
     * 
     * @param int $perPage
     * @return Post[]
     */
    public function getAllPosts(int $perPage)
    {
        return Post::latest()->paginate($perPage);
    }

        /**
     * user getAllPosts without pagination for csv download
     * 
     * @param int $perPage
     * @return Post[]
     */
    public function getAllPostsForCSV()
    {
        return Post::all(); // fetch all posts for CSV
    }

    /**
     * user findPostById function
     * 
     * @param int $id
     * @return Post
     */
    public function findPostById(int $id)
    {
        return Post::where('id', $id)->first(); // retrieves single post or null
    }

    /**
     * user updatePost function
     * 
     * @param int $id
     * @param array $data
     * @return void
     */
    public function updatePost(int $id, array $data): void
    {
        Post::where('id', $id)->update($data);
    }

    /**
     * user deletePost function
     * 
     * @param int $id
     * @return void
     */
    public function deletePost(int $id): void
    {
        Post::where('id', $id)->delete(); // deletes the post by id
    }

    /**
     * user getPublicPosts function
     * 
     * @param int $perPage
     * @return void
     */
    public function getPublicPosts(int $perPage)
    {
        return Post::where('public_flag', true)
                   ->latest()
                   ->paginate($perPage);
    }

    /**
     * user getPostsByUser function
     * 
     * @param int $perPage
     * @param User $user
     * @return Post[]
     */
    public function getPostsByUser(User $user, int $perPage)
    {
        return $user->posts()->latest()->paginate($perPage);
    }

    /**
     * user getPostsWithUsers function
     * 
     * @param 
     * @return Post
     */
    public function getPostsWithUsers()
    {
        return Post::with('user')->get();
    }

    /**
     * Posts create or update function
     * 
     * @param 
     * @return Post
     */
    public function createOrUpdatePost(array $data)
    {
        return Post::updateOrCreate(
            ['title' => $data['title']],//condition
            [
                'description' => $data['description'],
                'public_flag' => $data['public_flag'],
                'user_id' => $data['user_id'],
                'created_by' => $data['created_by']
            ]
        );
    }

    /**
     * Get posts with related user and comments
     *
     * @param integer $id
     * @return Post|null
     */
    public function getPostWithRelations(int $id)
    {
        return Post::with(['user', 'comments.user'])->find($id);
    }
}

