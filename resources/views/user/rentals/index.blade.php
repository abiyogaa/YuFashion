@extends('layout')

@section('title', 'Your Rentals')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Your Rentals</h2>

    @if ($rentals->isEmpty())
        <p class="text-gray-700">You have no active rentals.</p>
    @else
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="border-b py-2 px-4 text-left">Item</th>
                    <th class="border-b py-2 px-4 text-left">Rental Date</th>
                    <th class="border-b py-2 px-4 text-left">Return Date</th>
                    <th class="border-b py-2 px-4 text-left">Total Price</th>
                    <th class="border-b py-2 px-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rentals as $rental)
                    <tr>
                        <td class="border-b py-2 px-4">{{ $rental->clothingItem->name }}</td>
                        <td class="border-b py-2 px-4">{{ $rental->rental_date->format('Y-m-d') }}</td>
                        <td class="border-b py-2 px-4">{{ $rental->return_date->format('Y-m-d') }}</td>
                        <td class="border-b py-2 px-4">{{ $rental->total_price }}</td>
                        <td class="border-b py-2 px-4">{{ ucfirst($rental->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-6">
        @if ($rentals->count() < 2)
            <h3 class="text-lg font-bold mb-2">Available Items for Rent</h3>
            <ul>
                @foreach ($availableItems as $item)
                    <li class="flex justify-between items-center border-b py-2">
                        <span>{{ $item->name }}</span>
                        <a href="{{ route('rent.form', ['clothing_item_id' => $item->id]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Rent Now
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-red-500">You can only have a maximum of 2 active rentals.</p>
        @endif
    </div>
</div>
@endsection
