<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/regis.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    

    <div class="signup">
        <div class="image">
             <img src="{{ asset('assets/9881873_4266056-removebg-preview.png') }}" alt="">
        </div>
       
        <div class="signup-content">
            <h1 style="margin-bottom: -8px">Sign up</h1>
            <p>Create an account to start your adventure here</p>
            <div class="signup-form">
                <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" /> <br>
            <x-text-input  style="width: 300px; height:30px; border-radius:3px; border:2px solid #526D82;" id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" /> <br>
            <x-text-input style="width: 300px; height:30px; border-radius:3px; border:2px solid #526D82;" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" /> <br>

            <x-text-input style="width: 300px; height:30px; border-radius:3px; border:2px solid #526D82;" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" /> <br>

            <x-text-input style="width: 300px; height:30px; border-radius:3px; border:2px solid #526D82;" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <br>

        <div class="flex items-center justify-end mt-4">
            <a style="margin-right: 57px; text-decoration:none; color:#389ae4 " class="" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="btn" style="cursor: pointer; background-color:#001524; color:white; width:90px; height:40px; border:none; border-radius:5px;">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
            </div>
        </div>
    </div>

</body>
</html>
    