<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Default Title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-slate-100 via-gray-100 to-zinc-100 min-h-screen flex flex-col">
    <header class="bg-gradient-to-r from-slate-800 via-gray-700 to-zinc-800 text-white shadow">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-300 to-zinc-300">
                    <a href="#">YuFashion</a>
                </div>
                <div class="space-x-6">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-gray-300 transition duration-300">
                                <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                            </a>
                        @endif
                        <a href="#" class="text-white hover:text-gray-300 transition duration-300">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:text-gray-300 transition duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-gray-300 transition duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="text-white hover:text-gray-300 transition duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <div class="flex flex-grow">
        @auth
            @if(auth()->user()->isAdmin())
                @include('sidenav')
            @endif
        @endauth
        <main class="container mx-auto px-6 py-8 flex-grow">
            @yield('content')
        </main>
    </div>

    <footer class="bg-gradient-to-r from-slate-800 via-gray-700 to-zinc-800 text-white mt-auto">
        <div class="container mx-auto px-6 py-4">
            <div class="text-center">
                &copy; {{ date('Y') }} YuFashion. All rights reserved.
            </div>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>
</html>
