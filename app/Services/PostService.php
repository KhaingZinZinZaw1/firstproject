<?php

namespace App\Services;
use App\Contracts\Dao\PostDaoInterface;
use App\Contracts\Services\PostServiceInterface;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return $this->postDao->getAllPosts(Post::PAGINATION_LENGTH);
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
    public function getPublicPosts()
    {
        return $this->postDao->getPublicPosts(Post::PAGINATION_LENGTH);
    }
        
    /**
     * user getPostsByUser function
     *
     * @param User $user
     * @return Post
     */
    public function getPostsByUser(User $user)
    {
        return $this->postDao->getPostsByUser($user, Post::PAGINATION_LENGTH);
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
    public function uploadPostsCSV(Object $file)
    {
        try {
            DB::beginTransaction(); // Start database transaction

            $handle = fopen($file->getPathname(), 'r'); // Open CSV file
            fgetcsv($handle); // Skip header row

            while (($row = fgetcsv($handle)) !== false) {
                $this->postDao->createOrUpdatePost([
                    'id'          => $row[0],
                    'user_id'     => $row[1],
                    'title'       => $row[2],
                    'description' => $row[3],
                    'public_flag' => $row[4],
                    'created_by'  => $row[5] 
                ]);
            }

            fclose($handle); // Close CSV file
            DB::commit();   // Save all changes

            return back()->with('success', 'CSV uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollback(); // Undo changes if any error occurs
            return back()->withErrors(['CSV upload failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Post's csv file download function
     *
     * @return response
     */
    public function downloadPostsCSV()
    {
        try {
            // Fetch all posts as array
            $posts = $this->postDao->getAllPostsForCSV()->toArray();
            if (empty($posts)) {
                return back()->withErrors(['No posts found for CSV download.']);
            }

            // Add column headers dynamically
            array_unshift($posts, array_keys($posts[0]));

            $callback = function() use ($posts) {
                $file = fopen('php://output', 'w');
                foreach ($posts as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            // CSV headers for response
            $headers = [
                "Content-Type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=posts.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return back()->withErrors(['CSV download failed: ' . $e->getMessage()]);
        }
    }


    /**
     * Get post along with users and comments
     *
     * @param integer $id 
     * @return Post
     */
    public function getPostWithRelations(int $id)
    {
        return $this->postDao->getPostWithRelations($id);
    }
}
