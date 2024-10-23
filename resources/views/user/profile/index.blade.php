@extends('layout')

@section('title', 'Profil Anda')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-semibold mb-4">Profil Anda</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nama:</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" class="border rounded w-full p-2" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email:</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" class="border rounded w-full p-2" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Profil</button>
    </form>
</div>
@endsection
