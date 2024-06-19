<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PageApiController extends Controller
{
    public function index(){
        $pages = Page::all();
        if(!$pages->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Pages list','pages' => $pages], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Pages not found','pages' => $pages], 200);
        }
    }
}
