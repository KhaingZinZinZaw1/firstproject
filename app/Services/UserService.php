<?php

namespace App\Services;
use App\Contracts\Dao\UserDaoInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    protected $userDao;

    public function __construct(UserDaoInterface $userDao)
    {
        $this->userDao = $userDao;
    }
        
    /**
     * check login function
     *
     * @param array $credentials
     * @return user
     */
    public function checkLogin(array $credentials)
    {
        $user = $this->userDao->findByEmail($credentials['email']);

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user; // Login successful
        }

        return null; // Login failed
    }

    /**
     * get all users function
     *
     * @return alluser list
     */
    public function listUsers()
    {
        return $this->userDao->getAllUsers();
    }

    /**
     * resetPassword function
     *
     * @param string $email
     * @param string $newPassword
     * @return bool
     */
    public function resetPassword(string $email, string $newPassword)
    {
        $user = $this->userDao->findByEmail($email);
        if (!$user) 
        {
            return false; // user not found
        }

        $hashedPassword = Hash::make($newPassword);
        $this->userDao->updatePassword($user, $hashedPassword);

        return true;
    }

    /**
     * User store to db function
     *
     * @param array $data
     * @return void
     */
    public function storeUser(array $data)
    {
        // Add currently logged-in user ID to created_by
        $data['created_by'] = Auth::id() ?? 99999;

        // Hash password
        $data['password'] = bcrypt($data['password']);

        return $this->userDao->createUser($data);
    }

    /**
     * Get user function
     *
     * @param integer $id
     * @return user
     */
    public function getUserById(int $id)
    {
        return $this->userDao->findUserById($id);
    }
        
     /**
     * update user data function
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function updateUser(User $user, array $data)
    {
        // Update password only if provided
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        //set updated_by to logged-in user
        $data['updated_by'] = Auth::id();
        return $this->userDao->updateUser($user, $data);
    }

    /**
     * user delete function
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user)
    {
        return $this->userDao->deleteUser($user);
    }
}
