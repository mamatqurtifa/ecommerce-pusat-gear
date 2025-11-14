@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Form Tambah Produk</h3>
        </div>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Product Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Informasi Dasar</h4>
                        
                        <!-- Product Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="Masukkan nama produk"
                                   required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" 
                                    name="category_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div class="mb-4">
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                SKU <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror"
                                   placeholder="Contoh: CAM-001"
                                   required>
                            @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Description -->
                        <div class="mb-4">
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Singkat
                            </label>
                            <textarea id="short_description" 
                                      name="short_description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('short_description') border-red-500 @enderror"
                                      placeholder="Deskripsi singkat produk untuk preview">{{ old('short_description') }}</textarea>
                            @error('short_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Deskripsi Detail</h4>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="8"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Deskripsi lengkap produk, spesifikasi, fitur, dll."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Gambar Produk</h4>
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-gray-400 transition duration-150">
                            <div class="text-center">
                                <div class="mx-auto w-12 h-12 text-gray-400 mb-4">
                                    <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                </div>
                                <div class="flex text-sm text-gray-600">
                                    <label for="images" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                        <span>Upload gambar</span>
                                        <input id="images" 
                                               name="images[]" 
                                               type="file" 
                                               class="sr-only"
                                               accept="image/*"
                                               multiple
                                               onchange="previewImages(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG, WEBP up to 2MB (maksimal 5 gambar)</p>
                            </div>
                        </div>
                        
                        <!-- Image Previews -->
                        <div id="image-previews" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 hidden"></div>
                        
                        @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Pricing -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Harga</h4>
                        
                        <!-- Regular Price -->
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga Regular <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">Rp</span>
                                </div>
                                <input type="number" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price') }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                                       placeholder="0"
                                       min="0"
                                       step="0.01"
                                       required>
                            </div>
                            @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sale Price -->
                        <div class="mb-4">
                            <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga Diskon
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">Rp</span>
                                </div>
                                <input type="number" 
                                       id="sale_price" 
                                       name="sale_price" 
                                       value="{{ old('sale_price') }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('sale_price') border-red-500 @enderror"
                                       placeholder="0"
                                       min="0"
                                       step="0.01">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada diskon</p>
                            @error('sale_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Stock Management -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Manajemen Stok</h4>
                        
                        <!-- Manage Stock Checkbox -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       id="manage_stock" 
                                       name="manage_stock"
                                       value="1"
                                       {{ old('manage_stock', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       onchange="toggleStockFields()">
                                <span class="ml-2 text-sm text-gray-700">Kelola stok produk</span>
                            </label>
                        </div>

                        <!-- Stock Fields -->
                        <div id="stock-fields">
                            <!-- Current Stock -->
                            <div class="mb-4">
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                                    Stok Saat Ini <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock', 0) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror"
                                       min="0">
                                @error('stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Min Stock -->
                            <div class="mb-4">
                                <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                    Minimum Stok <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="min_stock" 
                                       name="min_stock" 
                                       value="{{ old('min_stock', 5) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('min_stock') border-red-500 @enderror"
                                       min="0">
                                <p class="mt-1 text-xs text-gray-500">Notifikasi ketika stok mencapai batas ini</p>
                                @error('min_stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Informasi Tambahan</h4>
                        
                        <!-- Weight -->
                        <div class="mb-4">
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                                Berat (kg)
                            </label>
                            <input type="number" 
                                   id="weight" 
                                   name="weight" 
                                   value="{{ old('weight') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('weight') border-red-500 @enderror"
                                   placeholder="0.00"
                                   min="0"
                                   step="0.01">
                            <p class="mt-1 text-xs text-gray-500">Untuk perhitungan ongkir</p>
                            @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Status Produk</h4>
                        
                        <div class="space-y-3">
                            <!-- Active Status -->
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Aktifkan produk</span>
                            </label>

                            <!-- Featured Status -->
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       id="is_featured" 
                                       name="is_featured"
                                       value="1"
                                       {{ old('is_featured') ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Produk unggulan</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200">
                <a href="{{ route('admin.products.index') }}" 
                   class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleStockFields() {
    const manageStock = document.getElementById('manage_stock');
    const stockFields = document.getElementById('stock-fields');
    const stockInput = document.getElementById('stock');
    const minStockInput = document.getElementById('min_stock');
    
    if (manageStock.checked) {
        stockFields.style.display = 'block';
        stockInput.required = true;
        minStockInput.required = true;
    } else {
        stockFields.style.display = 'none';
        stockInput.required = false;
        minStockInput.required = false;
    }
}

function previewImages(input) {
    const preview = document.getElementById('image-previews');
    preview.innerHTML = '';
    
    if (input.files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(input.files).slice(0, 5).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-300">
                    <button type="button" onclick="removeImage(${index})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                preview.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    } else {
        preview.classList.add('hidden');
    }
}

function removeImage(index) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    previewImages(input);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    toggleStockFields();
});
</script>
@endpush
@endsection