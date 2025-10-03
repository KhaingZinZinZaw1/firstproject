<?php

namespace App\Contracts\Dao;
use App\Models\Post;

interface PostDaoInterface
{
    public function storePost(array $data);
    public function getAllPosts();
    public function findPostById(int $id);
    public function updatePost(int $id,array $data);
    public function deletePost(int $id);
    public function getPublicPosts(int $perPage = 5);
}


