<nav class="bg-gradient-to-br from-slate-800 via-gray-700 to-zinc-800 text-white w-64 min-h-screen p-6 shadow-2xl">
    <div class="text-4xl font-extrabold mb-8 text-center text-transparent bg-clip-text bg-gradient-to-r from-slate-300 to-zinc-300">Admin Panel</div>
    <ul class="space-y-4">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-4 hover:bg-white hover:bg-opacity-10 rounded-md transition duration-300">
                <i class="fas fa-tachometer-alt mr-3"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-3 px-4 hover:bg-white hover:bg-opacity-10 rounded-md transition duration-300">
                <i class="fas fa-users mr-3"></i>
                <span>Manage Users</span>
            </a>
        </li>
        <li>
            <a href="{{ route('clothing_items.index') }}" class="flex items-center py-3 px-4 hover:bg-white hover:bg-opacity-10 rounded-md transition duration-300">
                <i class="fas fa-box-open mr-3"></i>
                <span>Manage Products</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-3 px-4 hover:bg-white hover:bg-opacity-10 rounded-md transition duration-300">
                <i class="fas fa-shopping-cart mr-3"></i>
                <span>Manage Orders</span>
            </a>
        </li>
        <li>
            <a href="{{ route('categories.index') }}" class="flex items-center py-3 px-4 hover:bg-white hover:bg-opacity-10 rounded-md transition duration-300">
                <i class="fas fa-tags mr-3"></i>
                <span>Manage Categories</span>
            </a>
        </li>
    </ul>
    <div class="mt-12">
        <a href="#" class="flex items-center justify-center py-2 px-4 bg-gradient-to-r from-slate-600 to-zinc-600 hover:from-slate-700 hover:to-zinc-700 text-white rounded-md transition duration-300">
            <i class="fas fa-arrow-left mr-3"></i>
            <span>Back to Main Site</span>
        </a>
    </div>
</nav>
