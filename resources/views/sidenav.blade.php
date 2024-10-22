<nav class="bg-gradient-to-br from-slate-800 via-gray-700 to-zinc-800 text-white w-64 min-h-screen p-6 shadow-2xl">
    <div class="text-4xl font-extrabold mb-8 text-center text-transparent bg-clip-text bg-gradient-to-r from-slate-300 to-zinc-300">Admin Panel</div>
    <ul class="space-y-4">
        @php
            $menuItems = [
                ['route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
                ['route' => 'users.index', 'icon' => 'fas fa-users', 'label' => 'Manage Pengguna'],
                ['route' => 'clothing_items.index', 'icon' => 'fas fa-box-open', 'label' => 'Manage Item'],
                ['route' => 'admin.rentals.index', 'icon' => 'fas fa-shopping-cart', 'label' => 'Manage Rental'],
                ['route' => 'categories.index', 'icon' => 'fas fa-tags', 'label' => 'Manage Kategori'],
            ];
        @endphp

        @foreach ($menuItems as $item)
            <li>
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center py-3 px-4 hover:bg-white hover:bg-opacity-10 rounded-md transition duration-300 {{ request()->routeIs($item['route']) ? 'bg-white bg-opacity-10' : '' }}">
                    <i class="{{ $item['icon'] }} mr-3"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
