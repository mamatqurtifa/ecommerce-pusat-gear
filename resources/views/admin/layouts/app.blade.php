<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#1a1a1a]">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay for Mobile -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
             style="display: none;">
        </div>

        <!-- Sidebar Component -->
        <x-admin.sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Content Container with Rounded Background -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="bg-white rounded-3xl shadow-xl h-full flex flex-col overflow-hidden">
                    
                    <!-- Top Navigation / Header Inside Rounded Container -->
                    <header class="border-b border-gray-200 px-6 py-5">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <!-- Mobile menu button -->
                                <button @click="sidebarOpen = !sidebarOpen" 
                                        class="text-gray-500 hover:text-gray-600 lg:hidden mr-4 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-lg p-2">
                                    <i class="fas fa-bars text-xl"></i>
                                </button>
                                
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">
                                        @yield('page-title', 'Dashboard')
                                    </h1>
                                    <p class="text-sm text-gray-500 mt-1">
                                        @yield('page-description', 'View, create, and manage product listings to ensure accurate information and availability for customers.')
                                    </p>
                                </div>
                            </div>

                            <!-- User Profile & Notifications -->
                            <div class="flex items-center space-x-3">
                                <!-- Notification Bell -->
                                <button class="relative p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="fas fa-bell text-xl"></i>
                                    <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500"></span>
                                </button>

                                <!-- User Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="flex items-center space-x-3 focus:outline-none">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" 
                                             alt="{{ Auth::user()->name }}" 
                                             class="w-10 h-10 rounded-full">
                                        <div class="hidden md:block text-left">
                                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                        </div>
                                    </button>
                                    
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         @click.away="open = false"
                                         class="absolute right-0 z-50 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                         style="display: none;">
                                        <div class="py-1">
                                            <a href="{{ route('profile.edit') }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                <i class="fas fa-user mr-2 w-4"></i>Profile
                                            </a>
                                            <div class="border-t border-gray-100"></div>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i>Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="flex-1 overflow-y-auto p-6">
                        <!-- Alert Messages -->
                        @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium">{{ session('success') }}</p>
                                </div>
                                <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium">{{ session('error') }}</p>
                                </div>
                                <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @endif

                        @yield('content')
                    </main>

                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>