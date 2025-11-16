@extends('admin.layouts.app')

@section('title', 'Category Details')
@section('page-title', 'Category Details')
@section('page-description', 'View and manage category information, products, and settings.')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.categories.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm font-medium">Back to Categories</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left - 2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Category Information -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Category Information</h3>
                </div>
                
                <div class="p-6">
                    <div class="flex gap-6">
                        <!-- Image -->
                        <div class="flex-shrink-0">
                            @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" 
                                 alt="{{ $category->name }}"
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                            @else
                            <div class="w-32 h-32 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-3xl text-gray-400"></i>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="flex-1 space-y-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
                                <p class="text-sm text-gray-500 mt-1">{{ $category->slug }}</p>
                            </div>
                            
                            @if($category->description)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                <p class="text-gray-600 leading-relaxed">{{ $category->description }}</p>
                            </div>
                            @endif
                            
                            <div class="flex items-center gap-6">
                                <div>
                                    <h4 class="text-xs font-medium text-gray-500 mb-1">Status</h4>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-medium rounded-lg 
                                        {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        <i class="fas fa-circle text-xs"></i>
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                
                                <div>
                                    <h4 class="text-xs font-medium text-gray-500 mb-1">Total Products</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $category->products->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            @if($category->products->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Products in this Category</h3>
                            <p class="text-sm text-gray-500 mt-1">Showing {{ min(6, $category->products->count()) }} of {{ $category->products->count() }} products</p>
                        </div>
                        @can('product.index')
                        <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" 
                           class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        @endcan
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($category->products->take(6) as $product)
                        <a href="{{ route('admin.products.show', $product) }}" 
                           class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:border-gray-300 hover:shadow-sm transition-all group">
                            <!-- Product Image/Icon -->
                            <div class="flex-shrink-0">
                                @if($product->images && $product->images->first())
                                <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-16 h-16 object-cover rounded-lg">
                                @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 text-xl"></i>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-600 transition-colors">
                                    {{ $product->name }}
                                </h4>
                                <p class="text-sm text-gray-500 mt-0.5">SKU: {{ $product->sku }}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        Stock: {{ $product->stock }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Arrow Icon -->
                            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <!-- No Products -->
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-box-open text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Products Yet</h3>
                <p class="text-gray-500 mb-6">This category doesn't have any products yet.</p>
                @can('product.create')
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>Add Product</span>
                </a>
                @endcan
            </div>
            @endif
        </div>

        <!-- Sidebar (Right - 1/3) -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                
                <div class="p-6 space-y-3">
                    @can('category.edit')
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium">
                        <i class="fas fa-edit"></i>
                        <span>Edit Category</span>
                    </a>
                    @endcan
                    
                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-box"></i>
                        <span>View All Products</span>
                    </a>
                    
                    @can('product.create')
                    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-plus"></i>
                        <span>Add Product</span>
                    </a>
                    @endcan
                    
                    @can('category.delete')
                    @if($category->products->count() === 0)
                    <form action="{{ route('admin.categories.destroy', $category) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition-colors font-medium">
                            <i class="fas fa-trash"></i>
                            <span>Delete Category</span>
                        </button>
                    </form>
                    @else
                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-xs text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Cannot delete category with products
                        </p>
                    </div>
                    @endif
                    @endcan
                </div>
            </div>

            <!-- Category Stats -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Products</span>
                        <span class="text-lg font-bold text-gray-900">{{ $category->products->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Active Products</span>
                        <span class="text-lg font-bold text-green-600">{{ $category->products->where('is_active', true)->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Out of Stock</span>
                        <span class="text-lg font-bold text-red-600">{{ $category->products->where('stock', 0)->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900">Metadata</h3>
                </div>
                
                <div class="p-6 space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Category ID</span>
                        <p class="font-medium text-gray-900 mt-0.5">{{ $category->id }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Created</span>
                        <p class="font-medium text-gray-900 mt-0.5">{{ $category->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Last Updated</span>
                        <p class="font-medium text-gray-900 mt-0.5">{{ $category->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Slug</span>
                        <p class="font-mono text-xs text-gray-900 mt-0.5 bg-white px-2 py-1 rounded border border-gray-200">
                            {{ $category->slug }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection