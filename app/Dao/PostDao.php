<?php

namespace App\Dao;

use App\Contracts\Dao\PostDaoInterface;
use App\Models\Post;

class PostDao implements PostDaoInterface
{
    public function storePost(array $data)
    {
        return Post::create($data); // Returns the created Post model
    }

    public function getAllPosts()
    {
        return Post::all();
    }

    public function findPostById(int $id)
    {
        return Post::where('id', $id)->first(); // retrieves single post or null
    }

    public function updatePost(int $id, array $data)
    {
        return Post::where('id', $id)->update($data); // returns number of affected rows
    }

    public function deletePost(int $id)
    {
        return Post::where('id', $id)->delete(); // deletes the post by id
    }
    
    public function getPublicPosts(int $perPage = 5)
    {
        return Post::where('public_flag', true)
                   ->latest()
                   ->paginate($perPage);
    }
}

