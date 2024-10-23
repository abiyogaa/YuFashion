@extends('layout')

@section('title', 'Tambah Item Pakaian')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-zinc-800 bg-clip-text text-transparent">
            Tambah Item Pakaian
        </h1>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('clothing_items.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <form action="{{ route('clothing_items.store') }}" method="POST" enctype="multipart/form-data" class="px-8 pt-6 pb-8">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    <i class="fas fa-tshirt mr-2"></i>Nama
                </label>
                <input class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    <i class="fas fa-align-left mr-2"></i>Deskripsi
                </label>
                <textarea class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('description') border-red-500 @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="stock">
                    <i class="fas fa-box mr-2"></i>Stok
                </label>
                <input class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('stock') border-red-500 @enderror" id="stock" type="number" name="stock" value="{{ old('stock') }}" required min="0">
                @error('stock')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                    <i class="fas fa-tag mr-2"></i>Harga (dalam rupiah)
                </label>
                <input class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('price') border-red-500 @enderror" id="price" type="number" name="price" value="{{ old('price') }}" required min="0">
                @error('price')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="categories">
                    <i class="fas fa-tags mr-2"></i>Kategori
                </label>
                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($categories as $category)
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}"
                                   class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-400 @error('categories') border-red-500 @enderror"
                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <label for="category_{{ $category->id }}" class="text-gray-700">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
                @error('categories')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="images">
                    <i class="fas fa-images mr-2"></i>Gambar
                </label>
                <input class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('images') border-red-500 @enderror" id="images" type="file" name="images[]" multiple accept="image/*">
                @error('images')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-end">
                <button class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400" type="submit">
                    <i class="fas fa-save mr-2"></i>Tambah Item Pakaian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection