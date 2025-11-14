@extends('admin.layouts.app')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="space-y-6">
    <!-- Product Header -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600">SKU: {{ $product->sku }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    @can('product.edit')
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm transition duration-150">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    @endcan
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition duration-150">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Images & Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Gallery -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800">Galeri Produk</h4>
                </div>
                <div class="p-6">
                    @if($product->images && count($product->images) > 0)
                    <div class="space-y-4">
                        <!-- Main Image -->
                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                            <img id="main-image" 
                                 src="{{ Storage::url($product->images[0]) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Thumbnail Images -->
                        @if(count($product->images) > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images as $index => $image)
                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer hover:ring-2 hover:ring-blue-500 {{ $index === 0 ? 'ring-2 ring-blue-500' : '' }}"
                                 onclick="changeMainImage('{{ Storage::url($image) }}', this)">
                                <img src="{{ Storage::url($image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <i class="fas fa-image text-6xl mb-4"></i>
                            <p>Tidak ada gambar</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Description -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800">Deskripsi Produk</h4>
                </div>
                <div class="p-6">
                    @if($product->short_description)
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                        <h5 class="font-medium text-gray-800 mb-2">Ringkasan</h5>
                        <p class="text-gray-700">{{ $product->short_description }}</p>
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <h5 class="font-medium text-gray-800 mb-2">Deskripsi Lengkap</h5>
                        <div class="text-gray-700 whitespace-pre-line">{{ $product->description }}</div>
                    </div>
                </div>
            </div>

            <!-- Sales Analytics -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800">Analisis Penjualan</h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $product->orderItems->count() }}</div>
                            <div class="text-sm text-gray-600">Total Pesanan</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $product->orderItems->sum('quantity') }}</div>
                            <div class="text-sm text-gray-600">Total Terjual</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600">Rp {{ number_format($product->orderItems->sum('total_price'), 0, ',', '.') }}</div>
                            <div class="text-sm text-gray-600">Total Pendapatan</div>
                        </div>
                    </div>

                    @if($recentOrders->count() > 0)
                    <div>
                        <h5 class="font-medium text-gray-800 mb-3">Pesanan Terbaru</h5>
                        <div class="space-y-2">
                            @foreach($recentOrders->take(5) as $orderItem)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $orderItem->order->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $orderItem->order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $orderItem->quantity }} unit</p>
                                    <p class="text-xs text-gray-500">Rp {{ number_format($orderItem->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-chart-line text-3xl mb-2"></i>
                        <p>Belum ada penjualan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details Sidebar -->
        <div class="space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800">Informasi Produk</h4>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Category -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Kategori</label>
                        <p class="text-gray-900">{{ $product->category->name }}</p>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Harga</label>
                        <div class="flex items-center space-x-2">
                            @if($product->sale_price)
                            <span class="text-xl font-bold text-green-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                            <span class="text-sm text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @if($product->discount_percentage > 0)
                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">-{{ $product->discount_percentage }}%</span>
                            @endif
                            @else
                            <span class="text-xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Stock -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Stok</label>
                        @if($product->manage_stock)
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-900">{{ $product->stock }} unit</span>
                            @if($product->is_low_stock)
                            <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Stok Menipis</span>
                            @elseif(!$product->is_in_stock)
                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Habis</span>
                            @else
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Tersedia</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">Minimum: {{ $product->min_stock }} unit</p>
                        @else
                        <p class="text-gray-900">Unlimited</p>
                        @endif
                    </div>

                    <!-- Weight -->
                    @if($product->weight)
                    <div>
                        <label class="text-sm font-medium text-gray-700">Berat</label>
                        <p class="text-gray-900">{{ $product->weight }} kg</p>
                    </div>
                    @endif

                    <!-- Status -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Status</label>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                            @if($product->is_featured)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Featured
                            </span>
                            @endif
                            @if($product->is_on_sale)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Sale
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Dates -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Tanggal</label>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Dibuat:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Diperbarui:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-800">Aksi Cepat</h4>
                </div>
                <div class="p-6 space-y-3">
                    @can('product.edit')
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center block transition duration-150">
                        <i class="fas fa-edit mr-2"></i>Edit Produk
                    </a>
                    @endcan

                    @if($product->is_active)
                    <form action="{{ route('admin.products.bulk-action') }}" method="POST" class="inline-block w-full">
                        @csrf
                        <input type="hidden" name="action" value="deactivate">
                        <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                        <button type="submit" 
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition duration-150">
                            <i class="fas fa-eye-slash mr-2"></i>Nonaktifkan
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.products.bulk-action') }}" method="POST" class="inline-block w-full">
                        @csrf
                        <input type="hidden" name="action" value="activate">
                        <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-150">
                            <i class="fas fa-eye mr-2"></i>Aktifkan
                        </button>
                    </form>
                    @endif

                    @if(!$product->is_featured)
                    <form action="{{ route('admin.products.bulk-action') }}" method="POST" class="inline-block w-full">
                        @csrf
                        <input type="hidden" name="action" value="feature">
                        <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                        <button type="submit" 
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-150">
                            <i class="fas fa-star mr-2"></i>Jadikan Featured
                        </button>
                    </form>
                    @endif

                    @can('product.delete')
                    @if($product->orderItems->count() === 0)
                    <form action="{{ route('admin.products.destroy', $product) }}" 
                          method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                          class="inline-block w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-150">
                            <i class="fas fa-trash mr-2"></i>Hapus Produk
                        </button>
                    </form>
                    @else
                    <button disabled 
                            class="w-full bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed"
                            title="Produk tidak dapat dihapus karena sudah ada dalam pesanan">
                        <i class="fas fa-trash mr-2"></i>Tidak Dapat Dihapus
                    </button>
                    @endif
                    @endcan

                    <!-- View Frontend -->
                    <a href="#" target="_blank"
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-center block transition duration-150">
                        <i class="fas fa-external-link-alt mr-2"></i>Lihat di Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function changeMainImage(imageSrc, thumbnail) {
    document.getElementById('main-image').src = imageSrc;
    
    // Remove active class from all thumbnails
    document.querySelectorAll('.aspect-square').forEach(thumb => {
        thumb.classList.remove('ring-2', 'ring-blue-500');
    });
    
    // Add active class to clicked thumbnail
    thumbnail.classList.add('ring-2', 'ring-blue-500');
}
</script>
@endpush
@endsection