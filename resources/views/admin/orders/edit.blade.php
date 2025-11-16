@extends('admin.layouts.app')

@section('title', 'Edit Order')
@section('page-title', 'Edit Order: ' . $order->order_number)
@section('page-description', 'Update order status, payment information, and tracking details.')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.orders.show', $order) }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm font-medium">Back to Order Details</span>
        </a>
    </div>

    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column - Order Information -->
            <div class="space-y-6">
                <!-- Basic Order Info -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Order Information</h3>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- Order Number (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                Order Number
                            </label>
                            <input type="text" 
                                   value="{{ $order->order_number }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed"
                                   readonly>
                        </div>

                        <!-- Customer (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                Customer
                            </label>
                            <div class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg bg-gray-50">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $order->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Amount (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                Total Amount
                            </label>
                            <input type="text" 
                                   value="Rp {{ number_format($order->total_amount, 0, ',', '.') }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed"
                                   readonly>
                        </div>

                        <!-- Created Date (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                Created Date
                            </label>
                            <input type="text" 
                                   value="{{ $order->created_at->format('d F Y, H:i') }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed"
                                   readonly>
                        </div>
                    </div>
                </div>

                <!-- Order Items (Read Only) -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
                    </div>
                    
                    <div class="p-6 space-y-3">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            @if($item->product && $item->product->images && count($item->product->images) > 0)
                            <img src="{{ Storage::url($item->product->images[0]) }}" 
                                 alt="{{ $item->product_name }}"
                                 class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                            @else
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                            @endif
                            <div class="flex-1">
                                <h6 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h6>
                                <p class="text-xs text-gray-600">SKU: {{ $item->product_sku }}</p>
                                <p class="text-xs text-gray-600">
                                    {{ $item->quantity }}x Rp {{ number_format($item->product_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column - Status and Actions -->
            <div class="space-y-6">
                <!-- Order Status -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Order Status</h3>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- Order Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-900 mb-2">
                                Order Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status" 
                                    class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('status') border-red-500 @enderror"
                                    required>
                                <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="confirmed" {{ old('status', $order->status) === 'confirmed' ? 'selected' : '' }}>
                                    Confirmed
                                </option>
                                <option value="processing" {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}>
                                    Processing
                                </option>
                                <option value="shipped" {{ old('status', $order->status) === 'shipped' ? 'selected' : '' }}>
                                    Shipped
                                </option>
                                <option value="delivered" {{ old('status', $order->status) === 'delivered' ? 'selected' : '' }}>
                                    Delivered
                                </option>
                                <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>
                            @error('status')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500">
                                Current: <strong>{{ $order->status_label }}</strong>
                            </p>
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-900 mb-2">
                                Payment Status <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_status" 
                                    name="payment_status" 
                                    class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_status') border-red-500 @enderror"
                                    required>
                                <option value="pending" {{ old('payment_status', $order->payment_status) === 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="paid" {{ old('payment_status', $order->payment_status) === 'paid' ? 'selected' : '' }}>
                                    Paid
                                </option>
                                <option value="failed" {{ old('payment_status', $order->payment_status) === 'failed' ? 'selected' : '' }}>
                                    Failed
                                </option>
                                <option value="refunded" {{ old('payment_status', $order->payment_status) === 'refunded' ? 'selected' : '' }}>
                                    Refunded
                                </option>
                            </select>
                            @error('payment_status')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500">
                                Current: <strong>{{ $order->payment_status_label }}</strong>
                            </p>
                        </div>

                        <!-- Tracking Number -->
                        <div>
                            <label for="tracking_number" class="block text-sm font-medium text-gray-900 mb-2">
                                Tracking Number
                            </label>
                            <input type="text" 
                                   id="tracking_number" 
                                   name="tracking_number" 
                                   value="{{ old('tracking_number', $order->tracking_number ?? '') }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('tracking_number') border-red-500 @enderror"
                                   placeholder="Enter shipping tracking number">
                            @error('tracking_number')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500">
                                Optional. Fill when order is shipped.
                            </p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-900 mb-2">
                                Admin Notes
                            </label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="4"
                                      class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent resize-none @error('notes') border-red-500 @enderror"
                                      placeholder="Add notes for this order...">{{ old('notes', $order->notes) }}</textarea>
                            @error('notes')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Status Timeline</h3>
                    </div>
                    
                    <div class="p-6 space-y-3">
                        <!-- Created -->
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-plus text-blue-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Order Created</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="w-3 h-3 bg-blue-500 rounded-full flex-shrink-0"></div>
                        </div>

                        <!-- Confirmed -->
                        @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']))
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Order Confirmed</p>
                                <p class="text-xs text-gray-500">Status: Confirmed</p>
                            </div>
                            <div class="w-3 h-3 bg-green-500 rounded-full flex-shrink-0"></div>
                        </div>
                        @else
                        <div class="flex items-center gap-3 opacity-50">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-gray-400 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-500">Awaiting Confirmation</p>
                            </div>
                            <div class="w-3 h-3 bg-gray-300 rounded-full flex-shrink-0"></div>
                        </div>
                        @endif

                        <!-- Shipped -->
                        @if(in_array($order->status, ['shipped', 'delivered']) && $order->shipped_at)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shipping-fast text-blue-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Order Shipped</p>
                                <p class="text-xs text-gray-500">{{ $order->shipped_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="w-3 h-3 bg-blue-500 rounded-full flex-shrink-0"></div>
                        </div>
                        @elseif($order->status === 'shipped')
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shipping-fast text-blue-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Order Shipped</p>
                                <p class="text-xs text-gray-500">Status: Shipped</p>
                            </div>
                            <div class="w-3 h-3 bg-blue-500 rounded-full flex-shrink-0"></div>
                        </div>
                        @else
                        <div class="flex items-center gap-3 opacity-50">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shipping-fast text-gray-400 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-500">Awaiting Shipment</p>
                            </div>
                            <div class="w-3 h-3 bg-gray-300 rounded-full flex-shrink-0"></div>
                        </div>
                        @endif

                        <!-- Delivered -->
                        @if($order->status === 'delivered' && $order->delivered_at)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Order Completed</p>
                                <p class="text-xs text-gray-500">{{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="w-3 h-3 bg-green-500 rounded-full flex-shrink-0"></div>
                        </div>
                        @elseif($order->status === 'delivered')
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Order Completed</p>
                                <p class="text-xs text-gray-500">Status: Delivered</p>
                            </div>
                            <div class="w-3 h-3 bg-green-500 rounded-full flex-shrink-0"></div>
                        </div>
                        @else
                        <div class="flex items-center gap-3 opacity-50">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check-circle text-gray-400 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-500">Awaiting Completion</p>
                            </div>
                            <div class="w-3 h-3 bg-gray-300 rounded-full flex-shrink-0"></div>
                        </div>
                        @endif

                        <!-- Cancelled -->
                        @if($order->status === 'cancelled')
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-times text-red-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-red-900">Order Cancelled</p>
                                <p class="text-xs text-red-500">Status: Cancelled</p>
                            </div>
                            <div class="w-3 h-3 bg-red-500 rounded-full flex-shrink-0"></div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Warning Messages -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex gap-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <h3 class="text-sm font-semibold text-yellow-900 mb-2">Important Notes</h3>
                            <div class="text-sm text-yellow-800 space-y-1">
                                <p>• Changing status to "Cancelled" will restore product stock.</p>
                                <p>• "Delivered" status marks the order as fully completed.</p>
                                <p>• Status changes will send email notifications to customer.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium shadow-sm">
                        <i class="fas fa-save"></i>
                        <span>Update Order</span>
                    </button>
                    
                    <a href="{{ route('admin.orders.show', $order) }}" 
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
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const paymentStatusSelect = document.getElementById('payment_status');
    const trackingNumberInput = document.getElementById('tracking_number');
    
    function handleStatusChange() {
        const status = statusSelect.value;
        const paymentStatus = paymentStatusSelect.value;
        
        // Show/hide tracking number based on status
        if (status === 'shipped' || status === 'delivered') {
            trackingNumberInput.parentElement.style.display = 'block';
        } else {
            trackingNumberInput.parentElement.style.display = 'none';
        }
        
        // Auto-adjust payment status based on order status
        if (status === 'confirmed' && paymentStatus === 'pending') {
            // Suggest to mark payment as paid when confirming order
            if (confirm('Do you want to mark payment as "Paid" as well?')) {
                paymentStatusSelect.value = 'paid';
            }
        }
        
        if (status === 'cancelled' && paymentStatus === 'paid') {
            // Suggest refund when cancelling paid order
            if (confirm('This order is already paid. Do you want to mark payment as "Refunded"?')) {
                paymentStatusSelect.value = 'refunded';
            }
        }
    }
    
    statusSelect.addEventListener('change', handleStatusChange);
    
    // Initialize on page load
    handleStatusChange();
});
</script>
@endpush
@endsection