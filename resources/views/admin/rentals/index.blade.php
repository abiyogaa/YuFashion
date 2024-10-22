@extends('layout')

@section('title', 'Manage Penyewaan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Manage Penyewaan</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Pengguna</th>
                    <th class="py-3 px-6 text-left">Item Pakaian</th>
                    <th class="py-3 px-6 text-center">Tanggal Sewa</th>
                    <th class="py-3 px-6 text-center">Tanggal Kembali</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($rentals as $rental)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="font-medium">{{ $rental->user->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <span>{{ $rental->clothingItem->name }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ $rental->rental_date->format('Y-m-d') }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span>{{ $rental->return_date->format('Y-m-d') }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span class="bg-{{ $rental->status === 'pending' ? 'yellow' : ($rental->status === 'approved' ? 'green' : 'red') }}-200 text-{{ $rental->status === 'pending' ? 'yellow' : ($rental->status === 'approved' ? 'green' : 'red') }}-600 py-1 px-3 rounded-full text-xs">
                                {{ $rental->status === 'pending' ? 'Menunggu' : ($rental->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                @if($rental->status === 'pending')
                                    <form action="{{ route('admin.rentals.approve', $rental->id) }}" method="POST" class="mr-2">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                                            Setuju
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.rentals.reject', $rental->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                            Tolak
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">Tidak ada tindakan tersedia</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection