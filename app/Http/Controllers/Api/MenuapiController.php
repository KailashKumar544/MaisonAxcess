<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuapiController extends Controller
{
    public function index(){
        $menus = Menu::all();
        if(!$menus->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Menu list','menus' => $menus], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Menu not found','menus' => $menus], 200);
        }
    }
}
