@extends('layout')

@section('title', 'Manage Penyewaan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-zinc-800 bg-clip-text text-transparent">
            Manage Penyewaan
        </h1>
        <div class="mt-4 md:mt-0">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari penyewaan..." 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gradient-to-r from-slate-800 via-gray-700 to-zinc-800 text-white text-sm leading-normal">
                        <th class="py-4 px-6 text-left">Pengguna</th>
                        <th class="py-4 px-6 text-left">Item Pakaian</th>
                        <th class="py-4 px-6 text-center">Tanggal Sewa</th>
                        <th class="py-4 px-6 text-center">Tanggal Kembali</th>
                        <th class="py-4 px-6 text-center">Tanggal Dikembalikan</th>
                        <th class="py-4 px-6 text-center">Jumlah</th>
                        <th class="py-4 px-6 text-center">Total Harga</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center">Terlambat</th>
                        <th class="py-4 px-6 text-center">Denda</th>
                        <th class="py-4 px-6 text-center">Total dengan Denda</th>
                        <th class="py-4 px-6 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse ($rentals['active'] as $rental)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-4 px-6 text-left whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <span class="font-medium">{{ $rental->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-tshirt text-gray-500"></i>
                                    <span>{{ $rental->clothingItem->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="far fa-calendar-alt text-gray-500"></i>
                                    <span>{{ $rental->rental_date ? $rental->rental_date->format('Y-m-d') : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="far fa-calendar-check text-gray-500"></i>
                                    <span>{{ $rental->return_date ? $rental->return_date->format('Y-m-d') : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="far fa-calendar-check text-gray-500"></i>
                                    <span>{{ $rental->rentalReturn ? $rental->rentalReturn->returned_date->format('Y-m-d') : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center font-medium">
                                {{ $rental->quantity ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-center font-medium">
                                Rp {{ number_format($rental->total_price ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'approved' => 'bg-green-100 text-green-800 border-green-200',
                                        'canceled' => 'bg-red-100 text-red-800 border-red-200',
                                        'returned' => 'bg-blue-100 text-blue-800 border-blue-200'
                                    ][$rental->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClasses }}">
                                    {{ ucfirst($rental->status ?? 'Unknown') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="{{ $rental->is_overdue ? 'text-red-600 font-bold' : '' }}">
                                    {{ $rental->is_overdue ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="{{ $rental->overdue_charges > 0 ? 'text-red-600 font-bold' : '' }}">
                                    Rp {{ number_format($rental->overdue_charges ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="{{ ($rental->total_price + $rental->overdue_charges) > $rental->total_price ? 'text-red-600 font-bold' : '' }}">
                                    Rp {{ number_format(($rental->total_price ?? 0) + ($rental->overdue_charges ?? 0), 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @if($rental->status === 'pending')
                                        <form action="{{ route('admin.rentals.approve', $rental->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                                                <i class="fas fa-check mr-1"></i> Setuju
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.rentals.reject', $rental->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                                                <i class="fas fa-times mr-1"></i> Tolak
                                            </button>
                                        </form>
                                    @elseif($rental->status === 'approved')
                                        <form action="{{ route('admin.rentals.return', $rental->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                                                <i class="fas fa-undo mr-1"></i> Kembalikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada tindakan tersedia</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-8 px-6 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i class="fas fa-box-open text-4xl text-gray-400"></i>
                                    <span class="font-medium text-gray-500">Tidak ada penyewaan aktif yang tersedia saat ini.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <tr class="bg-gray-100">
                        <td colspan="12" class="py-4 px-6">
                            <h2 class="text-lg font-semibold text-gray-700">Riwayat Penyewaan</h2>
                        </td>
                    </tr>

                    @forelse ($rentals['history'] as $rental)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-4 px-6 text-left whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <span class="font-medium">{{ $rental->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-left">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-tshirt text-gray-500"></i>
                                    <span>{{ $rental->clothingItem->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="far fa-calendar-alt text-gray-500"></i>
                                    <span>{{ $rental->rental_date ? $rental->rental_date->format('Y-m-d') : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="far fa-calendar-check text-gray-500"></i>
                                    <span>{{ $rental->return_date ? $rental->return_date->format('Y-m-d') : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="far fa-calendar-check text-gray-500"></i>
                                    <span>{{ $rental->rentalReturn ? $rental->rentalReturn->returned_date->format('Y-m-d') : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center font-medium">
                                {{ $rental->quantity ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-center font-medium">
                                Rp {{ number_format($rental->total_price ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'approved' => 'bg-green-100 text-green-800 border-green-200',
                                        'canceled' => 'bg-red-100 text-red-800 border-red-200',
                                        'returned' => 'bg-blue-100 text-blue-800 border-blue-200'
                                    ][$rental->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClasses }}">
                                    {{ ucfirst($rental->status ?? 'Unknown') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="{{ $rental->is_overdue ? 'text-red-600 font-bold' : '' }}">
                                    {{ $rental->is_overdue ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="{{ $rental->overdue_charges > 0 ? 'text-red-600 font-bold' : '' }}">
                                    Rp {{ number_format($rental->overdue_charges ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="{{ ($rental->total_price + $rental->overdue_charges) > $rental->total_price ? 'text-red-600 font-bold' : '' }}">
                                    Rp {{ number_format(($rental->total_price ?? 0) + ($rental->overdue_charges ?? 0), 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="text-gray-400 italic">Tidak ada tindakan tersedia</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-8 px-6 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i class="fas fa-box-open text-4xl text-gray-400"></i>
                                    <span class="font-medium text-gray-500">Tidak ada riwayat penyewaan yang tersedia saat ini.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

@endsection