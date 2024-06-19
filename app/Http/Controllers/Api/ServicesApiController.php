<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ServicesApiController extends Controller
{
    public function index(){
        $services = Article::all();
        if(!$services->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Services list','services' => $services], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Services not found','services' => $services], 200);
        }
    }

    public function getServiceByCategory($category_id = null){
        $services = Article::where('category_id', $category_id)->get();
        if(!$services->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Services list','services' => $services], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Services not found','services' => $services], 200);
        }
        
    }
}
