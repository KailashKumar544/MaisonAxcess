<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Appointment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    function show($slug){

        $articles = Article::where('slug', $slug)->firstOrFail();
        $service_provider_id = Session::get('service_provider_id');    
        // $bookedDates = Appointment::where('service_provider_id', $service_provider_id)->pluck('date')->toArray();
        $bookedDates = [];
        // Session::forget('service_provider_id');
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = [];
        }
        return view('services', compact('articles', 'bookedDates', 'user'));

    }
}
