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
     * @return void
     */
    public function getAllPosts(int $perPage = 5)
    {
        // return Post::all();
        return Post::latest()->paginate($perPage);
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
     * @return bool
     */
    public function updatePost(int $id, array $data)
    {
        return Post::where('id', $id)->update($data); // returns number of affected rows
    }

    /**
     * user deletePost function
     * 
     * @param int $id
     * @return bool
     */
    public function deletePost(int $id)
    {
        return Post::where('id', $id)->delete(); // deletes the post by id
    }

    /**
     * user getPublicPosts function
     * 
     * @param int $perPage
     * @return void
     */
    public function getPublicPosts(int $perPage = 5)
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
     * @return void
     */
    public function getPostsByUser(User $user, int $perPage = 5)
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
            ['title' => $data['title']],
            [
                'description' => $data['description'],
                'public_flag' => $data['public_flag'],
                'user_id' => $data['user_id'],
                'created_by' => $data['created_by']
            ]
        );
    }
}

