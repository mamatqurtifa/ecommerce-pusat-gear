<div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 group">
    <div class="relative">
        @if($product->images && count($product->images) > 0)
        <img src="{{ Storage::url($product->images[0]) }}" 
             alt="{{ $product->name }}"
             class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
        @else
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
            <i class="fas fa-image text-4xl text-gray-400"></i>
        </div>
        @endif
        
        <!-- Badges -->
        <div class="absolute top-3 left-3 space-y-1">
            @if($product->is_featured)
            <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                Featured
            </span>
            @endif
            
            @if($product->is_on_sale && isset($showSaleBadge))
            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                -{{ $product->discount_percentage }}%
            </span>
            @endif
            
            @if($product->manage_stock && $product->is_low_stock)
            <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                Stok Terbatas
            </span>
            @endif
        </div>

        <!-- Quick Add to Cart (hanya jika user login) -->
        @auth
        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition duration-300">
            <form action="{{ route('frontend.cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition duration-150"
                        title="Tambah ke Keranjang">
                    <i class="fas fa-cart-plus"></i>
                </button>
            </form>
        </div>
        @endauth
    </div>
    
    <div class="p-4">
        <!-- Category -->
        <p class="text-sm text-blue-600 font-medium mb-1">{{ $product->category->name }}</p>
        
        <!-- Product Name -->
        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition duration-150">
            <a href="{{ route('frontend.products.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>
        
        <!-- Short Description -->
        @if($product->short_description)
        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->short_description }}</p>
        @endif
        
        <!-- Price -->
        <div class="flex items-center justify-between mb-3">
            <div>
                @if($product->is_on_sale)
                <div class="flex items-center space-x-2">
                    <span class="text-lg font-bold text-red-600">
                        Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                    </span>
                    <span class="text-sm text-gray-500 line-through">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>
                @else
                <span class="text-lg font-bold text-gray-900">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
                @endif
            </div>
            
            <!-- Stock Status -->
            @if($product->manage_stock)
            <span class="text-sm {{ $product->is_in_stock ? 'text-green-600' : 'text-red-600' }}">
                {{ $product->is_in_stock ? 'Tersedia' : 'Habis' }}
            </span>
            @endif
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-2">
            <a href="{{ route('frontend.products.show', $product->slug) }}" 
               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-center font-medium transition duration-150">
                Detail
            </a>
            
            @auth
            @if($product->is_in_stock)
            <form action="{{ route('frontend.cart.add') }}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-150">
                    <i class="fas fa-cart-plus mr-1"></i>Keranjang
                </button>
            </form>
            @else
            <button disabled 
                    class="flex-1 bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-medium cursor-not-allowed">
                Habis
            </button>
            @endif
            @else
            <a href="{{ route('login') }}" 
               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center font-medium transition duration-150">
                Login untuk Beli
            </a>
            @endauth
        </div>
    </div>
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush