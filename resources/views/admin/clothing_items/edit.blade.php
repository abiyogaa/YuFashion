@extends('layout')

@section('title', 'Edit Item Pakaian')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Edit Item Pakaian</h1>
        <a href="{{ route('clothing_items.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali ke Daftar
        </a>
    </div>
    <form action="{{ route('clothing_items.update', $clothingItem->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Nama
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name', $clothingItem->name) }}" required>
            @error('name')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Deskripsi
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" id="description" name="description" rows="3" required>{{ old('description', $clothingItem->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="stock">
                Stok
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('stock') border-red-500 @enderror" id="stock" type="number" name="stock" value="{{ old('stock', $clothingItem->stock) }}" required min="0">
            @error('stock')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                Harga (dalam rupiah)
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror" id="price" type="number" name="price" value="{{ old('price', $clothingItem->price) }}" required min="0">
            @error('price')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="categories">
                Kategori
            </label>
            <div class="mt-2 space-y-2">
                @foreach($categories as $category)
                    <div class="flex items-center">
                        <input type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}"
                               class="form-checkbox h-5 w-5 text-blue-600 @error('categories') border-red-500 @enderror"
                               {{ in_array($category->id, old('categories', $clothingItem->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <label for="category_{{ $category->id }}" class="ml-2 text-gray-700">{{ $category->name }}</label>
                    </div>
                @endforeach
            </div>
            @error('categories')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="images">
                Ganti Gambar Baru
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('images') border-red-500 @enderror" id="images" type="file" name="images[]" multiple accept="image/*">
            @error('images')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Gambar Saat Ini
            </label>
            <div class="grid grid-cols-3 gap-4">
                @foreach($clothingItem->images as $image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gambar Item Pakaian" class="w-full h-32 object-cover rounded">
                @endforeach
            </div>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Perbarui Item Pakaian
            </button>
        </div>
    </form>
</div>
@endsection