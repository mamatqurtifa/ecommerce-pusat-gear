@extends('admin.layouts.app')

@section('title', 'Product Details')
@section('page-title', 'Product Details')
@section('page-description', 'View complete product information, sales analytics, and manage product settings')

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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content (Left - 2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Gallery -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Product Gallery</h3>
                    </div>
                    <div class="p-6">
                        @if ($product->images && count($product->images) > 0)
                            <div class="space-y-4">
                                <!-- Main Image -->
                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                    <img id="main-image" src="{{ Storage::url($product->images[0]) }}"
                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>

                                <!-- Thumbnail Images -->
                                @if (count($product->images) > 1)
                                    <div class="grid grid-cols-5 gap-2">
                                        @foreach ($product->images as $index => $image)
                                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer border-2 transition-all {{ $index === 0 ? 'border-gray-900' : 'border-gray-200 hover:border-gray-400' }}"
                                                onclick="changeMainImage('{{ Storage::url($image) }}', this)">
                                                <img src="{{ Storage::url($image) }}" alt="{{ $product->name }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div
                                class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                <div class="text-center text-gray-400">
                                    <i class="fas fa-image text-6xl mb-4 text-gray-300"></i>
                                    <p class="text-sm">No images</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Description -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Product Description</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if ($product->short_description)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-medium text-gray-900 mb-2">Summary</h5>
                                <p class="text-gray-700 text-sm">{{ $product->short_description }}</p>
                            </div>
                        @endif

                        <div>
                            <h5 class="font-medium text-gray-900 mb-3">Full Description</h5>
                            <div class="text-gray-700 text-sm whitespace-pre-line leading-relaxed">
                                {{ $product->description }}</div>
                        </div>
                    </div>
                </div>

                <!-- Sales Analytics -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Sales Analytics</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-lg p-4 text-center border border-gray-200">
                                <div class="text-2xl font-bold text-gray-900">{{ $product->orderItems->count() }}</div>
                                <div class="text-xs text-gray-500 mt-1">Total Orders</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center border border-gray-200">
                                <div class="text-2xl font-bold text-gray-900">{{ $product->orderItems->sum('quantity') }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Units Sold</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center border border-gray-200">
                                <div class="text-lg font-bold text-gray-900">Rp
                                    {{ number_format($product->orderItems->sum('total_price'), 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500 mt-1">Total Revenue</div>
                            </div>
                        </div>

                        @if ($recentOrders->count() > 0)
                            <div>
                                <h5 class="font-medium text-gray-900 mb-3">Recent Orders</h5>
                                <div class="space-y-2">
                                    @foreach ($recentOrders->take(5) as $orderItem)
                                        <a href="{{ route('admin.orders.show', $orderItem->order) }}"
                                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-900 text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $orderItem->order->user->name }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $orderItem->order->created_at->format('d M Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">{{ $orderItem->quantity }}
                                                    units</p>
                                                <p class="text-xs text-gray-500">Rp
                                                    {{ number_format($orderItem->total_price, 0, ',', '.') }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-chart-line text-4xl mb-3 text-gray-300"></i>
                                <p class="text-sm">No sales yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right - 1/3) -->
            <div class="space-y-6">
                <!-- Product Info -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Product Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Product Name -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Product Name</label>
                            <p class="text-base font-semibold text-gray-900 mt-1">{{ $product->name }}</p>
                        </div>

                        <!-- SKU -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">SKU</label>
                            <p
                                class="text-sm text-gray-900 mt-1 font-mono bg-gray-50 px-2 py-1 rounded border border-gray-200 inline-block">
                                {{ $product->sku }}</p>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Category</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $product->category->name }}</p>
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Price</label>
                            <div class="mt-1">
                                @if ($product->sale_price)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl font-bold text-green-600">Rp
                                            {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                        <span class="text-sm text-gray-400 line-through">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</span>
                                    </div>
                                    @if ($product->discount_percentage > 0)
                                        <span
                                            class="inline-block mt-1 bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-medium">-{{ $product->discount_percentage }}%</span>
                                    @endif
                                @else
                                    <span class="text-xl font-bold text-gray-900">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Stock Status</label>
                            @if ($product->manage_stock)
                                <div class="mt-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-gray-900">{{ $product->stock }}</span>
                                        <span class="text-sm text-gray-500">units</span>
                                        @if ($product->is_low_stock)
                                            <span
                                                class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded font-medium">Low
                                                Stock</span>
                                        @elseif(!$product->is_in_stock)
                                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-medium">Out
                                                of Stock</span>
                                        @else
                                            <span
                                                class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-medium">In
                                                Stock</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Minimum: {{ $product->min_stock }} units</p>
                                </div>
                            @else
                                <p class="text-sm text-gray-900 mt-1 font-medium">Unlimited</p>
                            @endif
                        </div>

                        <!-- Weight -->
                        @if ($product->weight)
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">Weight</label>
                                <p class="text-sm text-gray-900 mt-1">{{ $product->weight }} kg</p>
                            </div>
                        @endif

                        <!-- Status Badges -->
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase mb-2 block">Status</label>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg 
                                {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <i class="fas fa-circle text-[6px]"></i>
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if ($product->is_featured)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-yellow-100 text-yellow-700">
                                        <i class="fas fa-star text-[10px]"></i>
                                        Featured
                                    </span>
                                @endif
                                @if ($product->is_on_sale)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700">
                                        <i class="fas fa-tag text-[10px]"></i>
                                        On Sale
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">Metadata</h3>
                    </div>
                    <div class="p-6 space-y-3 text-xs">
                        <div>
                            <span class="text-gray-500">Product ID</span>
                            <p class="font-medium text-gray-900 mt-0.5">{{ $product->id }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Created</span>
                            <p class="font-medium text-gray-900 mt-0.5">{{ $product->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Updated</span>
                            <p class="font-medium text-gray-900 mt-0.5">{{ $product->updated_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-500">Total Views</span>
                            <p class="font-medium text-gray-900 mt-0.5">-</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Edit -->
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium">
                            <i class="fas fa-edit"></i>
                            <span>Edit Product</span>
                        </a>

                        <!-- Activate/Deactivate -->
                        @if ($product->is_active)
                            <form action="{{ route('admin.products.bulk-action') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="action" value="deactivate">
                                <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                    <i class="fas fa-eye-slash"></i>
                                    <span>Deactivate</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.products.bulk-action') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="action" value="activate">
                                <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white border border-gray-900 rounded-lg hover:bg-gray-800 transition-colors font-medium">
                                    <i class="fas fa-eye"></i>
                                    <span>Activate</span>
                                </button>
                            </form>
                        @endif

                        <!-- Delete -->
                        @if ($product->orderItems->count() === 0)
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this product?')" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition-colors font-medium">
                                    <i class="fas fa-trash"></i>
                                    <span>Delete Product</span>
                                </button>
                            </form>
                        @else
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-xs text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Cannot delete product with order history
                                </p>
                            </div>
                        @endif
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
                document.querySelectorAll('.aspect-square.cursor-pointer').forEach(thumb => {
                    thumb.classList.remove('border-gray-900');
                    thumb.classList.add('border-gray-200');
                });

                // Add active class to clicked thumbnail
                thumbnail.classList.remove('border-gray-200', 'hover:border-gray-400');
                thumbnail.classList.add('border-gray-900');
            }
        </script>
    @endpush
@endsection
