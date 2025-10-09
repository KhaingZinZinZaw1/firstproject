<?php

namespace App\Services;
use App\Contracts\Dao\PostDaoInterface;
use App\Contracts\Services\PostServiceInterface;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostService implements PostServiceInterface
{
    protected $postDao;

    public function __construct(PostDaoInterface $postDao)
    {
        $this->postDao = $postDao;
    }

    /**
     * user storePost function
     * 
     * @param array $data
     * @return void
     */
    public function storePost(array $data)
    {
        // Add created_by and updated_by fields
        $data['created_by'] = Auth::id() ?? 99999;
        $data['updated_by'] = null;

        return $this->postDao->storePost($data);
    }

    /**
     * user getAllPosts function
     * 
     * @param 
     * @return posts
     */
    public function getAllPosts()
    {
        return $this->postDao->getAllPosts();
    }
    
    /**
     * user getPostById function
     * 
     * @param int $id
     * @return post
     */
    public function getPostById(int $id)
    {
        return $this->postDao->findPostById($id);
    }

    /**
     * user updatePost function
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updatePost(int $id,array $data)
    {
        $data['updated_by'] = Auth::id();
        return $this->postDao->updatePost($id, $data);    
    }
    
    /**
     * user deletePost function
     *
     * @param int $id
     * @return bool
     */
    public function deletePost(int $id)
    {
        return $this->postDao->deletePost($id);
    }

    /**
     * user getPublicPosts function
     *
     * @param int $perPage
     * @return 
     */
    public function getPublicPosts(int $perPage = 5)
    {
        return $this->postDao->getPublicPosts($perPage);
    }
        
    /**
     * user getPostsByUser function
     *
     * @param User $user
     * @return 
     */
    public function getPostsByUser(User $user, int $perPage = 5)
    {
        return $this->postDao->getPostsByUser($user, $perPage);
    }
}
