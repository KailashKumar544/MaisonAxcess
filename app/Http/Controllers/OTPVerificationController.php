<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserNotification;
use App\Notifications\NewUserNotificationForAdmin;

class OTPVerificationController extends Controller
{
    public function showVerificationForm()
    {
        return view('auth.otp-verify');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otp = $request->session()->get('otp');

        if ($request->otp == $otp) {
            $userDetails = $request->session()->get('user_details');

            // Create the user
            $user = User::create([
                'name' => $userDetails['name'],
                'email' => $userDetails['email'],
                'password' => Hash::make($userDetails['password']),
            ]);
            // Assign the role and category to the user
            if($userDetails['user_role'] == '4'){
                $category_ids = $subcategories = [];
                if(empty($userDetails['subChildCategory']) && !empty($userDetails['childCategory'])){
                    $subcategories = Category::where('parent_id', $userDetails['childCategory'])->get();
                }
                elseif (empty($userDetails['childCategory'])) {
                    $subcategories = Category::where('parent_id', $userDetails['mainServiceTypes'])->get();
                } else {
                    $subcategories = Category::where('parent_id', $userDetails['subChildCategory'])->get();
                }
                if ($subcategories->isNotEmpty()) {
                    foreach ($subcategories as $subcategory) {
                        $category_ids[] = $subcategory->id;
                    }
                    $category_ids[] = $userDetails['childCategory']?? $userDetails['mainServiceTypes'];
                } else {
                    $category_ids[] = $userDetails['childCategory']?? $userDetails['mainServiceTypes'];
                }

                // Convert the array to a comma-separated string
                $category_id = implode(',', $category_ids);

                $user->update([
                    'phone_number' => $userDetails['phone_number'],
                    'category_id' => $category_id,
                    'parent_category_id' => $userDetails['mainServiceTypes'],
                    'description' => $userDetails['description']
                ]);
            }else{
                $user->update([
                    'phone_number' => $userDetails['phone_number']
                ]);
            }
            
            
            $photoPath = $request->session()->get('user_profile_photo');
            $user->update(['profile_photo_path' => $photoPath]);
            
            // Attach roles to the user
            if (!empty($userDetails['user_role'])) {
                $role = Role::find($userDetails['user_role']);
                
                if ($role) {
                    $user->assignRole($role);
                }
            }
            /** Sene mail to new user and admin */
            $user->notify(new NewUserNotification());
            $adminEmail = 'shilpi@trustycoders.com'; // Replace with the admin's email address
            Notification::route('mail', $adminEmail)->notify(new NewUserNotificationForAdmin($user));

            // Clear session data
            $request->session()->forget('otp');
            $request->session()->forget('user_details');

            return redirect()->route('login')->with('success', 'L\'utilisateur s\'est enregistré avec succès. Veuillez vous connecter.');
        } else {
            return redirect()->route('otp.verify')->withErrors(['otp' => 'OTP invalide. Veuillez réessayer.']);
        }
    }
}

