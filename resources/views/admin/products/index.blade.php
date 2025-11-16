@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')
@section('page-description', 'Manage your product catalog: add, edit, set prices, stock, and status')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Products -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Total Products</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_products'] ?? 0 }}</div>
            <div class="text-xs text-gray-500">All products</div>
        </div>

        <!-- Active Products -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Active Products</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['active_products'] ?? 0 }}</div>
            <div class="text-xs text-gray-500">Currently active</div>
        </div>

        <!-- Low Stock -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Low Stock</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['low_stock'] ?? 0 }}</div>
            <div class="text-xs text-gray-500">Need restock</div>
        </div>

        <!-- Featured Products -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Featured</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['featured_products'] ?? 0 }}</div>
            <div class="text-xs text-gray-500">Featured products</div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl border border-gray-200">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.products.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <input type="text" 
                               name="search" 
                               value="{{ $request->search }}"
                               placeholder="Search name, SKU, or description..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>

                    <!-- Category Filter -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button"
                                @click="open = !open"
                                class="w-full inline-flex items-center justify-between gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors bg-white">
                            <span class="text-sm text-gray-700 truncate">
                                @if($request->category)
                                    {{ $categories->find($request->category)?->name ?? 'All Categories' }}
                                @else
                                    All Categories
                                @endif
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-500 flex-shrink-0"></i>
                        </button>
                        
                        <div x-show="open"
                             @click.away="open = false"
                             class="absolute left-0 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10 max-h-60 overflow-y-auto"
                             style="display: none;">
                            <a href="{{ route('admin.products.index', array_merge(request()->except('category'), ['category' => ''])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">All Categories</a>
                            @foreach($categories as $category)
                            <a href="{{ route('admin.products.index', array_merge(request()->except('category'), ['category' => $category->id])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ $request->category == $category->id ? 'bg-gray-50 font-medium' : '' }}">
                                {{ $category->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button"
                                @click="open = !open"
                                class="w-full inline-flex items-center justify-between gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors bg-white">
                            <span class="text-sm text-gray-700">
                                @if($request->status === '1')
                                    Active
                                @elseif($request->status === '0')
                                    Inactive
                                @else
                                    All Status
                                @endif
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>
                        
                        <div x-show="open"
                             @click.away="open = false"
                             class="absolute left-0 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10"
                             style="display: none;">
                            <a href="{{ route('admin.products.index', array_merge(request()->except('status'), ['status' => ''])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">All Status</a>
                            <a href="{{ route('admin.products.index', array_merge(request()->except('status'), ['status' => '1'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Active</a>
                            <a href="{{ route('admin.products.index', array_merge(request()->except('status'), ['status' => '0'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Inactive</a>
                        </div>
                    </div>

                    <!-- Stock Filter -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button"
                                @click="open = !open"
                                class="w-full inline-flex items-center justify-between gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors bg-white">
                            <span class="text-sm text-gray-700">
                                @if($request->stock_status === 'in_stock')
                                    In Stock
                                @elseif($request->stock_status === 'low_stock')
                                    Low Stock
                                @elseif($request->stock_status === 'out_of_stock')
                                    Out of Stock
                                @else
                                    All Stock
                                @endif
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>
                        
                        <div x-show="open"
                             @click.away="open = false"
                             class="absolute left-0 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10"
                             style="display: none;">
                            <a href="{{ route('admin.products.index', array_merge(request()->except('stock_status'), ['stock_status' => ''])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">All Stock</a>
                            <a href="{{ route('admin.products.index', array_merge(request()->except('stock_status'), ['stock_status' => 'in_stock'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">In Stock</a>
                            <a href="{{ route('admin.products.index', array_merge(request()->except('stock_status'), ['stock_status' => 'low_stock'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Low Stock</a>
                            <a href="{{ route('admin.products.index', array_merge(request()->except('stock_status'), ['stock_status' => 'out_of_stock'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Out of Stock</a>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Search Button -->
                    <button type="submit" 
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Reset Button -->
                    @if($request->search || $request->category || $request->status || $request->stock_status)
                    <a href="{{ route('admin.products.index') }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif

                    <div class="flex-1"></div>

                    <!-- Add Product Button -->
                    <a href="{{ route('admin.products.create') }}" 
                       class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-plus"></i>
                        <span class="text-sm font-medium">Add Product</span>
                    </a>

                    <!-- Export Button -->
                    <a href="{{ route('admin.products.index', array_merge(request()->all(), ['export' => 'excel'])) }}" 
                       class="p-2 hover:bg-gray-100 rounded-lg transition-colors" 
                       title="Export to Excel">
                        <i class="fas fa-download text-gray-600"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Bulk Actions Bar -->
        <div id="bulk-actions" 
             class="px-6 py-4 bg-blue-50 border-b border-blue-200 hidden">
            <form id="bulk-form" method="POST" action="{{ route('admin.products.bulk-action') }}" class="flex items-center gap-4">
                @csrf
                <span class="text-sm font-medium text-blue-900">
                    <span id="selected-count">0</span> products selected
                </span>
                
                <select name="action" 
                        class="px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-600 bg-white text-sm">
                    <option value="">Select Action</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="feature">Set as Featured</option>
                    <option value="unfeature">Remove from Featured</option>
                    <option value="delete">Delete</option>
                </select>
                
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                        onclick="return confirm('Are you sure you want to perform this action?')">
                    Apply
                </button>
                
                <button type="button" 
                        onclick="clearSelection()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Clear Selection
                </button>
            </form>
        </div>

        @if($products->count() > 0)
        <!-- Select All Bar -->
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" 
                       id="select-all" 
                       class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                <span class="ml-2 text-sm font-medium text-gray-700">Select All</span>
            </label>
            <div class="text-sm text-gray-600">
                Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} products
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 p-6">
            @foreach($products as $product)
            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all product-card">
                <!-- Product Image -->
                <div class="relative h-48 bg-gray-100">
                    @if($product->images && count($product->images) > 0)
                    <img src="{{ Storage::url($product->images[0]) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-box text-4xl text-gray-300"></i>
                    </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                        @if($product->is_featured)
                        <span class="inline-flex items-center gap-1 bg-yellow-500 text-white text-xs px-2 py-1 rounded font-medium">
                            <i class="fas fa-star"></i> Featured
                        </span>
                        @endif
                        @if(!$product->is_active)
                        <span class="inline-flex items-center gap-1 bg-red-500 text-white text-xs px-2 py-1 rounded font-medium">
                            <i class="fas fa-times-circle"></i> Inactive
                        </span>
                        @endif
                        @if($product->manage_stock && $product->stock <= $product->min_stock && $product->stock > 0)
                        <span class="inline-flex items-center gap-1 bg-orange-500 text-white text-xs px-2 py-1 rounded font-medium">
                            <i class="fas fa-exclamation-triangle"></i> Low Stock
                        </span>
                        @endif
                        @if($product->manage_stock && $product->stock == 0)
                        <span class="inline-flex items-center gap-1 bg-red-600 text-white text-xs px-2 py-1 rounded font-medium">
                            <i class="fas fa-ban"></i> Out of Stock
                        </span>
                        @endif
                    </div>

                    <!-- Checkbox -->
                    <div class="absolute top-2 right-2">
                        <input type="checkbox" 
                               name="product_ids[]" 
                               value="{{ $product->id }}"
                               class="product-checkbox h-5 w-5 rounded border-gray-300 text-gray-900 focus:ring-gray-900 bg-white shadow-sm">
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <div class="mb-2">
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-1">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                    </div>
                    
                    <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ Str::limit($product->short_description, 80) }}</p>
                    
                    <!-- Price -->
                    <div class="mb-3">
                        @if($product->sale_price)
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-green-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        @else
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <!-- Stock & SKU -->
                    <div class="flex justify-between items-center text-xs mb-4 pb-4 border-b border-gray-100">
                        <span class="text-gray-600">SKU: {{ $product->sku }}</span>
                        @if($product->manage_stock)
                        <span class="font-medium {{ $product->stock > 0 ? 'text-gray-900' : 'text-red-600' }}">
                            Stock: {{ $product->stock }}
                        </span>
                        @else
                        <span class="text-green-600 font-medium">Unlimited</span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="text-gray-600 hover:text-gray-900 transition-colors"
                               title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="text-gray-600 hover:text-gray-900 transition-colors"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Status Badge -->
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg 
                            {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <i class="fas fa-circle text-[6px]"></i>
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }}
                </div>

                <div class="flex items-center gap-1">
                    @if($products->onFirstPage())
                        <button disabled class="px-3 py-2 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </a>
                    @endif

                    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if($page == $products->currentPage())
                            <button class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium">{{ $page }}</button>
                        @elseif($page == 1 || $page == $products->lastPage() || abs($page - $products->currentPage()) <= 2)
                            <a href="{{ $url }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">{{ $page }}</a>
                        @elseif($page == 2 || $page == $products->lastPage() - 1)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                    @endforeach

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </a>
                    @else
                        <button disabled class="px-3 py-2 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @else
        <div class="p-12 text-center">
            <div class="text-gray-500">
                <i class="fas fa-box-open text-5xl mb-4 text-gray-300"></i>
                <h3 class="text-lg font-medium mb-2">No products found</h3>
                <p class="text-sm text-gray-400 mb-6">
                    @if(request()->hasAny(['search', 'category', 'status', 'stock_status']))
                        Try adjusting your filters or search query.
                    @else
                        Start by adding your first product to the catalog.
                    @endif
                </p>
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>Add First Product</span>
                </a>
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

    if (selectAll) {
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
    }

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        
        if (checkedBoxes.length > 0) {
            bulkActions.classList.remove('hidden');
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
            bulkActions.classList.add('hidden');
        }
        
        // Update select all checkbox
        if (selectAll) {
            selectAll.checked = checkedBoxes.length === productCheckboxes.length;
            selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < productCheckboxes.length;
        }
    }

    window.clearSelection = function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        if (selectAll) selectAll.checked = false;
        bulkActions.classList.add('hidden');
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