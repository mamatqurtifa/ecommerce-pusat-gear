@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-description', 'Manage and track customer orders, payments, and shipping status.')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Orders -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Total Orders</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['total_orders']) }}</div>
            <div class="text-xs text-gray-500">All time orders</div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Pending Orders</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['pending_orders']) }}</div>
            <div class="text-xs text-gray-500">Awaiting confirmation</div>
        </div>

        <!-- Delivered Orders -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Delivered</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['delivered_orders']) }}</div>
            <div class="text-xs text-gray-500">Successfully delivered</div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Total Revenue</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <div class="text-xs text-gray-500">From paid orders</div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl border border-gray-200">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <input type="text" 
                               name="search" 
                               value="{{ $request->search }}"
                               placeholder="Search order number or customer..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>

                    <!-- Status Filter -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button"
                                @click="open = !open"
                                class="w-full inline-flex items-center justify-between gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors bg-white">
                            <span class="text-sm text-gray-700">
                                @if($request->status)
                                    {{ ucfirst($request->status) }}
                                @else
                                    All Status
                                @endif
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>
                        
                        <div x-show="open"
                             @click.away="open = false"
                             class="absolute left-0 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10"
                             style="display: none;">
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => ''])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">All Status</a>
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pending</a>
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'confirmed'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Confirmed</a>
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'shipped'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Shipped</a>
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'delivered'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Delivered</a>
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'cancelled'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelled</a>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button"
                                @click="open = !open"
                                class="w-full inline-flex items-center justify-between gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors bg-white">
                            <span class="text-sm text-gray-700">
                                @if($request->payment_status)
                                    {{ ucfirst($request->payment_status) }}
                                @else
                                    All Payment
                                @endif
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>
                        
                        <div x-show="open"
                             @click.away="open = false"
                             class="absolute left-0 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10"
                             style="display: none;">
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('payment_status'), ['payment_status' => ''])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">All Payment</a>
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('payment_status'), ['payment_status' => 'pending'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pending</a>
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('payment_status'), ['payment_status' => 'paid'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Paid</a>
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('payment_status'), ['payment_status' => 'failed'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Failed</a>
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('payment_status'), ['payment_status' => 'refunded'])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Refunded</a>
                        </div>
                    </div>

                    <!-- Date From -->
                    <div>
                        <input type="date" 
                               name="date_from" 
                               value="{{ $request->date_from }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>

                    <!-- Date To -->
                    <div>
                        <input type="date" 
                               name="date_to" 
                               value="{{ $request->date_to }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Search Button -->
                    <button type="submit" 
                            class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Reset Button -->
                    @if($request->search || $request->status || $request->payment_status || $request->date_from || $request->date_to)
                    <a href="{{ route('admin.orders.index') }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif

                    <div class="flex-1"></div>

                    <!-- Export Button -->
                    <a href="{{ route('admin.orders.index', array_merge(request()->all(), ['export' => 'excel'])) }}" 
                       class="p-2 hover:bg-gray-100 rounded-lg transition-colors" 
                       title="Export to Excel">
                        <i class="fas fa-download text-gray-600"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Bulk Actions Bar (Hidden by default) -->
        <div id="bulk-actions" 
             class="px-6 py-4 bg-blue-50 border-b border-blue-200 hidden">
            <form id="bulk-form" method="POST" action="{{ route('admin.orders.bulk-action') }}" class="flex items-center gap-4">
                @csrf
                <span class="text-sm font-medium text-blue-900">
                    <span id="selected-count">0</span> orders selected
                </span>
                
                <select name="action" 
                        class="px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-600 bg-white text-sm">
                    <option value="">Select Action</option>
                    <option value="confirm">Confirm Orders</option>
                    <option value="ship">Mark as Shipped</option>
                    <option value="deliver">Mark as Delivered</option>
                    <option value="cancel">Cancel Orders</option>
                </select>
                
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                        onclick="return confirm('Are you sure you want to perform this action?')">
                    Apply
                </button>
                
                <button type="button" 
                        onclick="clearSelection()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Clear Selection
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" 
                                   id="select-all" 
                                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" 
                                   name="order_ids[]" 
                                   value="{{ $order->id }}"
                                   class="order-checkbox rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                                {{ $order->order_number }}
                            </a>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $order->items->count() }} item(s)
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-600 space-y-0.5">
                                @foreach($order->items->take(2) as $item)
                                <div>{{ $item->product_name }} ({{ $item->quantity }}x)</div>
                                @endforeach
                                @if($order->items->count() > 2)
                                <div class="text-gray-400">+{{ $order->items->count() - 2 }} more</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-lg {{ 
                                $order->status === 'delivered' ? 'bg-green-100 text-green-700' : 
                                ($order->status === 'shipped' ? 'bg-blue-100 text-blue-700' : 
                                ($order->status === 'confirmed' ? 'bg-indigo-100 text-indigo-700' : 
                                ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                                ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')))) 
                            }}">
                                <i class="fas fa-circle text-[6px]"></i>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-lg {{ 
                                $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 
                                ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') 
                            }}">
                                <i class="fas fa-circle text-[6px]"></i>
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $order->created_at->format('d M Y') }}
                            <div class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="text-gray-600 hover:text-gray-900 transition-colors"
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order) }}" 
                                   class="text-gray-600 hover:text-gray-900 transition-colors"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.orders.print', $order) }}" 
                                   class="text-gray-600 hover:text-gray-900 transition-colors"
                                   title="Print" 
                                   target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-shopping-cart text-5xl mb-4 text-gray-300"></i>
                                <h3 class="text-lg font-medium mb-2">No orders found</h3>
                                <p class="text-sm text-gray-400">
                                    @if(request()->hasAny(['search', 'status', 'payment_status', 'date_from', 'date_to']))
                                        Try adjusting your filters or search query.
                                    @else
                                        Orders will appear here once customers start placing them.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Showing {{ $orders->firstItem() }}-{{ $orders->lastItem() }} of {{ $orders->total() }}
                </div>

                <div class="flex items-center gap-1">
                    @if($orders->onFirstPage())
                        <button disabled class="px-3 py-2 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </a>
                    @endif

                    @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        @if($page == $orders->currentPage())
                            <button class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium">{{ $page }}</button>
                        @elseif($page == 1 || $page == $orders->lastPage() || abs($page - $orders->currentPage()) <= 2)
                            <a href="{{ $url }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">{{ $page }}</a>
                        @elseif($page == 2 || $page == $orders->lastPage() - 1)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                    @endforeach

                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    // Select all functionality
    selectAll.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox functionality
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        
        if (checkedBoxes.length > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = checkedBoxes.length;
            
            // Add hidden inputs for selected orders
            const existingInputs = bulkForm.querySelectorAll('input[name="order_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order_ids[]';
                input.value = checkbox.value;
                bulkForm.appendChild(input);
            });
        } else {
            bulkActions.classList.add('hidden');
        }
        
        // Update select all checkbox
        selectAll.checked = checkedBoxes.length === orderCheckboxes.length;
        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < orderCheckboxes.length;
    }

    window.clearSelection = function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAll.checked = false;
        bulkActions.classList.add('hidden');
    };
});
</script>
@endpush
@endsection