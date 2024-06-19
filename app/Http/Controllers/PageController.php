<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    public function show($slug)
    {
        // Logic to retrieve content based on slug
        $content = Page::where('slug', $slug)->firstOrFail();
        return view('page_show', compact('content'));
    }
}
