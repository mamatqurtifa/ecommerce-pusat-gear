@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product: ' . $product->name)
@section('page-description', 'Update product information, pricing, inventory, and status settings.')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.products.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm font-medium">Back to Products</span>
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Form (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Enter product name"
                                   required>
                            @error('name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-900 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" 
                                    name="category_id" 
                                    class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('category_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-900 mb-2">
                                SKU <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku', $product->sku) }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('sku') border-red-500 @enderror"
                                   placeholder="e.g., CAM-001"
                                   required>
                            @error('sku')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Description -->
                        <div>
                            <label for="short_description" class="block text-sm font-medium text-gray-900 mb-2">
                                Short Description
                            </label>
                            <textarea id="short_description" 
                                      name="short_description" 
                                      rows="3"
                                      class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent resize-none @error('short_description') border-red-500 @enderror"
                                      placeholder="Brief product description for preview">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Full Description -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Full Description</h3>
                    </div>
                    
                    <div class="p-6">
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                                Product Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="8"
                                      class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent resize-none @error('description') border-red-500 @enderror"
                                      placeholder="Complete product description, specifications, features, etc."
                                      required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Current Images -->
                @if($product->images && count($product->images) > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Current Images</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($product->images as $image)
                            <div class="relative">
                                <img src="{{ Storage::url($image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-32 object-cover rounded-lg border border-gray-200">
                            </div>
                            @endforeach
                        </div>
                        <p class="mt-3 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Uploading new images will replace all existing images
                        </p>
                    </div>
                </div>
                @endif

                <!-- Product Images -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $product->images && count($product->images) > 0 ? 'Replace Images' : 'Product Images' }}
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 hover:border-gray-400 transition-colors">
                            <div class="text-center">
                                <div class="mx-auto w-12 h-12 text-gray-400 mb-4">
                                    <i class="fas fa-cloud-upload-alt text-4xl"></i>
                                </div>
                                <div class="flex justify-center text-sm text-gray-600">
                                    <label for="images" class="relative cursor-pointer rounded-md font-medium text-gray-900 hover:text-gray-700">
                                        <span>Upload images</span>
                                        <input id="images" 
                                               name="images[]" 
                                               type="file" 
                                               class="sr-only"
                                               accept="image/*"
                                               multiple
                                               onchange="previewImages(this)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG, WEBP up to 2MB (max 5 images)</p>
                            </div>
                        </div>
                        
                        <!-- Image Previews -->
                        <div id="image-previews" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 hidden"></div>
                        
                        @error('images.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar (1/3 width) -->
            <div class="space-y-6">
                <!-- Pricing -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pricing</h3>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- Regular Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-900 mb-2">
                                Regular Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">Rp</span>
                                </div>
                                <input type="number" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $product->price) }}"
                                       class="w-full pl-12 pr-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('price') border-red-500 @enderror"
                                       placeholder="0"
                                       min="0"
                                       step="0.01"
                                       required>
                            </div>
                            @error('price')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sale Price -->
                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-gray-900 mb-2">
                                Sale Price
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">Rp</span>
                                </div>
                                <input type="number" 
                                       id="sale_price" 
                                       name="sale_price" 
                                       value="{{ old('sale_price', $product->sale_price) }}"
                                       class="w-full pl-12 pr-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('sale_price') border-red-500 @enderror"
                                       placeholder="0"
                                       min="0"
                                       step="0.01">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500">Leave empty if no discount</p>
                            @error('sale_price')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Stock Management -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Stock Management</h3>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- Manage Stock Checkbox -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       id="manage_stock" 
                                       name="manage_stock"
                                       value="1"
                                       {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}
                                       class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded"
                                       onchange="toggleStockFields()">
                                <span class="ml-2 text-sm text-gray-900">Enable stock management</span>
                            </label>
                        </div>

                        <!-- Stock Fields -->
                        <div id="stock-fields">
                            <!-- Current Stock -->
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-900 mb-2">
                                    Current Stock <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock', $product->stock) }}"
                                       class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('stock') border-red-500 @enderror"
                                       min="0">
                                @error('stock')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Min Stock -->
                            <div>
                                <label for="min_stock" class="block text-sm font-medium text-gray-900 mb-2">
                                    Minimum Stock <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="min_stock" 
                                       name="min_stock" 
                                       value="{{ old('min_stock', $product->min_stock) }}"
                                       class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('min_stock') border-red-500 @enderror"
                                       min="0">
                                <p class="mt-1.5 text-xs text-gray-500">Alert when stock reaches this level</p>
                                @error('min_stock')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Additional Information</h3>
                    </div>
                    
                    <div class="p-6">
                        <!-- Weight -->
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-900 mb-2">
                                Weight (kg)
                            </label>
                            <input type="number" 
                                   id="weight" 
                                   name="weight" 
                                   value="{{ old('weight', $product->weight) }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('weight') border-red-500 @enderror"
                                   placeholder="0.00"
                                   min="0"
                                   step="0.01">
                            <p class="mt-1.5 text-xs text-gray-500">For shipping cost calculation</p>
                            @error('weight')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Status -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Product Status</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3">
                            <!-- Active Status -->
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-900">Active product</span>
                            </label>

                            <!-- Featured Status -->
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       id="is_featured" 
                                       name="is_featured"
                                       value="1"
                                       {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                       class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-900">Featured product</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium shadow-sm">
                        <i class="fas fa-save"></i>
                        <span>Update Product</span>
                    </button>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>

                    <a href="{{ route('admin.products.show', $product) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                    </a>
                </div>
            </div>
        </div>
    </form>
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
                    <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                    <button type="button" onclick="removeImage(${index})" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700 shadow-sm">
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