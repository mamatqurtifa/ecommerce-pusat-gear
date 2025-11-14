<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Toko Gear Media Terlengkap')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 text-xl font-bold text-blue-600 hover:text-blue-700">
                        <i class="fas fa-camera text-2xl"></i>
                        <span>Pusat Gear</span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden md:ml-8 md:flex md:space-x-8">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                            Home
                        </a>

                        <!-- Categories Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 text-sm font-medium">
                                Kategori
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 @click.away="open = false"
                                 class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    @foreach(\App\Models\Category::active()->get() as $category)
                                    <a href="{{ route('frontend.products.category', $category->slug) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ $category->name }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('frontend.products.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('frontend.products.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                            Produk
                        </a>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Cart Icon -->
                        <a href="{{ route('frontend.cart.index') }}" 
                           class="relative p-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            @if(auth()->user()->getCartCount() > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ auth()->user()->getCartCount() }}
                            </span>
                            @endif
                        </a>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-2 text-sm text-gray-500 hover:text-gray-700 focus:outline-none">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 @click.away="open = false"
                                 class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    @if(auth()->user()->hasAnyRole(['super-admin', 'admin', 'staff']))
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                                    </a>
                                    <hr class="border-gray-200">
                                    @endif
                                    <a href="{{ route('frontend.orders.index') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-box mr-2"></i>Pesanan Saya
                                    </a>
                                    <a href="{{ route('profile.edit') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Profile
                                    </a>
                                    <hr class="border-gray-200">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            Register
                        </a>
                    @endauth

                    <!-- Mobile menu button -->
                    <button @click="mobileMenu = !mobileMenu" 
                            class="md:hidden p-2 text-gray-500 hover:text-gray-700"
                            x-data="{ mobileMenu: false }">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div x-show="mobileMenu" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="md:hidden border-t border-gray-200 bg-white"
                 x-data="{ mobileMenu: false }">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" 
                       class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Home</a>
                    <a href="{{ route('frontend.products.index') }}" 
                       class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Produk</a>
                    
                    @foreach(\App\Models\Category::active()->get() as $category)
                    <a href="{{ route('frontend.products.category', $category->slug) }}" 
                       class="block px-6 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-md">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 relative" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="ml-3">
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" 
                    class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 relative" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="ml-3">
                <p>{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" 
                    class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-camera text-2xl text-blue-400"></i>
                        <span class="text-xl font-bold">Pusat Gear</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Toko peralatan media terlengkap untuk kebutuhan fotografi, videografi, audio, dan drone profesional.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                            <i class="fab fa-whatsapp text-xl"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-150">Kamera</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-150">Audio</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-150">Drone</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-150">Lighting</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-2 text-gray-300">
                            <i class="fas fa-phone"></i>
                            <span>021-123-4567</span>
                        </li>
                        <li class="flex items-center space-x-2 text-gray-300">
                            <i class="fas fa-envelope"></i>
                            <span>info@pusatgear.com</span>
                        </li>
                        <li class="flex items-center space-x-2 text-gray-300">
                            <i class="fas fa-clock"></i>
                            <span>Sen-Sab 09:00-18:00</span>
                        </li>
                    </ul>

                    <h4 class="text-md font-semibold mt-6 mb-3">Payment Methods</h4>
                    <div class="flex flex-wrap gap-2">
                        <img src="https://duitku.com/images/logo/method/VA.png" alt="Virtual Account" class="h-6">
                        <img src="https://duitku.com/images/logo/method/CC.png" alt="Credit Card" class="h-6">
                        <img src="https://duitku.com/images/logo/method/OV.png" alt="OVO" class="h-6">
                        <img src="https://duitku.com/images/logo/method/DA.png" alt="DANA" class="h-6">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Pusat Gear. All rights reserved.</p>
                <p class="text-gray-400 mt-2 md:mt-0">Powered by Duitku Payment Gateway</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>