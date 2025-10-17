<?php

namespace App\Services;
use App\Contracts\Dao\UserDaoInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

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
        try {
            // Fetch all users as an array
            $users = $this->userDao->getAllUsers()->toArray();
            if (empty($users)) {
                return back()->withErrors(['No users found for CSV download.']);
            }

            // Add column headers dynamically
            array_unshift($users, array_keys($users[0]));

            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');

                foreach ($users as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            // CSV headers for response
            $headers = [
                "Content-Type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=users.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            // Return back with error message
            return back()->withErrors(['CSV download failed: ' . $e->getMessage()]);
        }
    }

    /**
     * User's csv file upload function
     *
     * @return response
     */
    public function uploadUsersCSV(Object $file)
    {
        try {
            DB::beginTransaction(); // Start database transaction
            $handle = fopen($file->getPathname(), 'r'); // Open the CSV file
            fgetcsv($handle); // Skip the first row (header)

            while (($row = fgetcsv($handle)) !== false) {

                // Insert user into database(values from csv file)
                $this->userDao->createOrUpdateUser([
                    'email'       => $row[1],         
                    'name'      => $row[2],         
                    'password'   => bcrypt('password'),
                    'img'        => $row[3],
                    'role'       => $row[4] ?? null, 
                    'created_by' => $row[5]                
                ]);
            }

            fclose($handle); // Close the file
            DB::commit(); // Save all data

            return back()->with('success', 'CSV uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['CSV upload failed: ' . $e->getMessage()]);
        }
    }
}
