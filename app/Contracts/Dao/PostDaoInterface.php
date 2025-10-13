<?php

namespace App\Contracts\Dao;
use App\Models\Post;
use App\Models\User;

interface PostDaoInterface
{
    public function storePost(array $data);
    public function getAllPosts();
    public function findPostById(int $id);
    public function updatePost(int $id,array $data);
    public function deletePost(int $id);
    public function getPublicPosts(int $perPage = 5);
    public function getPostsByUser(User $user,int $perPage = 5);
    public function getPostsWithUsers();
    public function createOrUpdatePost(array $data);
}


