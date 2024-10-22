<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Default Title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div class="text-xl font-semibold text-gray-800">
                    <a href="#">YuFashion</a>
                </div>
                <div class="space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800">Admin Dashboard</a>
                        @endif
                        <a href="#" class="text-gray-600 hover:text-gray-800">Profile</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-800">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">Login</a>
                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-800">Register</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <div class="flex">
        @auth
            @if(auth()->user()->isAdmin())
                @include('sidenav')
            @endif
        @endauth
        <main class="container mx-auto px-6 py-8 flex-grow">
            @yield('content')
        </main>
    </div>

    <footer class="bg-white shadow mt-auto">
        <div class="container mx-auto px-6 py-4">
            <div class="text-center text-gray-600">
                &copy; {{ date('Y') }} YuFashion. All rights reserved.
            </div>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>
</html>
