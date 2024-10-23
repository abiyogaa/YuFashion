@extends('layout')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-zinc-800 bg-clip-text text-transparent">
            My Profile
        </h1>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="px-8 pt-6 pb-8">
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    <i class="fas fa-user mr-2"></i>Name
                </label>
                <div class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700">
                    {{ $user->name }}
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <div class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700">
                    {{ $user->email }}
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                    <i class="fas fa-user-tag mr-2"></i>Role
                </label>
                <div class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700">
                    {{ $user->role->name }}
                </div>
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('profile.edit') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection