<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\VerificationCodeMail;
use App\Rules\AllowedEmailDomain;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\UploadedFile;

class RegisterController extends Controller
{
    public function index(){
        $categories = Category::all();
        $roles = Role::all();
        return view('auth.register', compact('categories', 'roles'));
    }

    public function register(Request $request)
    {
        if($request->user_role == '3'){
            Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new AllowedEmailDomain(['urbanstation.fr', 'ratp.dev.fr', 'ratp.fr', 'trustycoders.com'])],
                'password' => ['required', 'string', 'confirmed', 'min:8'],
                'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
                'user_role'=>['required'],
                'phone_number' => ['required', 'string', 'max:12']
            ])->validate();
        }else{
            Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'confirmed', 'min:8'],
                'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
                'user_role'=>['required'],
                'phone_number' => ['required', 'string', 'max:12'],
                'mainServiceTypes' => ['required']
            ])->validate();
        }
        $photoPath = '';
        if (isset($request->photo) && $request->photo instanceof UploadedFile && $request->photo->isValid()) {
            $photoPath = $request->photo->store('profile-photos', 'public');
        }
        $otp = rand(100000, 999999);
        Mail::to($request->email)->send(new VerificationCodeMail($otp));

        $request->session()->put('otp', $otp);
        $request->session()->put('user_profile_photo', $photoPath);
        $request->session()->put('user_details', $request->except('photo'));

        return redirect()->route('otp.verify');
    }

}


