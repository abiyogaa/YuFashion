@extends('layout')

@section('title', ' Welcome to Yufashion')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-semibold text-center mb-8">Available Costumes</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($clothingItems as $item)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Image -->
                <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="{{ $item->name }}">

                <!-- Card Body -->
                <div class="p-6">
                    <h5 class="text-xl font-bold mb-2">{{ $item->name }}</h5>
                    <p class="text-gray-700 mb-4">{{ $item->description }}</p>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-900 font-semibold">Price: Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span class="text-gray-500">Stock: {{ $item->stock }}</span>
                    </div>

                    <p class="mt-2 text-gray-500 text-sm">
                        Category:
                        @foreach($item->categories as $category)
                            <span class="text-gray-700">{{ $category->name }}@if(!$loop->last), @endif</span>
                        @endforeach
                    </p>

                    <!-- Button to Rent -->
                    <div class="mt-4">
                    <a href="{{ route('rent.form', ['clothing_item_id' => $item->id]) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">

                            Rent Now
</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


@endsection
