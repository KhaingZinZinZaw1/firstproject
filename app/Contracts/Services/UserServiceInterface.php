<?php

namespace App\Contracts\Services;
use App\Models\User;

interface UserServiceInterface
{   
    public function storeUser(array $data);
    public function getUserById(int $id); 
    public function updateUser(int $id, array $data);
    public function deleteUser(int $id);
    public function checkLogin(array $credentials);
    public function listUsers();
    public function resetPassword(string $email, string $newPassword);
}
