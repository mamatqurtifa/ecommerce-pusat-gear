@extends('admin.layouts.app')

@section('title', 'Detail Kategori')
@section('page-title', 'Detail Kategori: ' . $category->name)

@section('content')
<div class="space-y-6">
    <!-- Category Info -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Kategori</h3>
                <div class="flex items-center space-x-2">
                    @can('category.edit')
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    @endcan
                    <a href="{{ route('admin.categories.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    @if($category->image)
                    <img src="{{ Storage::url($category->image) }}" 
                         alt="{{ $category->name }}"
                         class="w-full h-48 object-cover rounded-lg">
                    @else
                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                    @endif
                </div>
                
                <div class="md:col-span-2 space-y-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
                        <p class="text-gray-600">{{ $category->slug }}</p>
                    </div>
                    
                    @if($category->description)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Deskripsi</h4>
                        <p class="text-gray-900">{{ $category->description }}</p>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Status</h4>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Total Produk</h4>
                            <p class="text-xl font-bold text-blue-600">{{ $category->products->count() }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <strong>Dibuat:</strong> {{ $category->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <strong>Diperbarui:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    @if($category->products->count() > 0)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Produk Terbaru</h3>
                @can('product.index')
                <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" 
                   class="text-blue-600 hover:text-blue-700 text-sm">
                    Lihat Semua
                </a>
                @endcan
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($category->products->take(6) as $product)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-gray-400"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection