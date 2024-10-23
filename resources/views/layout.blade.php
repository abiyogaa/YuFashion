<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Default Title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-slate-100 via-gray-100 to-zinc-100 h-full flex flex-col">
    <header class="bg-gradient-to-r from-slate-800 via-gray-700 to-zinc-800 text-white shadow">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap justify-between items-center">
                <div class="text-2xl sm:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-300 to-zinc-300">
                    <a href="/" class="transition duration-300">YuFashion</a>
                </div>
                <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <div id="menu" class="hidden md:flex w-full md:w-auto mt-4 md:mt-0 space-y-2 md:space-y-0 md:space-x-6">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block md:inline-block text-white hover:text-gray-300 transition duration-300">
                                <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                            </a>
                        @endif

                        @if(auth()->user()->isUser())
                            <a href="{{ route('user.rentals') }}" class="block md:inline-block text-white hover:text-gray-300 transition duration-300">
                                <i class="fas fa-tshirt mr-2"></i>Your rent
                            </a>
                        @endif

                        <a href="{{ route('profile.show') }}" class="block md:inline-block text-white hover:text-gray-300 transition duration-300">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="block md:inline-block w-full md:w-auto text-left md:text-center text-white hover:text-gray-300 transition duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block md:inline-block text-white hover:text-gray-300 transition duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="block md:inline-block text-white hover:text-gray-300 transition duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <div class="flex flex-1 overflow-hidden">
        @auth
            @if(auth()->user()->isAdmin())
                <div id="sidenav" class="hidden lg:block">
                    @include('sidenav')
                </div>
            @endif
        @endauth
        <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 overflow-x-auto">
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

    <footer class="bg-gradient-to-r from-slate-800 via-gray-700 to-zinc-800 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="text-center">
                &copy; {{ date('Y') }} YuFashion. All rights reserved.
            </div>
        </div>
    </footer>

    @vite('resources/js/app.js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const floatingAlert = document.getElementById('floating-alert');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('menu');
            const sidenav = document.getElementById('sidenav');

            if (floatingAlert.innerHTML.trim() !== '') {
                floatingAlert.classList.remove('hidden');
                setTimeout(() => {
                    floatingAlert.classList.add('hidden');
                }, 5000);
            }

            mobileMenuButton.addEventListener('click', function() {
                menu.classList.toggle('hidden');
                if (sidenav) {
                    sidenav.classList.toggle('hidden');
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInsideMenu = menu.contains(event.target);
                const isClickInsideButton = mobileMenuButton.contains(event.target);
                if (!isClickInsideMenu && !isClickInsideButton && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    if (sidenav) {
                        sidenav.classList.add('hidden');
                    }
                }
            });

            // Responsive behavior for window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    menu.classList.remove('hidden');
                    if (sidenav) {
                        sidenav.classList.remove('hidden');
                    }
                } else {
                    menu.classList.add('hidden');
                    if (sidenav) {
                        sidenav.classList.add('hidden');
                    }
                }
            });
        });

        function closeAlert() {
            document.getElementById('floating-alert').classList.add('hidden');
        }
    </script>
</body>
</html>
