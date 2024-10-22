@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Rent {{ $item->name }}</h2>
    
    <form action="{{ route('rent.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        <input type="hidden" name="clothing_item_id" value="{{ $item->id }}">

        <div class="mb-4">
            <label for="rental_date" class="block text-gray-700 font-bold mb-2">Rental Date</label>
            <input type="date" id="rental_date" name="rental_date" class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div class="mb-4">
            <label for="return_date" class="block text-gray-700 font-bold mb-2">Return Date</label>
            <input type="date" id="return_date" name="return_date" class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div class="mb-4">
            <label for="total_price" class="block text-gray-700 font-bold mb-2">Total Price</label>
            <input type="number" id="total_price" name="total_price" value="{{ $item->price }}" class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Rental</button>
    </form>
</div>
@endsection
