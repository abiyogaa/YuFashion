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

                        @if(auth()->user()->isUser())
                            <a href="{{ route('user.dashboard') }}" class="text-white hover:text-gray-300 transition duration-300">
                                <i class="fas fa-tshirt mr-2"></i>Your Clothing
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
            <div id="floating-alert" class="fixed top-4 right-4 left-4 md:left-auto z-50 hidden flex flex-col">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-lg flex justify-between items-center" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <button class="ml-4" onclick="closeAlert()">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 shadow-lg flex justify-between items-center" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <button class="ml-4" onclick="closeAlert()">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </button>
                    </div>
                @endif
            </div>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const floatingAlert = document.getElementById('floating-alert');
            if (floatingAlert.innerHTML.trim() !== '') {
                floatingAlert.classList.remove('hidden');
                setTimeout(() => {
                    floatingAlert.classList.add('hidden');
                }, 5000);
            }
        });

        function closeAlert() {
            document.getElementById('floating-alert').classList.add('hidden');
        }
    </script>
</body>
</html>
