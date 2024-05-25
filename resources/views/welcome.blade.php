<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pompiere&display=swap" rel="stylesheet">
</head>
<body>
    <div class="landing">
        <div class="landing-navbar">
            <h1>TB Library.</h1>
            <div class="landing-navbar-reg">
                @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                    @auth
                        <a href="{{ url('/home') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a class="anchor" href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"><button>Register</button></a>
                        @endif
                    @endauth
                </div>
            @endif
            </div>
            
        </div>
        <div class="landing-content">
            <div class="landing-content-left">
            <div class="tagline">
                <img class="star-icon" src="{{ asset('assets/star.png') }}" alt="">
                <p>Start your reading journey today</p>
            </div>
            <h1>Where every Page is a new Adventure</h1>
            <p class="p">Welcome to our library, where endless adventures begin through the pages of books. Discover knowledge, inspiration, and life-changing stories here. Let's start exploring the world through words!</p>
        </div>
        <div class="landing-content-right">
            <img src="{{ asset('assets/landing.png') }}" alt="">
        </div>
        </div>
        
    </div>
</body>
</html>