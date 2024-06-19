<?php

namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Page;

class RedirectController extends Controller
{
    public function redirectToSlug($id)
    {
        $service = Article::findOrFail($id);
        $slug = $service->slug; // Assuming 'slug' is the column name in your database table
        $newUrl = '/service/' . $slug;

        return redirect($newUrl);
    }

    public function redirectToPageSlug($id)
    {
        $page = Page::findOrFail($id);
        $slug = $page->slug; 
        // $newUrl = '/page/' . $slug;

        return redirect($slug);
    }
}