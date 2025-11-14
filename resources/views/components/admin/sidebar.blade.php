<!-- Sidebar untuk Desktop (selalu terlihat) & Mobile (dengan overlay) -->
<aside class="w-64 bg-gray-900 flex-shrink-0 hidden lg:flex lg:flex-col"
       :class="{ 'fixed inset-y-0 left-0 z-50 flex flex-col': sidebarOpen }"
       x-show="sidebarOpen || true"
       @click.away="sidebarOpen && (sidebarOpen = false)">
    
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-gray-800 border-b border-gray-700 flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="text-white text-lg font-bold">
            <i class="fas fa-tachometer-alt mr-2"></i>Admin Panel
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-2 py-4">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors duration-150">
                <i class="fas fa-home mr-3 w-5 text-center"></i>
                Dashboard
            </a>

            <!-- Categories -->
            <a href="{{ route('admin.categories.index') }}" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors duration-150">
                <i class="fas fa-tags mr-3 w-5 text-center"></i>
                Kategori
            </a>

            <!-- Products -->
            <a href="{{ route('admin.products.index') }}" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors duration-150">
                <i class="fas fa-box mr-3 w-5 text-center"></i>
                Produk
            </a>

            <!-- Orders -->
            <a href="#" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-150">
                <i class="fas fa-shopping-cart mr-3 w-5 text-center"></i>
                Pesanan
            </a>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}" 
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-150">
                <i class="fas fa-users mr-3 w-5 text-center"></i>
                Pengguna
            </a>

            <div class="border-t border-gray-700 my-4"></div>

            <!-- View Website -->
            <a href="{{ route('home') }}" target="_blank"
               class="group flex items-center px-3 py-2.5 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors duration-150">
                <i class="fas fa-external-link-alt mr-3 w-5 text-center"></i>
                Lihat Website
            </a>
        </div>
    </nav>

    <!-- User Info at Bottom -->
    <div class="p-4 bg-gray-800 border-t border-gray-700 flex-shrink-0">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-gray-300"></i>
            </div>
            <div class="ml-3 min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">
                    {{ ucfirst(auth()->user()->roles->first()?->name ?? 'User') }}
                </p>
            </div>
        </div>
    </div>
</aside>