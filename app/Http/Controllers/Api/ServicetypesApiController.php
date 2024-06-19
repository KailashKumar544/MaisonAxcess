<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class ServicetypesApiController extends Controller
{
    public function index(){
        $servicetypes = Category::where('parent_id', null)->get();
        foreach ($servicetypes as $servicetype) {
            $servicetype->users = $servicetype->usersWithChildCategory($servicetype->id);
            
            $childcategoryslug = Category::where('slug', $servicetype->slug)->firstOrFail();
            $childcatarticles = $childcategoryslug->articles;
            foreach($childcatarticles as $childcatarticle){
                $servicetype->articleslug = $childcatarticle->slug;
                $servicetype->article = $childcatarticle;
            }
        }
        if(!$servicetypes->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Service Types list','servicetypes' => $servicetypes], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Service Types not found','servicetypes' => $servicetypes], 200);
        }
    }

    public function getServiceTypesByParent($parent_id = null){
        $servicetypes = Category::where('parent_id', $parent_id)->get();
        foreach ($servicetypes as $servicetype) {
            $servicetype->users = $servicetype->usersWithChildCategory($servicetype->id);
            if(empty($servicetype->users)){
                $servicetype->users = $servicetype->usersWithChildCategory($servicetype->parent_id);
            }
            $childcategoryslug = Category::where('slug', $servicetype->slug)->firstOrFail();
            $childcatarticles = $childcategoryslug->articles;
            foreach($childcatarticles as $childcatarticle){
                $servicetype->articleslug = $childcatarticle->slug;
                $servicetype->article = $childcatarticle;
            }
        }
        if(!$servicetypes->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Service Types list','servicetypes' => $servicetypes], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Service Types not found','servicetypes' => $servicetypes], 200);
        }
    }
}
