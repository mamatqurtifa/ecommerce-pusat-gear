@extends('layouts.app')

@section('title', 'Toko Gear Media Terlengkap')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                    Pusat Gear Media Terlengkap
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    Temukan peralatan fotografi, videografi, audio, dan drone terbaik untuk kebutuhan kreatif Anda.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('frontend.products.index') }}" 
                       class="bg-white text-blue-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-150">
                        Lihat Produk
                    </a>
                    <a href="#categories" 
                       class="border-2 border-white text-white px-8 py-3 rounded-lg font-medium hover:bg-white hover:text-blue-600 transition duration-150">
                        Kategori
                    </a>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="bg-white bg-opacity-10 rounded-xl p-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <i class="fas fa-camera text-3xl mb-2"></i>
                            <p class="font-medium">Kamera</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <i class="fas fa-microphone text-3xl mb-2"></i>
                            <p class="font-medium">Audio</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <i class="fas fa-plane text-3xl mb-2"></i>
                            <p class="font-medium">Drone</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <i class="fas fa-lightbulb text-3xl mb-2"></i>
                            <p class="font-medium">Lighting</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($categories->count() > 0)
<section id="categories" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Kategori Produk</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Jelajahi berbagai kategori peralatan media untuk semua kebutuhan kreatif Anda
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('frontend.products.category', $category->slug) }}" 
               class="group bg-gray-50 hover:bg-blue-50 rounded-xl p-6 text-center transition duration-150">
                @if($category->image)
                <img src="{{ Storage::url($category->image) }}" 
                     alt="{{ $category->name }}"
                     class="w-16 h-16 mx-auto mb-4 object-cover rounded-lg group-hover:scale-110 transition duration-150">
                @else
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition duration-150">
                    <i class="fas fa-image text-blue-600 text-2xl"></i>
                </div>
                @endif
                <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition duration-150">
                    {{ $category->name }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">{{ $category->active_products_count }} produk</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Produk Unggulan</h2>
                <p class="text-gray-600">Pilihan terbaik dari koleksi kami</p>
            </div>
            <a href="{{ route('frontend.products.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition duration-150">
                Lihat Semua
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            @include('components.frontend.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Sale Products -->
@if($saleProducts->count() > 0)
<section class="py-16 bg-red-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                <i class="fas fa-fire text-red-500 mr-2"></i>Flash Sale
            </h2>
            <p class="text-gray-600">Jangan lewatkan penawaran terbatas ini!</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($saleProducts as $product)
            @include('frontend.components.product-card', ['product' => $product, 'showSaleBadge' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Products -->
@if($latestProducts->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Produk Terbaru</h2>
                <p class="text-gray-600">Koleksi terbaru dari Pusat Gear</p>
            </div>
            <a href="{{ route('frontend.products.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition duration-150">
                Lihat Semua
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestProducts->take(8) as $product)
            @include('frontend.components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Features Section -->
<section class="py-16 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-blue-600 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-shipping-fast text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Gratis Ongkir</h3>
                <p class="text-gray-300">Gratis ongkos kirim untuk pembelian minimal Rp 500.000</p>
            </div>
            <div class="text-center">
                <div class="bg-green-600 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Garansi Resmi</h3>
                <p class="text-gray-300">Semua produk bergaransi resmi dari distributor Indonesia</p>
            </div>
            <div class="text-center">
                <div class="bg-yellow-600 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-headset text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Support 24/7</h3>
                <p class="text-gray-300">Customer service siap membantu Anda kapan saja</p>
            </div>
        </div>
    </div>
</section>
@endsection