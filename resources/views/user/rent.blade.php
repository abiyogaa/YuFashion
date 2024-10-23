@extends('layout')

@section('title', 'Rent ' . $item->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">{{ $item->name }}</h2>
    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-2">Informasi Harga:</h3>
        <ul class="list-disc list-inside text-gray-700">
            <li>Harga sewa per hari: Rp {{ number_format($item->price, 0, ',', '.') }}</li>
            <li>Biaya tambahan untuk sewa lebih dari 5 hari: Rp 10.000/hari</li>
            <li>Total harga = (Harga sewa × Jumlah hari × Jumlah barang) + Biaya tambahan</li>
        </ul>
    </div>
    <form action="{{ route('rent.store') }}" method="POST" class="bg-white p-6 rounded shadow-md" id="rental-form">
        @csrf
        <input type="hidden" name="clothing_item_id" value="{{ $item->id }}">

        <div class="mb-4">
            <label for="rental_date" class="block text-gray-700 font-bold mb-2">Tanggal Sewa</label>
            <input type="date" id="rental_date" name="rental_date" class="border border-gray-300 p-2 w-full rounded @error('rental_date') border-red-500 @enderror" required value="{{ old('rental_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}">
            @error('rental_date')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="return_date" class="block text-gray-700 font-bold mb-2">Tanggal Pengembalian</label>
            <input type="date" id="return_date" name="return_date" class="border border-gray-300 p-2 w-full rounded @error('return_date') border-red-500 @enderror" required value="{{ old('return_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            @error('return_date')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
            <p class="text-sm text-gray-600 mt-1">*Periode sewa lebih dari 5 hari akan dikenakan biaya Rp 10.000/hari</p>
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 font-bold mb-2">Jumlah</label>
            <input type="number" id="quantity" name="quantity" min="1" class="border border-gray-300 p-2 w-full rounded @error('quantity') border-red-500 @enderror" required value="{{ old('quantity', 1) }}">
            @error('quantity')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="total_price" class="block text-gray-700 font-bold mb-2">Total Harga</label>
            <input type="number" id="total_price" name="total_price" value="{{ old('total_price', $item->price) }}" class="border border-gray-300 p-2 w-full rounded bg-gray-100 cursor-not-allowed @error('total_price') border-red-500 @enderror" required readonly>
            @error('total_price')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div id="late_fee_info" class="mb-4 hidden">
            <p class="text-red-600">Biaya tambahan: Rp <span id="late_fee_amount">0</span></p>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">Ajukan Sewa</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rentalDateInput = document.getElementById('rental_date');
    const returnDateInput = document.getElementById('return_date');
    const quantityInput = document.getElementById('quantity');
    const totalPriceInput = document.getElementById('total_price');
    const lateFeeInfo = document.getElementById('late_fee_info');
    const lateFeeAmount = document.getElementById('late_fee_amount');
    const itemPrice = <?php echo $item->price; ?>;
    const LATE_FEE_PER_DAY = 10000;
    const MAX_NORMAL_DAYS = 5;

    function updateTotalPrice() {
        const rentalDate = new Date(rentalDateInput.value);
        const returnDate = new Date(returnDateInput.value);
        const daysDiff = Math.ceil((returnDate - rentalDate) / (1000 * 60 * 60 * 24));
        const quantity = parseInt(quantityInput.value);
        
        let totalPrice = 0;
        let lateFee = 0;

        if (daysDiff > 0 && quantity > 0) {
            totalPrice = daysDiff * itemPrice * quantity;
            
            if (daysDiff > MAX_NORMAL_DAYS) {
                const extraDays = daysDiff - MAX_NORMAL_DAYS;
                lateFee = extraDays * LATE_FEE_PER_DAY * quantity;
                lateFeeInfo.classList.remove('hidden');
                lateFeeAmount.textContent = lateFee.toLocaleString();
            } else {
                lateFeeInfo.classList.add('hidden');
            }

            totalPriceInput.value = totalPrice + lateFee;
        } else {
            totalPriceInput.value = itemPrice * quantity;
            lateFeeInfo.classList.add('hidden');
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
            alert('Tanggal pengembalian harus setelah tanggal sewa.');
        }
    });
});
</script>
@endsection
