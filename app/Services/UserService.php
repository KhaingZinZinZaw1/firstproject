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
     * @return User
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
     * @return User
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

        $data = ['password' => $newPassword];
        $this->userDao->updateUser($user->id, $data);
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
     * @return User
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
    public function updateUser(int $id, array $data)
    {
        // Update password only if provided
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        //set updated_by to logged-in user
        $data['updated_by'] = Auth::id();
        return $this->userDao->updateUser($id, $data);
    }

    /**
     * user delete function
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(int $id)
    {
        return $this->userDao->deleteUser($id);
    }

    /**
     * User's csv file download function
     *
     * @return response
     */
    public function downloadUsersCSV()
    {
        $users = $this->userDao->getAllUsers();

        $columns = ['ID', 'Name', 'Email', 'Image', 'Role'];

        $callback = function() use ($users, $columns) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->img,
                    $user->role
                ]);
            }
            fclose($file);
        };
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=users.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        return response()->stream($callback, 200, $headers);
    }

    /**
     * User's csv file upload function
     *
     * @return response
     */
    public function uploadUsersCSV($file)
    {
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle); // skip header row

        while (($row = fgetcsv($handle)) !== false) {
            $this->userDao->createOrUpdateUser([
                'name' => $row[1],
                'email' => $row[2],
                'password' => bcrypt('password'),
                'role' => 2,
                'created_by' => 1
            ]);
        }
        fclose($handle);
        return back()->with('success', 'CSV uploaded successfully');
    }
}
