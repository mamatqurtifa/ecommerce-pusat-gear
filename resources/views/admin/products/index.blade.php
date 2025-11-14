@extends('admin.layouts.app')

@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="space-y-6">
    <!-- Header & Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Produk</h2>
        @can('product.create')
        <a href="{{ route('admin.products.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-150 ease-in-out inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>Tambah Produk
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" 
                           name="search" 
                           value="{{ $request->search }}"
                           placeholder="Nama, SKU, atau deskripsi..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $request->category == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="1" {{ $request->status === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $request->status === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Stock Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <select name="stock_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Stok</option>
                        <option value="in_stock" {{ $request->stock_status === 'in_stock' ? 'selected' : '' }}>Tersedia</option>
                        <option value="low_stock" {{ $request->stock_status === 'low_stock' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="out_of_stock" {{ $request->stock_status === 'out_of_stock' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="created_at" {{ $request->sort_by === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="name" {{ $request->sort_by === 'name' ? 'selected' : '' }}>Nama</option>
                        <option value="price" {{ $request->sort_by === 'price' ? 'selected' : '' }}>Harga</option>
                        <option value="stock" {{ $request->sort_by === 'stock' ? 'selected' : '' }}>Stok</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-150">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-150">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-xl shadow-sm p-4" id="bulk-actions" style="display: none;">
        <form id="bulk-form" method="POST" action="{{ route('admin.products.bulk-action') }}">
            @csrf
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700">Aksi untuk <span id="selected-count">0</span> produk:</span>
                <select name="action" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Aksi</option>
                    <option value="activate">Aktifkan</option>
                    <option value="deactivate">Nonaktifkan</option>
                    <option value="feature">Jadikan Featured</option>
                    <option value="unfeature">Hapus dari Featured</option>
                    <option value="delete">Hapus</option>
                </select>
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-150"
                        onclick="return confirm('Apakah Anda yakin?')">
                    Jalankan
                </button>
                <button type="button" 
                        onclick="clearSelection()"
                        class="text-gray-500 hover:text-gray-700">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($products->count() > 0)
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               id="select-all" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Pilih Semua</span>
                    </label>
                </div>
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 p-6">
            @foreach($products as $product)
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-150 product-card">
                <!-- Product Image -->
                <div class="relative h-48 bg-gray-100">
                    @if($product->images && count($product->images) > 0)
                    <img src="{{ Storage::url($product->images[0]) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-box text-4xl text-gray-400"></i>
                    </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="absolute top-2 left-2 space-y-1">
                        @if($product->is_featured)
                        <span class="inline-block bg-yellow-500 text-white text-xs px-2 py-1 rounded">Featured</span>
                        @endif
                        @if(!$product->is_active)
                        <span class="inline-block bg-red-500 text-white text-xs px-2 py-1 rounded">Tidak Aktif</span>
                        @endif
                        @if($product->manage_stock && $product->stock <= $product->min_stock)
                        <span class="inline-block bg-orange-500 text-white text-xs px-2 py-1 rounded">Stok Menipis</span>
                        @endif
                    </div>

                    <!-- Checkbox -->
                    <div class="absolute top-2 right-2">
                        <input type="checkbox" 
                               name="product_ids[]" 
                               value="{{ $product->id }}"
                               class="product-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded bg-white">
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h3>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ Str::limit($product->short_description, 80) }}</p>
                    
                    <!-- Price -->
                    <div class="mb-3">
                        @if($product->sale_price)
                        <div class="flex items-center space-x-2">
                            <span class="text-lg font-bold text-green-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                            <span class="text-sm text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        @else
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <!-- Stock & SKU -->
                    <div class="flex justify-between items-center text-sm mb-4">
                        <span class="text-gray-600">SKU: {{ $product->sku }}</span>
                        @if($product->manage_stock)
                        <span class="text-gray-600">Stok: {{ $product->stock }}</span>
                        @else
                        <span class="text-green-600">Unlimited</span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="text-blue-600 hover:text-blue-700">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('product.edit')
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="text-yellow-600 hover:text-yellow-700">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            @can('product.delete')
                            <form action="{{ route('admin.products.destroy', $product) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                        
                        <!-- Status Toggle -->
                        <div class="flex items-center">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $products->links() }}
        </div>
        @endif

        @else
        <div class="p-12 text-center">
            <div class="text-gray-500">
                <i class="fas fa-inbox text-6xl mb-4"></i>
                <h3 class="text-lg font-medium mb-2">Tidak ada produk</h3>
                <p class="text-gray-400 mb-4">Belum ada produk yang ditambahkan atau tidak ada yang sesuai dengan filter.</p>
                @can('product.create')
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Tambah Produk Pertama
                </a>
                @endcan
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    // Select all functionality
    selectAll.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox functionality
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        
        if (checkedBoxes.length > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = checkedBoxes.length;
            
            // Add hidden inputs for selected products
            const existingInputs = bulkForm.querySelectorAll('input[name="product_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_ids[]';
                input.value = checkbox.value;
                bulkForm.appendChild(input);
            });
        } else {
            bulkActions.style.display = 'none';
        }
        
        // Update select all checkbox
        selectAll.checked = checkedBoxes.length === productCheckboxes.length;
        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < productCheckboxes.length;
    }

    window.clearSelection = function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAll.checked = false;
        bulkActions.style.display = 'none';
    };
});
</script>
@endpush

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
@endsection