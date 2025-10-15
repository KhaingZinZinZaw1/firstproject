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
    public function getPublicPosts();
    public function getPostsByUser(User $user);
    public function listPostsWithUsers();
    public function uploadPostsCSV(Object $file);
    public function downloadPostsCSV();
    public function getPostWithRelations(int $id);
}
