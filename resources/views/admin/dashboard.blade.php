@extends('layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-2">Total Transactions</h2>
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
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-2">Total Categories</h2>
            <p class="text-3xl font-bold text-orange-600">{{ $totalCategories }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Transactions</h2>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left">ID</th>
                        <th class="text-left">User</th>
                        <th class="text-left">Item</th>
                        <th class="text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->user->name }}</td>
                        <td>{{ $transaction->clothingItem->name }}</td>
                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Popular Items</h2>
            <ul>
                @foreach($popularItems as $item)
                <li class="mb-2">
                    <span class="font-semibold">{{ $item->name }}</span> - Rented {{ $item->rental_count }} times
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection