<!-- Sidebar untuk Desktop (selalu terlihat) & Mobile (dengan overlay) -->
<aside class="w-64 bg-[#1a1a1a] flex-shrink-0 hidden lg:flex lg:flex-col"
       :class="{ 'fixed inset-y-0 left-0 z-50 flex flex-col': sidebarOpen }"
       x-show="sidebarOpen || true"
       @click.away="sidebarOpen && (sidebarOpen = false)">
    
    <!-- Logo -->
    <div class="flex items-center px-6 h-20 border-b border-gray-800 flex-shrink-0">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-8 mr-3">
        <span class="text-white text-lg font-semibold">Pusat Gear</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-6">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white text-gray-900' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-all duration-200">
                <i class="fas fa-home mr-3 w-5 text-center"></i>
                Dashboard
            </a>

            <!-- Products -->
            <a href="{{ route('admin.products.index') }}" 
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-white text-gray-900' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-all duration-200">
                <i class="fas fa-box mr-3 w-5 text-center"></i>
                Products
            </a>

            <!-- Orders -->
            <a href="{{ route('admin.orders.index') }}" 
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-white text-gray-900' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-all duration-200">
                <i class="fas fa-list-ul mr-3 w-5 text-center"></i>
                Orders
            </a>

            <!-- Categories -->
            <a href="{{ route('admin.categories.index') }}" 
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-white text-gray-900' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-all duration-200">
                <i class="fas fa-tags mr-3 w-5 text-center"></i>
                Categories
            </a>

            <!-- Customers -->
            <a href="{{ route('admin.users.index') }}" 
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-white text-gray-900' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-all duration-200">
                <i class="fas fa-users mr-3 w-5 text-center"></i>
                Customers
            </a>

            <!-- Reports -->
            <a href="#" 
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
                <i class="fas fa-file-alt mr-3 w-5 text-center"></i>
                Reports
            </a>

            <div class="border-t border-gray-800 my-4"></div>

            <!-- Settings -->
            <a href="#" 
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
                <i class="fas fa-cog mr-3 w-5 text-center"></i>
                Settings
            </a>
        </div>
    </nav>

    <!-- Logout Button at Bottom -->
    <div class="p-4 border-t border-gray-800 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center px-4 py-3 text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition-all duration-200">
                <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                Log out
            </button>
        </form>
    </div>
</aside>