<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}"> <!-- Include additional stylesheet -->
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css') }}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- SweetAlert JS -->
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js') }}"></script>
    <style>
        /* Custom CSS */
        body {
            background-color: #001524;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login {
            display: flex;
            align-items: center;
            background-color: white;
            width: 85%;
            height: 630px;
            margin-top: 17px;
            border-radius: 20px;
        }
        .image {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #526D82;
            width: 50%;
            height: 100%;
            border-radius: 20px 0px 0px 20px;
        }
        .login-content {
            display: flex;
            align-items: center;
            flex-direction: column;
            right: 50%;
            transform: translateX(45%);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Yielding additional content -->
    @yield('content')

    <!-- Additional HTML content -->
    <div class="login" style="margin-top: 22px">
        <div class="image">
            <img src="{{ asset('assets/8768461-removebg-preview.png') }}" alt="">
        </div>

        <div class="login-content" style="margin-top: 25px">
            <h1 style="font-weight: 600; font-size:40px">Sign in</h1>
            <p style="font-size:16px;">Welcome back, please sign in to your account</p>
            <div class="login-form">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" /> <br>
                        <x-text-input style="width: 300px; height:40px; border-radius:3px; border:2px solid #526D82;" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" /> <br>

                        <x-text-input style="width: 300px; height:40px; border-radius:3px; border:2px solid #526D82;" id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="" style="text-decoration: none; margin-right:55px; color:#389ae4" class="ahay" >
                            {{ __('Forgot your password?') }}
                        </a>

                        <x-primary-button class="btnlog" style="background-color: #001524">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                    <br>
                    <div class="sign" style="margin-left: 23px">
                        <p>Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert Error Display -->
    @if ($errors->any())
        <script>
            swal({
                title: "Error!",
                text: "{{ $errors->first() }}",
                icon: "error",
                button: "OK",
            });
        </script>
    @endif

</body>
</html>
