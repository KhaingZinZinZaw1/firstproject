<?php

namespace App\Dao;

use App\Contracts\Dao\UserDaoInterface;
use App\Models\User;

class UserDao implements UserDaoInterface
{
    /**
     * createUser function
     *
     * @param array $data
     * @return void
     */
    public function createUser(array $data)
    {
        return User::create($data);
    }

    /**
     * findUserById function
     *
     * @param int $id
     * @return User
     */
    public function findUserById(int $id)
    {
        return User::findOrFail($id);
    }

    /**
     * findByEmail function
     *
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * updateUser function
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function updateUser(User $user, array $data)
    {
        return $user->update($data);
    }

    /**
     * getAllUsers function
     *
     * @return collection
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * updatePassword function
     *
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function updatePassword(User $user, string $password)
    {
        return $user->update(['password' => $password]);
    }

    /**
     * deleteUser function
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user)
    {
        return $user->delete();
    }
}
