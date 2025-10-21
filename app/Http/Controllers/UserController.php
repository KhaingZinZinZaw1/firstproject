<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserServiceInterface;
use App\Contracts\Services\PostServiceInterface;
use App\Models\Post;
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
    private $postService;

    public function __construct(UserServiceInterface $userService,PostServiceInterface $postService)
    {
        $this->userService = $userService;
        $this->postService = $postService;
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
            $path = $file->storeAs('images', $filename);
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
        // Handle password
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']); // Remove if empty
        }

        // Handle image upload
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename);
            $validated['img'] = $path;
        }

        // Update user directly via service (no retrieval)
        $this->userService->updateUser($id, $validated);

        // Role-based redirect
        $currentUser = Auth::user();
        if ($currentUser->role == 1) {
            // Admin → back to user list
            return redirect()->route('users.list')->with('status', 'User updated successfully!');
        } else {
            // Member → redirect to their own profile page
            return redirect()->route('users.show', $currentUser->id)->with('status', 'Profile updated successfully!');
        }

        // return redirect()->route('users.list')->with('status', 'User updated successfully!');
    }

    /**
     * Remove the specified user from the database.
     *
     * @param  int  $id
     * @return View
     */
    public function destroy(int $id)
    {
        // Delete user via service
        $this->userService->deleteUser($id);
        return redirect()->route('users.list');
    }

    /**
     * show all user list
     *
     * @return View
     */
    public function allList()
    {
        $users = $this->userService->listUsers();
        $posts = $this->postService->listPostsWithUsers(); 
        return view('users.admin', compact('users','posts'));
    }

    /**
     * Attempt to log in the user with the provided credentials.
     *
     * @return response
     */
    public function checklogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = $this->userService->checkLogin($credentials);

        if ($user) {
            // Login via Auth facade
            Auth::login($user);

            // Redirect based on role
            if ($user->role == 1) { // Admin
                return redirect()->route('users.list');
            } elseif ($user->role == 2) { // Member
                // Redirect to a member-specific route showing only their own data
                return redirect()->route('users.show', $user->id);
            } else {
                return redirect()->back()->withErrors(['login_error' => 'Permission denied!']);
            }
        }

        // Login failed
        return redirect()->back()->withErrors([
            'login_error' => 'Invalid username or password!',
        ])->withInput($request->only('email'));
    }

    /**
     * show member function
     *
     * @param int $id
     * @return View
     */
    public function memberRole(int $id)
    {
        $currentUser = Auth::user();

        if ($currentUser->role == 2 && $currentUser->id != $id) {
            abort(403, 'Access denied');
        }

        $user = $this->userService->getUserById($id);        
        // $myPosts = $this->postService->getPostsByUser($user, 5);
        $myPosts = $this->postService->getPostsByUser($user);
        $allPosts = $this->postService->getAllPosts();
        return view('users.member', compact('user', 'myPosts', 'allPosts'));
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

    /**
     * shows user's details function
     *
     * @return View
     */
    public function showUserDetail(int $id)
    {
        $user = $this->userService->getUserById($id);
        return view('users.userdetail', compact('user'));
    }
    
    /**
     * Download users as CSV file function
     *
     * @return response
     */
    public function downloadCSV()
    {
        return $this->userService->downloadUsersCSV();
    }

    /**
     * Upload users as CSV file function
     *
     * @return response
     */
    public function uploadCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);
        $this->userService->uploadUsersCSV($request->file('csv_file'));
        return back()->with('success', 'CSV uploaded successfully');
    }

}
