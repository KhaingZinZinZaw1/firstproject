<?php

namespace App\Dao;

use App\Contracts\Dao\UserDaoInterface;
use App\Models\User;

class UserDao implements UserDaoInterface
{
    //create new user
    public function createUser(array $data)
    {
        return User::create($data);
    }

    //find user by id
    public function findUserById(int $id)
    {
        return User::findOrFail($id);
    }

    //check email exit or not
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    //update user data based on request
    public function updateUser(User $user, array $data)
    {
        return $user->update($data);
    }

    //get all user lists
    public function getAllUsers()
    {
        return User::all();
    }

    //update user password
    public function updatePassword(User $user, string $password)
    {
        return $user->update(['password' => $password]);
    }

    //delete user
    public function deleteUser(User $user)
    {
        return $user->delete();
    }
}
