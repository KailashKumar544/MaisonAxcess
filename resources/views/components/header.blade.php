<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body class="">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>Maisonaxcess</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('style.css') }}">
    </head>
        <header>
<div class="container">
 <a href="/"><img src="{{ asset('uploads/AXCESS_Logo.png') }}" class="site-logo"></a>
<ul class="top-right">
<li><a href="/"><img src="{{ asset('uploads/home.svg') }}"><br>Accueil</a></li>
<li><a href="/contacter-la-conciergerie"><img src="{{ asset('uploads/page.png') }}"><br>Contacter La Conciergerie</a></li>
@if (Route::has('login'))
@auth
    <li><a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"><img src="{{ asset('uploads/dashboard.svg') }}"><br>Tableau de bord</a></li>
@else
    <li><a href="{{ route('login') }}"><img src="{{ asset('uploads/LOGIN.svg') }}"><br>Se connecter</a></li>
@if (Route::has('register'))
    <li><a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"><img src="{{ asset('uploads/REGISTER.svg') }}"><br>Inscription</a></li>
@endif
@endauth
@endif
</ul>
</div>

 <div class="main-nav">
 <ul>
    @foreach($menu_items as $menu_item)
        @php
            $activeClass = '';
            if ($menu_item->type == 'external_link' && request()->url() == $menu_item->link) {
                $activeClass = 'active';
            } elseif ($menu_item->link == 'contacter-la-conciergerie' && request()->routeIs('contact.form')) {
                $activeClass = 'active';
            } elseif ($menu_item->type == 'page_link') {
                if ($menu_item->page_id !== null) {
                    $pageData = App\Models\Page::findOrFail($menu_item->page_id);
                    if (request()->url() == route('page.show', ['slug' => $pageData->slug])) {
                        $activeClass = 'active';
                    }
                }
            } elseif (request()->url() == route('categories.show', ['slug' => $menu_item->link])) {
                $activeClass = 'active';
            }
        @endphp

        @if($menu_item->type == 'external_link')
            <li><a href="{{$menu_item->link}}" target="_blank" class="{{ $activeClass }}">{{ $menu_item->name }}</a></li>
        @elseif($menu_item->link == 'contacter-la-conciergerie')
            <li><a href="{{ route('contact.form')}}" class="{{ $activeClass }}">{{ $menu_item->name }}</a></li>
        @elseif($menu_item->type == 'page_link')
            @if($menu_item->page_id !== null)
                @php 
                $pageData = App\Models\Page::findOrFail($menu_item->page_id);
                @endphp
                <li><a href="{{ route('page.show', ['slug' => $pageData->slug]) }}" class="{{ $activeClass }}">{{ $menu_item->name }}</a></li>
            @else
                <li><a href="#" class="{{ $activeClass }}">{{ $menu_item->name }}</a></li>
            @endif
        @else
            <li><a href="{{ route('categories.show', ['slug' => $menu_item->link]) }}" class="{{ $activeClass }}">{{ $menu_item->name }}</a></li>
        @endif
    @endforeach
</ul>

</div>
 </header>