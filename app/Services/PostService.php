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
        $data['created_by'] = Auth::id();
        return $this->postDao->storePost($data);
    }

    /**
     * user getAllPosts function
     * 
     * @param 
     * @return Post
     */
    public function getAllPosts()
    {
        return $this->postDao->getAllPosts();
    }
    
    /**
     * user getPostById function
     * 
     * @param int $id
     * @return Post
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
     * @return Post
     */
    public function getPublicPosts(int $perPage = 5)
    {
        return $this->postDao->getPublicPosts($perPage);
    }
        
    /**
     * user getPostsByUser function
     *
     * @param User $user
     * @return Post
     */
    public function getPostsByUser(User $user, int $perPage = 5)
    {
        return $this->postDao->getPostsByUser($user, $perPage);
    }

    /**
     * user listPostsWithUsers function
     *
     * @param 
     * @return Post
     */
    public function listPostsWithUsers()
    {
        return $this->postDao->getPostsWithUsers();
    }

    /**
     * Post's csv file upload function
     *
     * @return response
     */
    public function uploadPostsCSV($file)
    {
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $this->postDao->createOrUpdatePost([
                'id' => $row[0],
                'title' => $row[1],
                'description' => $row[2],
                'user_id' => $row[3],
                'public_flag' => $row[4],
                'created_by' => 1
            ]);
        }

        fclose($handle);
        return back()->with('success', 'CSV uploaded successfully');
    }

    /**
     * Post's csv file download function
     *
     * @return response
     */
    public function downloadPostsCSV()
    {
        $posts = $this->postDao->getAllPosts();
        $columns = ['ID', 'Title', 'Description', 'User ID', 'Public/Private', 'Created by'];

        $callback = function() use ($posts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($posts as $post) {
                fputcsv($file, [
                    $post->id,
                    $post->title,
                    $post->description,
                    $post->user_id,
                    $post->public_flag,
                    $post-> created_by,
                ]);
            }

            fclose($file);
        };

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=posts.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream($callback, 200, $headers);
    }
}
