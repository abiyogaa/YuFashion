@extends('layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-2">Total Rentals</h2>
            <p class="text-3xl font-bold text-blue-600">{{ $totalRentals }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-2">Total Users</h2>
            <p class="text-3xl font-bold text-green-600">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-2">Total Clothes</h2>
            <p class="text-3xl font-bold text-purple-600">{{ $totalClothes }}</p>
        </div>
    </div>
</div>
@endsection