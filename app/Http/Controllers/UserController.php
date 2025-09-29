<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //Using service and dao structure
    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    
    /**
     * Display view
     *
     * @return View
     */
    public function login()
    {
        return view('users.login');//call blade view
    }

    /**
     * Display view
     *
     * @return View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Display view
     *
     * @return View
     */
    public function store(Request $request)
    {
        // Validation rules to check
        $validated = $request->validate([
        'name'     => 'required|string|max:100',
        'email'    => 'required|string|email|max:100|unique:users,email',
        'password' => 'required|string|min:6|max:255|confirmed',
        'img'      => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'role'     => 'required|integer|in:1,2', //1=admin, 2=member
        ]);
        
        // Handle image upload if file exists
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension(); // unique filename
            // dd($filename);
            // Save in storage/app/images
            $path = $file->storeAs('images', $filename); // "local" disk by default
            dd($path);

            $validated['img'] = $path; // save path to validated data
        }
        
        //Pass validated data to service
        $this->userService->storeUser($validated);

        return redirect()->route('users.list')->with('status', 'User created successfully!');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return View
     */
    public function edit(int $id)
    {
        $user = $this->userService->getUserById($id);
        return view('users.edit', compact('user'));
    }
    
    /**
     * Update the specified user in the database.
     *
     * @param  int  $id
     * @return View
     */
    public function update(Request $request, int $id)
    {
        // Validation rules
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|string|email|max:100|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|max:255|confirmed',
            'img'      => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'role'     => 'required|integer|in:1,2',
        ]);
        
        // Fetch user via service
        $user = $this->userService->getUserById($id);

        // If password is empty, remove it so it won't overwrite the current password
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Update user via service
        $this->userService->updateUser($user, $validated);
        return redirect()->route('users.list')->with('status', 'User updated successfully!');
    }

    /**
     * Remove the specified user from the database.
     *
     * @param  int  $id
     * @return View
     */
    public function destroy(int $id)
    {
        // Fetch user via service
        $user = $this->userService->getUserById($id);
        // Delete user via service
        $this->userService->deleteUser($user);
        return redirect()->route('users.list');
    }

    /**
     * show all user list
     *
     * @return view
     */
    public function userList()
    {
        $users = $this->userService->listUsers();
        return view('users.list', compact('users'));
    }

    /**
     * Attempt to log in the user with the provided credentials.
     *
     * @return 
     */
    public function checklogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = $this->userService->checkLogin($credentials);

        if ($user) {
            // Login via Auth facade
            Auth::login($user);

            // Check if user is admin
            if ($user->role == 1) {
                return redirect()->route('users.list');
            } else {
                    return redirect()->back()->withErrors([
                    'login_error' => 'Permission denied!']);
            }
        }

        // Login failed
        return redirect()->back()->withErrors([
            'login_error' => 'Invalid username or password!',
        ])->withInput($request->only('email'));
    }

    /**
     * Register new user
     *
     * @return redirect view
     */
    public function register()
    {
        return redirect()->route('users.create');
    }

    /**
     * forgot psw function
     *
     * @return view
     */
    public function passwordreset()
    {
        return view('users.forgot_psw');//call blade view(ask confirm either need or not to use cgm project structure here)
    }
    
    /**
     * password reset function
     *
     * @param Request $request
     * @return view
     */
    public function emailreset(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $success = $this->userService->resetPassword(
            $request->email,
            $request->new_password
        );

        if (!$success) {
            return back()->withErrors(['email' => 'No user found with this email.']);
        }

        return redirect()->route('login')
            ->with('status', 'Password has been reset successfully!');
    }

    /**
     * user logout function
     *
     * @return redirect view
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('status', 'You have been logged out successfully!');
    }
}
