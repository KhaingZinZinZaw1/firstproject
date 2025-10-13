<?php

namespace App\Contracts\Services;
use App\Models\Post;
use App\Models\User;

interface PostServiceInterface
{   
    public function storePost(array $data);
    public function getAllPosts();
    public function getPostById(int $id);
    public function updatePost(int $id, array $data);
    public function deletePost(int $id);
    public function getPublicPosts(int $perPage = 5);
    public function getPostsByUser(User $user, int $perPage = 5);
    public function listPostsWithUsers();
    public function uploadPostsCSV($file);
    public function downloadPostsCSV();
}
