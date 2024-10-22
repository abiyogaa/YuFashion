@extends('layout')

@section('content')
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md h-screen">
            <div class="p-4">
                <h2 class="text-xl font-semibold text-gray-800">Navigation</h2>
            </div>
            <nav class="mt-4">
                <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">Dashboard</a>
                <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">Profile</a>
                <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">Settings</a>
                @auth
                    <a href="{{ route('logout') }}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">Logout</a>
                @else
                    <a href="{{ route('login') }}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">Login</a>
                @endauth
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-10">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome to Your Dashboard</h1>
            <p class="text-gray-600">This is where your main content will go.</p>
        </div>
    </div>
@endsection
