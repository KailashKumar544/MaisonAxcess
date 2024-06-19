<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserNotification;
use App\Notifications\NewUserNotificationForAdmin;
use App\Rules\AllowedEmailDomain;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        if($input['user_role'] == '3'){
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new AllowedEmailDomain(['urbanstation.fr', 'ratp.dev.fr', 'ratp.fr', 'trustycoders.com'])],
                'password' => $this->passwordRules(),
                'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
                'user_role'=>['required'],
                'phone_number' => ['required', 'string', 'max:12']
            ])->validate();
        }else{
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules(),
                'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
                'user_role'=>['required'],
                'phone_number' => ['required', 'string', 'max:12'],
                'mainServiceTypes' => ['required']
            ])->validate();
        }
        

        return DB::transaction(function () use ($input) {
            // Create the user with additional fields
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
            // Assign the role and category to the user
            if($input['user_role'] == '4'){
                $category_ids = $subcategories = [];
                if(empty($input['subChildCategory']) && !empty($input['childCategory'])){
                    $subcategories = Category::where('parent_id', $input['childCategory'])->get();
                }
                elseif (empty($input['childCategory'])) {
                    $subcategories = Category::where('parent_id', $input['mainServiceTypes'])->get();
                } else {
                    $subcategories = Category::where('parent_id', $input['subChildCategory'])->get();
                }
                if ($subcategories->isNotEmpty()) {
                    foreach ($subcategories as $subcategory) {
                        $category_ids[] = $subcategory->id;
                    }
                    $category_ids[] = $input['childCategory']?? $input['mainServiceTypes'];
                } else {
                    $category_ids[] = $input['childCategory']?? $input['mainServiceTypes'];
                }

                // Convert the array to a comma-separated string
                $category_id = implode(',', $category_ids);

                $user->update([
                    'phone_number' => $input['phone_number'],
                    'category_id' => $category_id,
                    'parent_category_id' => $input['mainServiceTypes'],
                    'description' => $input['description']
                ]);
            }else{
                $user->update([
                    'phone_number' => $input['phone_number']
                ]);
            }
            
            if (isset($input['photo']) && $input['photo'] instanceof UploadedFile && $input['photo']->isValid()) {
                $photoPath = $input['photo']->store('profile-photos', 'public');
                $user->update(['profile_photo_path' => $photoPath]);
            }
            // Attach roles to the user
            if (!empty($input['user_role'])) {
                $role = Role::find($input['user_role']);
                
                if ($role) {
                    $user->assignRole($role);
                }
            }
            /** Sene mail to new user and admin */
            $user->notify(new NewUserNotification());
            $adminEmail = 'shilpi@trustycoders.com'; // Replace with the admin's email address
            Notification::route('mail', $adminEmail)->notify(new NewUserNotificationForAdmin($user));
            $this->createTeam($user);

            return $user;
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}

