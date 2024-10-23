<nav class="bg-gradient-to-br from-slate-800 via-gray-700 to-zinc-800 text-white w-48 min-h-screen p-3 shadow-lg">
    <div class="text-xl font-bold mb-4 text-center text-transparent bg-clip-text bg-gradient-to-r from-slate-300 to-zinc-300">Admin</div>
    <ul class="space-y-2">
        @php
            $menuItems = [
                ['route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
                ['route' => 'users.index', 'icon' => 'fas fa-users', 'label' => 'Users'],
                ['route' => 'clothing_items.index', 'icon' => 'fas fa-box-open', 'label' => 'Items'],
                ['route' => 'admin.rentals.index', 'icon' => 'fas fa-shopping-cart', 'label' => 'Rentals'], 
                ['route' => 'categories.index', 'icon' => 'fas fa-tags', 'label' => 'Categories'],
            ];
        @endphp

        @foreach ($menuItems as $item)
            <li>
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center py-2 px-3 hover:bg-white hover:bg-opacity-10 rounded-md transition {{ request()->routeIs($item['route']) ? 'bg-white bg-opacity-10' : '' }}">
                    <i class="{{ $item['icon'] }} mr-2 text-sm"></i>
                    <span class="text-sm">{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
