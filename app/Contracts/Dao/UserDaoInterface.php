<?php

namespace App\Contracts\Dao;
use App\Models\User;

interface UserDaoInterface
{   
    public function createUser(array $data);
    public function findByEmail(string $email);
    public function getAllUsers();
    // public function updatePassword(User $user, string $password);
    public function findUserById(int $id);
    public function updateUser(int $id, array $data);
    public function deleteUser(int $id);
    public function createOrUpdateUser(array $data);
}


