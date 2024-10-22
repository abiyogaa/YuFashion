<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-white shadow">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div>
                    <a href="/" class="text-xl font-bold text-gray-800">Your App Name</a>
                </div>
                <div>
                    @auth
                        <a href="{{ route('logout') }}" class="text-gray-800 hover:text-gray-600">Logout</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-800 hover:text-gray-600">Login</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8">
        @yield('content')
    </main>

    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-6 py-3">
            <p class="text-center text-gray-600">&copy; {{ date('Y') }} Your App Name. All rights reserved.</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>

