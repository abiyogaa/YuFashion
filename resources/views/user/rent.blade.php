@extends('layout')

@section('title', 'Rent ' . $item->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Rent {{ $item->name }}</h2>
    <form action="{{ route('rent.store') }}" method="POST" class="bg-white p-6 rounded shadow-md" id="rental-form">
        @csrf
        <input type="hidden" name="clothing_item_id" value="{{ $item->id }}">

        <div class="mb-4">
            <label for="rental_date" class="block text-gray-700 font-bold mb-2">Rental Date</label>
            <input type="date" id="rental_date" name="rental_date" class="border border-gray-300 p-2 w-full rounded @error('rental_date') border-red-500 @enderror" required value="{{ old('rental_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}">
            @error('rental_date')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="return_date" class="block text-gray-700 font-bold mb-2">Return Date</label>
            <input type="date" id="return_date" name="return_date" class="border border-gray-300 p-2 w-full rounded @error('return_date') border-red-500 @enderror" required value="{{ old('return_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            @error('return_date')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 font-bold mb-2">Quantity (Max 2)</label>
            <input type="number" id="quantity" name="quantity" min="1" max="2" class="border border-gray-300 p-2 w-full rounded @error('quantity') border-red-500 @enderror" required value="{{ old('quantity', 1) }}">
            @error('quantity')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="total_price" class="block text-gray-700 font-bold mb-2">Total Price</label>
            <input type="number" id="total_price" name="total_price" value="{{ old('total_price', $item->price) }}" class="border border-gray-300 p-2 w-full rounded @error('total_price') border-red-500 @enderror" required readonly>
            @error('total_price')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">Submit Rental</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rentalDateInput = document.getElementById('rental_date');
    const returnDateInput = document.getElementById('return_date');
    const quantityInput = document.getElementById('quantity');
    const totalPriceInput = document.getElementById('total_price');
    const itemPrice = <?php echo $item->price; ?>;

    function updateTotalPrice() {
        const rentalDate = new Date(rentalDateInput.value);
        const returnDate = new Date(returnDateInput.value);
        const daysDiff = Math.ceil((returnDate - rentalDate) / (1000 * 60 * 60 * 24));
        const quantity = parseInt(quantityInput.value);
        
        if (daysDiff > 0 && quantity > 0) {
            totalPriceInput.value = daysDiff * itemPrice * quantity;
        } else {
            totalPriceInput.value = itemPrice * quantity;
        }
    }

    rentalDateInput.addEventListener('change', function() {
        returnDateInput.min = new Date(rentalDateInput.value);
        returnDateInput.value = '';
        updateTotalPrice();
    });

    returnDateInput.addEventListener('change', updateTotalPrice);
    quantityInput.addEventListener('change', updateTotalPrice);

    document.getElementById('rental-form').addEventListener('submit', function(e) {
        const rentalDate = new Date(rentalDateInput.value);
        const returnDate = new Date(returnDateInput.value);
        
        if (returnDate <= rentalDate) {
            e.preventDefault();
            alert('Return date must be after the rental date.');
        }

        if (quantityInput.value > 2) {
            e.preventDefault();
            alert('Maximum quantity allowed is 2.');
        }
    });
});
</script>
@endsection
