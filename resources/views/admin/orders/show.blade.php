@extends('admin.layouts.app')

@section('title', 'Order Details')
@section('page-title', 'Order Details: ' . $order->order_number)
@section('page-description', 'View comprehensive order information, items, shipping, and payment details.')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.orders.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm font-medium">Back to Orders</span>
        </a>
    </div>

    <!-- Order Header Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $order->order_number }}</h3>
                    <p class="text-sm text-gray-600">Created on {{ $order->created_at->format('d F Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.orders.edit', $order) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-edit"></i>
                        <span class="text-sm font-medium">Edit Status</span>
                    </a>
                    <a href="{{ route('admin.orders.print', $order) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" 
                       target="_blank">
                        <i class="fas fa-print"></i>
                        <span class="text-sm font-medium">Print Invoice</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Order Details (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if($item->product && $item->product->images && count($item->product->images) > 0)
                                        <img src="{{ Storage::url($item->product->images[0]) }}" 
                                             alt="{{ $item->product_name }}"
                                             class="h-12 w-12 rounded-lg object-cover border border-gray-200">
                                        @else
                                        <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                            @if($item->product)
                                            <a href="{{ route('admin.products.show', $item->product) }}" 
                                               class="text-xs text-gray-600 hover:text-gray-900">View Product</a>
                                            @else
                                            <span class="text-xs text-red-600">Product deleted</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->product_sku }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Order Summary -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="space-y-2 max-w-sm ml-auto">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($order->tax_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax:</span>
                            <span class="text-gray-900">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($order->shipping_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="text-gray-900">Rp {{ number_format($order->shipping_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discount:</span>
                            <span class="text-red-600">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="border-t border-gray-200 pt-2">
                            <div class="flex justify-between">
                                <span class="text-base font-medium text-gray-900">Total:</span>
                                <span class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Shipping Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Shipping Address -->
                        <div>
                            <h5 class="text-sm font-semibold text-gray-900 mb-3">Shipping Address</h5>
                            @if($order->shipping_address)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-sm font-medium text-gray-900">{{ $order->shipping_address['name'] ?? $order->user->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $order->shipping_address['phone'] ?? $order->user->phone }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $order->shipping_address['address'] ?? $order->user->address }}</p>
                                @if(isset($order->shipping_address['city']))
                                <p class="text-sm text-gray-600">{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Billing Address -->
                        <div>
                            <h5 class="text-sm font-semibold text-gray-900 mb-3">Billing Address</h5>
                            @if($order->billing_address)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-sm font-medium text-gray-900">{{ $order->billing_address['name'] ?? $order->user->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $order->billing_address['phone'] ?? $order->user->phone }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $order->billing_address['address'] ?? $order->user->address }}</p>
                                @if(isset($order->billing_address['city']))
                                <p class="text-sm text-gray-600">{{ $order->billing_address['city'] }}, {{ $order->billing_address['postal_code'] ?? '' }}</p>
                                @endif
                            </div>
                            @else
                            <p class="text-sm text-gray-500 italic">Same as shipping address</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar (1/3 width) -->
        <div class="space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Order Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Order Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Order Status</label>
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ 
                            $order->status === 'delivered' ? 'bg-green-100 text-green-700' : 
                            ($order->status === 'shipped' ? 'bg-blue-100 text-blue-700' : 
                            ($order->status === 'confirmed' ? 'bg-purple-100 text-purple-700' : 
                            ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                            ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')))) 
                        }}">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Payment Status</label>
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ 
                            $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 
                            ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') 
                        }}">
                            {{ $order->payment_status_label }}
                        </span>
                    </div>

                    <!-- Payment Method -->
                    @if($order->payment_method)
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Payment Method</label>
                        <p class="text-sm text-gray-900">{{ $order->payment_method }}</p>
                    </div>
                    @endif

                    <!-- Payment Reference -->
                    @if($order->payment_reference)
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Payment Reference</label>
                        <p class="text-sm text-gray-900 font-mono">{{ $order->payment_reference }}</p>
                    </div>
                    @endif

                    <!-- Tracking Dates -->
                    @if($order->shipped_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Shipped Date</label>
                        <p class="text-sm text-gray-900">{{ $order->shipped_at->format('d F Y, H:i') }}</p>
                    </div>
                    @endif

                    @if($order->delivered_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Delivered Date</label>
                        <p class="text-sm text-gray-900">{{ $order->delivered_at->format('d F Y, H:i') }}</p>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($order->notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Notes</label>
                        <p class="text-sm text-gray-900">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900">{{ $order->user->name }}</h5>
                            <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-phone w-4 mr-2"></i>
                            {{ $order->user->phone }}
                        </div>
                        <div class="flex items-start text-gray-600">
                            <i class="fas fa-map-marker-alt w-4 mr-2 mt-0.5"></i>
                            <span>{{ $order->user->address }}</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.users.show', $order->user) }}" 
                           class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            <i class="fas fa-external-link-alt mr-1"></i>View Customer Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            @if($order->payment)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment ID:</span>
                        <span class="font-mono text-gray-900">{{ $order->payment->payment_reference }}</span>
                    </div>
                    
                    @if($order->payment->duitku_reference)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duitku Reference:</span>
                        <span class="font-mono text-gray-900">{{ $order->payment->duitku_reference }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium text-gray-900">{{ $order->payment->status_label }}</span>
                    </div>
                    
                    @if($order->payment->paid_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paid at:</span>
                        <span class="text-gray-900">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif

                    @if($order->payment->expired_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Expires at:</span>
                        <span class="text-gray-900">{{ $order->payment->expired_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($order->status === 'pending' && $order->payment_status === 'pending')
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="confirmed">
                        <input type="hidden" name="payment_status" value="paid">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium"
                                onclick="return confirm('Confirm order and payment?')">
                            <i class="fas fa-check"></i>
                            <span>Confirm & Pay</span>
                        </button>
                    </form>
                    @endif

                    @if($order->status === 'confirmed')
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="shipped">
                        <input type="hidden" name="payment_status" value="{{ $order->payment_status }}">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium"
                                onclick="return confirm('Mark order as shipped?')">
                            <i class="fas fa-shipping-fast"></i>
                            <span>Mark as Shipped</span>
                        </button>
                    </form>
                    @endif

                    @if($order->status === 'shipped')
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="delivered">
                        <input type="hidden" name="payment_status" value="{{ $order->payment_status }}">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium"
                                onclick="return confirm('Mark order as delivered?')">
                            <i class="fas fa-check-circle"></i>
                            <span>Mark as Delivered</span>
                        </button>
                    </form>
                    @endif

                    @if($order->can_be_cancelled)
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <input type="hidden" name="payment_status" value="{{ $order->payment_status }}">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition-colors font-medium"
                                onclick="return confirm('Cancel this order?')">
                            <i class="fas fa-times"></i>
                            <span>Cancel Order</span>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.orders.edit', $order) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-edit"></i>
                        <span>Edit Details</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection