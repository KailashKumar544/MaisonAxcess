<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserNotification;
use App\Notifications\NewUserNotificationForAdmin;
use App\Rules\AllowedEmailDomain;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;


class UserApiController extends Controller
{
    //Get all users
    public function index()
    {
        $users = User::with('roles')->get();
        // Assuming 'roles' is the relationship name between users and roles
        
        $usersWithRoles = $users->map(function ($user) {
            $roles = $user->roles->pluck('id','name');
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'email_verified_at' => $user->email_verified_at,
                'category_id' => $user->category_id,
                'parent_category_id' => $user->parent_category_id,
                'description' => $user->description,
                'profile_photo_path' => $user->profile_photo_path,                
                'profile_photo_url' => $user->profile_photo_url,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'roles' => $roles
            ];
        });
        if(!$users->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Users list','users' => $usersWithRoles], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'User not found','users' => $usersWithRoles], 200);
        }
    }

    //get user by user id
    public function getUser($userId = null)
    {
        $usersQuery = User::query()->with('roles');
    
        // If $userId is provided, filter users by user_id
        if ($userId !== null) {
            $usersQuery->where('id', $userId);
        }
    
        $users = $usersQuery->get();
    
        $usersWithRoles = $users->map(function ($user) {
            $roles = $user->roles->pluck('id', 'name');
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'email_verified_at' => $user->email_verified_at,
                'category_id' => $user->category_id,
                'parent_category_id' => $user->parent_category_id,
                'description' => $user->description,
                'profile_photo_path' => $user->profile_photo_path,
                'profile_photo_url' => $user->profile_photo_url,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'roles' => $roles
            ];
        });
        if(!$users->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'User details','user' => $usersWithRoles], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'User not found','user' => $usersWithRoles], 200);
        }
    }

    public function getUserByCategory($categoryId = null)
    {
        $usersQuery = User::query()->with('roles');
    
        // If $userId is provided, filter users by user_id
        if ($categoryId !== null) {
            $usersQuery->whereRaw("FIND_IN_SET(?, category_id)", [$categoryId]);
        }
    
        $users = $usersQuery->get();
    
        $usersWithRoles = $users->map(function ($user) {
            $roles = $user->roles->pluck('id', 'name');
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'email_verified_at' => $user->email_verified_at,
                'category_id' => $user->category_id,
                'parent_category_id' => $user->parent_category_id,
                'description' => $user->description,
                'profile_photo_path' => $user->profile_photo_path,
                'profile_photo_url' => $user->profile_photo_url,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'roles' => $roles
            ];
        });
        if(!$users->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'User details','user' => $usersWithRoles], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'User not found','user' => $usersWithRoles], 200);
        }
    }
    
    // create user
    public function createUser(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new AllowedEmailDomain(['urbanstation.fr', 'ratp.dev.fr', 'ratp.fr', 'trustycoders.com'])],
            'password' => ['required','min:8'],
            'user_role' => ['required'],
            'phone_number' => ['required', 'string', 'max:12']
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error','errors' => $validator->errors()], 400);
        }

        $otp = rand(100000, 999999);
        $mail_response = Mail::to($request->input('email'))->send(new VerificationCodeMail($otp));

        return response()->json(['status' => true, 'message' => 'OTP sent successfully!', 'api_otp'=> $otp], 200);        
    }

    //Update user detail
    public function updateUser(Request $request, $userId)
    {
        if(User::find($userId) == null){
            return response()->json(['status' => false, 'message' => 'Validation Error','error' => 'User not found'], 400);
        }
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'password'=>['nullable', 'min:8']
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error','errors' => $validator->errors()], 400);
        }
        // Find the user
        $user = User::findOrFail($userId);
       
        // Update user data
        $user->name = $request->input('name');
        $user->phone_number = $request->input('phone_number');
        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        return response()->json(['status' => true,
        'message' => 'User updated successfully','user' => $user], 200);      
        
    }

    public function verifyUserOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'otp' => ['required','min:6', 'max:6'],
            'api_otp'=>['required'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new AllowedEmailDomain(['urbanstation.fr', 'ratp.dev.fr', 'ratp.fr', 'trustycoders.com'])],
            'password' => ['required','min:8'],
            'user_role' => ['required'],
            'phone_number' => ['required', 'string', 'max:12']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error','errors' => $validator->errors()], 400);
        }

        if ($request->input('otp') == $request->input('api_otp')) {
            // Create new user
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'phone_number' => $request->input('phone_number')
            ]);
            // Attach roles to the user
            if (!empty($request['user_role'])) {
                $role = Role::find($request['user_role']);
                
                if ($role) {
                    $user->assignRole($role);
                }
            }
            /** Sene mail to new user and admin */
            $user->notify(new NewUserNotification());
            $adminEmail = 'shilpi@trustycoders.com'; // Replace with the admin's email address
            Notification::route('mail', $adminEmail)->notify(new NewUserNotificationForAdmin($user));

            return response()->json(['status' => true,'message' => 'User created successfully','user' => $user], 200);
        }else{
            return response()->json(['status' => false,'message' => 'Validation Error','error' => 'Invalid OTP. Please try again.'], 400);
        }
    }
}
