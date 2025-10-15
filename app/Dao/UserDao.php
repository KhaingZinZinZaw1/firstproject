<?php

namespace App\Dao;

use App\Contracts\Dao\UserDaoInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser(int $id, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return User::where('id', $id)->update($data);
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
     * deleteUser function
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id)
    {
        return User::where('id', $id)->delete(); // returns number of affected rows
    }

    /**
     * createOrUpdateUser function
     *
     * @param array $data
     * @return void
     */
    public function createOrUpdateUser(array $data): void
    {
        User::updateOrCreate(
            ['email' => $data['email']], // Find existing user by email
            [
                'name'       => $data['name'],
                'password'   => isset($data['password']) ? Hash::make($data['password']) : null,
                'role'       => $data['role'],
                'created_by' => $data['created_by'],
            ]
        );
    }
}
