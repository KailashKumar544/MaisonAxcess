<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Maisonaxcess</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('style.css') }}">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
     <header>
<div class="container">
 <a href="/"><img src="{{ asset('uploads/AXCESS_Logo.png') }}" class="site-logo"></a>
<ul class="top-right">
<li><a href="/"><img src="{{ asset('uploads/home.svg') }}">Accueil</a></li>
<li><a href="/contacter-la-conciergerie"><img src="{{ asset('uploads/page.png') }}">Contacter La Conciergerie</a></li>
@if (Route::has('login'))
@auth
    <li><a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"><img src="{{ asset('uploads/dashboard.svg') }}">Tableau de bord</a></li>
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
</div>
 </header>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <div class="footer">
    <p>Maison Axcess {!! '&copy;' !!} 2023. All Rights Reserved WordPress</p>
</div>
    </body>
</html>
