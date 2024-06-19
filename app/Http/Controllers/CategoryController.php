<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $serviceproviders = $uniqueProviders = array();
        $articleslug = '';
        $category = Category::where('slug', $slug)->firstOrFail();
        $articles = $category->articles;
        $category_id = $category->id;
        $childCategories = Category::where('parent_id', $category_id)->get();

        foreach ($childCategories as $childcategory) {
            $childcategory->users = $childcategory->usersWithChildCategory($childcategory->id)->toArray();
            if(empty($childcategory->users)){
                $childcategory->users = $childcategory->usersWithChildCategory($childcategory->parent_id)->toArray();
            }
            $childcategoryslug = Category::where('slug', $childcategory->slug)->firstOrFail();
            $childcatarticles = $childcategoryslug->articles;
            foreach($childcatarticles as $childcatarticle){
                $childcategory->articleslug = $childcatarticle->slug;
                $childcategory->article = $childcatarticle;
            }
        }

        if ($category->parent_id !== null) {
            $serviceproviders = array_merge(
                $category->usersWithChildCategory($category->parent_id)->toArray(),
                $category->usersWithChildCategory($category_id)->toArray()
            );
        } else {
            $serviceproviders = $category->usersWithChildCategory($category_id)->toArray();
        }
        if(!empty($articles)){
            foreach($articles as $article){
                $articleslug = $article->slug;
            }
        }

        if(!empty($serviceproviders)){
            
            usort($serviceproviders, function($a, $b) {
                return $a['id'] - $b['id'];
            });
            
            foreach ($serviceproviders as $provider) {
                if (empty($uniqueProviders) || end($uniqueProviders)['id'] !== $provider['id']) {
                    $uniqueProviders[] = $provider;
                }
            }
        }
        $serviceproviders = $uniqueProviders;
        
        return view('categoryshow', compact('category', 'articleslug', 'category_id', 'childCategories', 'serviceproviders', 'articles'));
    }
   
}
