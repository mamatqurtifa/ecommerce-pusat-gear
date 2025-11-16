@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')
@section('page-description', 'Update category information, image, and settings to organize your products.')

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

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form (Left - 2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
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
                                   value="{{ old('name', $category->name) }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Enter category name"
                                   required>
                            @error('name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                      placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Category Image -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Category Image</h3>
                        <p class="text-sm text-gray-500 mt-1">Upload an image to represent this category</p>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Current Image Preview -->
                        @if($category->image)
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Current Image</label>
                            <div class="relative inline-block">
                                <img src="{{ Storage::url($category->image) }}" 
                                     alt="{{ $category->name }}"
                                     class="h-32 w-32 object-cover rounded-lg border border-gray-200"
                                     id="current-image">
                                <button type="button" 
                                        onclick="document.getElementById('current-image').classList.toggle('opacity-50')"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @endif

                        <!-- Image Upload -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-900 mb-2">
                                {{ $category->image ? 'Change Image' : 'Upload Image' }}
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-all">
                                <div class="space-y-2 text-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
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
                                </div>
                            </div>
                            
                            <!-- New Image Preview -->
                            <div id="image-preview" class="mt-4 hidden">
                                <label class="block text-sm font-medium text-gray-900 mb-2">New Image Preview</label>
                                <img id="preview-img" class="h-32 w-32 object-cover rounded-lg border border-gray-200">
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
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                   class="h-5 w-5 text-gray-900 focus:ring-gray-900 border-gray-300 rounded">
                            <span class="ml-3">
                                <span class="text-sm font-medium text-gray-900">Active Category</span>
                                <span class="block text-xs text-gray-500 mt-0.5">Enable this category to be visible</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Category Info -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">Category Information</h3>
                    </div>
                    
                    <div class="p-6 space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Category ID</span>
                            <p class="font-medium text-gray-900 mt-0.5">{{ $category->id }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Slug</span>
                            <p class="font-mono text-xs text-gray-900 mt-0.5 bg-white px-2 py-1 rounded border border-gray-200">
                                {{ $category->slug }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Products</span>
                            <p class="font-medium text-gray-900 mt-0.5">{{ $category->products()->count() }} products</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Created</span>
                            <p class="font-medium text-gray-900 mt-0.5">{{ $category->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Last Updated</span>
                            <p class="font-medium text-gray-900 mt-0.5">{{ $category->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium shadow-sm">
                        <i class="fas fa-save"></i>
                        <span>Save Changes</span>
                    </button>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>

                    <a href="{{ route('admin.categories.show', $category) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors font-medium">
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
</script>
@endpush
@endsection