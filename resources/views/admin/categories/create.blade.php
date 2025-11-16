@extends('admin.layouts.app')

@section('title', 'Create Category')
@section('page-title', 'Create New Category')
@section('page-description', 'Add a new category to organize and structure your product catalog.')

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

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form (Left - 2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                        <p class="text-sm text-gray-500 mt-1">Enter the category name and description</p>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Enter category name (e.g., Electronics, Clothing)"
                                   required>
                            @error('name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                This will be used to identify and organize your products
                            </p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent resize-none @error('description') border-red-500 @enderror"
                                      placeholder="Enter a detailed description of this category...">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500">
                                Optional - Provide additional context about what products belong in this category
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Category Image -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Category Image</h3>
                        <p class="text-sm text-gray-500 mt-1">Upload an image to represent this category (optional)</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Image Upload -->
                        <div>
                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-all cursor-pointer" 
                                 onclick="document.getElementById('image').click()">
                                <div class="space-y-2 text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                            <span>Upload a file</span>
                                            <input id="image" 
                                                   name="image" 
                                                   type="file" 
                                                   class="sr-only"
                                                   accept="image/*"
                                                   onchange="previewImage(this)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                                    <p class="text-xs text-gray-400">Recommended size: 400x400px</p>
                                </div>
                            </div>
                            
                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-4 hidden">
                                <label class="block text-sm font-medium text-gray-900 mb-2">Image Preview</label>
                                <div class="relative inline-block">
                                    <img id="preview-img" class="h-40 w-40 object-cover rounded-lg border border-gray-200">
                                    <button type="button" 
                                            onclick="clearImage()"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                            </div>
                            
                            @error('image')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right - 1/3) -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Status</h3>
                    </div>
                    
                    <div class="p-6">
                        <label class="flex items-start cursor-pointer group">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="mt-1 h-5 w-5 text-gray-900 focus:ring-gray-900 border-gray-300 rounded">
                            <span class="ml-3">
                                <span class="text-sm font-medium text-gray-900 group-hover:text-gray-700">Active Category</span>
                                <span class="block text-xs text-gray-500 mt-1">Enable this category to make it visible and available for products</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Tips & Guidelines -->
                <div class="bg-blue-50 rounded-xl border border-blue-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-blue-200">
                        <h3 class="text-sm font-semibold text-blue-900 flex items-center gap-2">
                            <i class="fas fa-lightbulb"></i>
                            Tips & Guidelines
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-3 text-xs text-blue-800">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                                <span>Use clear and descriptive names that customers can easily understand</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                                <span>Add a detailed description to help with SEO and customer understanding</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                                <span>Upload a high-quality image that represents the category well</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                                <span>Keep category names consistent with your brand and product structure</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium shadow-sm">
                        <i class="fas fa-save"></i>
                        <span>Create Category</span>
                    </button>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('preview-img').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function clearImage() {
    document.getElementById('image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('preview-img').src = '';
}
</script>
@endpush
@endsection