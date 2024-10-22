<nav class="bg-gradient-to-b from-gray-800 to-gray-900 text-white w-64 min-h-screen p-6 shadow-lg">
    <div class="text-3xl font-bold mb-8 text-center text-teal-400 animate-pulse">Admin Dashboard</div>
    <ul class="space-y-4">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-4 hover:bg-opacity-25 hover:bg-white rounded-lg transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-tachometer-alt mr-3"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-3 px-4 hover:bg-opacity-25 hover:bg-white rounded-lg transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-users mr-3"></i>
                <span>Manage Users</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-3 px-4 hover:bg-opacity-25 hover:bg-white rounded-lg transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-box-open mr-3"></i>
                <span>Manage Products</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-3 px-4 hover:bg-opacity-25 hover:bg-white rounded-lg transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-shopping-cart mr-3"></i>
                <span>Manage Orders</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-3 px-4 hover:bg-opacity-25 hover:bg-white rounded-lg transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-tags mr-3"></i>
                <span>Manage Categories</span>
            </a>
        </li>
    </ul>
    <div class="mt-12">
        <a href="#" class="flex items-center justify-center py-3 px-4 bg-teal-400 text-gray-900 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
            <i class="fas fa-arrow-left mr-3"></i>
            <span>Back to Main Site</span>
        </a>
    </div>
</nav>
