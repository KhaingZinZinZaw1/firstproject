<?php

namespace App\Services;
use App\Contracts\Dao\PostDaoInterface;
use App\Contracts\Services\PostServiceInterface;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService implements PostServiceInterface
{
    protected $postDao;

    public function __construct(PostDaoInterface $postDao)
    {
        $this->postDao = $postDao;
    }

    public function storePost(array $data)
    {
        // Add created_by and updated_by fields
        $data['created_by'] = Auth::id() ?? 99999;
        $data['updated_by'] = null;

        return $this->postDao->storePost($data);
    }

    public function getAllPosts()
    {
        return $this->postDao->getAllPosts();
    }
    
    public function getPostById(int $id)
    {
        return $this->postDao->findPostById($id);
    }

    public function updatePost(int $id,array $data)
    {
        $data['updated_by'] = Auth::id();
        return $this->postDao->updatePost($id, $data);    
    }
    
    public function deletePost(int $id)
    {
        return $this->postDao->deletePost($id);
    }

    public function getPublicPosts(int $perPage = 5)
    {
        return $this->postDao->getPublicPosts($perPage);
    }
        
}
