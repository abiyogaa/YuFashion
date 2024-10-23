@extends('layout')

@section('title', 'Your Rentals')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Active Rentals</h2>

    @if ($activeRentals->count() >= 2)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
            <p class="font-bold">Warning:</p>
            <p>You currently have two active rentals. Please return an item before renting more.</p>
        </div>
    @endif

    @if ($activeRentals->isEmpty())
        <p class="text-gray-700">You have no active rentals.</p>
    @else
        <table class="min-w-full bg-white border border-gray-300 mb-8">
            <thead>
                <tr>
                    <th class="border-b py-2 px-4 text-left">Item</th>
                    <th class="border-b py-2 px-4 text-left">Rental Date</th>
                    <th class="border-b py-2 px-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activeRentals as $rental)
                    <tr>
                        <td class="border-b py-2 px-4">{{ $rental->clothingItem->name }}</td>
                        <td class="border-b py-2 px-4">{{ $rental->rental_date->format('Y-m-d') }}</td>
                        <td class="border-b py-2 px-4">{{ ucfirst($rental->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    
@endsection
