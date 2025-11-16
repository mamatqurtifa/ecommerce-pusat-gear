@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories')
@section('page-description', 'Manage categories by adding, editing, and organizing categories to structure your products.')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Categories -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-600 mb-1">Total Categories</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_categories'] ?? 0 }}</div>
            <div class="text-xs text-gray-500">All product categories</div>
        </div>

        <!-- Active Categories -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-600 mb-1">Active Categories</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['active_categories'] ?? 0 }}</div>
            <div class="text-xs text-gray-500">Currently active</div>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-600 mb-1">Total Products</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_products'] ?? 0 }}</div>
            <div class="text-xs text-gray-500">Across all categories</div>
        </div>

        <!-- Empty Categories -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-600 mb-1">Empty Categories</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['empty_categories'] ?? 0 }}</div>
            <div class="text-xs text-gray-500">Without products</div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl border border-gray-200">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-wrap items-center gap-3">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search categories..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>

                <!-- Status Filter -->
                <div class="relative">
                    <select name="status" 
                            class="appearance-none w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent bg-white cursor-pointer"
                            onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Search Button -->
                <button type="submit" 
                        class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-search"></i>
                </button>

                <!-- Reset Button -->
                @if(request('search') || request('status'))
                <a href="{{ route('admin.categories.index') }}" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
                @endif

                <div class="flex-1"></div>

                <!-- Add Category Button -->
                <a href="{{ route('admin.categories.create') }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span class="text-sm font-medium">Add Category</span>
                </a>

                <!-- Export Button -->
                <a href="{{ route('admin.categories.index', array_merge(request()->all(), ['export' => 'excel'])) }}" 
                   class="p-2 hover:bg-gray-100 rounded-lg transition-colors" 
                   title="Export to Excel">
                    <i class="fas fa-download text-gray-600"></i>
                </a>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    @if($category->image)
                                    <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" 
                                         src="{{ Storage::url($category->image) }}" 
                                         alt="{{ $category->name }}">
                                    @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $category->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $category->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 max-w-xs truncate">
                                {{ $category->description ?: '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900">{{ $category->products_count }}</span>
                                <span class="text-xs text-gray-500">products</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-lg {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                <i class="fas fa-circle text-[6px]"></i>
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $category->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.categories.show', $category) }}" 
                                   class="text-gray-600 hover:text-gray-900 transition-colors"
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="text-gray-600 hover:text-gray-900 transition-colors"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition-colors {{ $category->products_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            title="{{ $category->products_count > 0 ? 'Cannot delete category with products' : 'Delete' }}"
                                            {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-folder-open text-5xl mb-4 text-gray-300"></i>
                                <h3 class="text-lg font-medium mb-2">No categories found</h3>
                                <p class="text-sm text-gray-400 mb-6">
                                    @if(request('search') || request('status'))
                                        Try adjusting your filters or search query.
                                    @else
                                        Start by creating your first category.
                                    @endif
                                </p>
                                @if(!request('search') && !request('status'))
                                <a href="{{ route('admin.categories.create') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                                    <i class="fas fa-plus"></i>
                                    <span>Add First Category</span>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Showing {{ $categories->firstItem() }}-{{ $categories->lastItem() }} of {{ $categories->total() }}
                </div>

                <div class="flex items-center gap-1">
                    @if($categories->onFirstPage())
                        <button disabled class="px-3 py-2 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                    @else
                        <a href="{{ $categories->previousPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </a>
                    @endif

                    @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                        @if($page == $categories->currentPage())
                            <button class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium">{{ $page }}</button>
                        @elseif($page == 1 || $page == $categories->lastPage() || abs($page - $categories->currentPage()) <= 2)
                            <a href="{{ $url }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">{{ $page }}</a>
                        @elseif($page == 2 || $page == $categories->lastPage() - 1)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                    @endforeach

                    @if($categories->hasMorePages())
                        <a href="{{ $categories->nextPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
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
    </div>
</div>
@endsection