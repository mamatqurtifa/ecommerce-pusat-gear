@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview of your store performance and key metrics')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Welcome Card -->
        <div class="lg:col-span-2 bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 text-white">
            <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="text-gray-300 text-sm mb-1">
                <i class="fas fa-calendar-alt mr-2"></i>
                {{ now()->format('l, d F Y') }}
            </p>
            <p class="text-gray-300 text-sm">
                <i class="fas fa-clock mr-2"></i>
                {{ now()->format('H:i') }}
            </p>
        </div>

        <!-- Today Orders -->
        <div class="bg-gray-800 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-300 mb-1">Today Orders</p>
                    <p class="text-3xl font-bold text-white">{{ $todayStats['orders'] }}</p>
                    <p class="text-xs text-gray-300 mt-1">
                        {{ $todayStats['completed'] }} completed
                    </p>
                </div>
                <div class="rounded-full p-3">
                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <div class="rounded-lg p-2">
                    <i class="fas fa-dollar-sign text-gray-900"></i>
                </div>
                @if($changes['revenue'] != 0)
                <span class="text-xs font-medium {{ $changes['revenue'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas fa-arrow-{{ $changes['revenue'] > 0 ? 'up' : 'down' }}"></i>
                    {{ abs($changes['revenue']) }}%
                </span>
                @endif
            </div>
            <div class="text-xs text-gray-500 mb-1">Today's Revenue</div>
            <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($todayStats['revenue'], 0, ',', '.') }}</div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <div class="rounded-lg p-2">
                    <i class="fas fa-users text-gray-900"></i>
                </div>
                @if($changes['customers'] != 0)
                <span class="text-xs font-medium {{ $changes['customers'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas fa-arrow-{{ $changes['customers'] > 0 ? 'up' : 'down' }}"></i>
                    {{ abs($changes['customers']) }}%
                </span>
                @endif
            </div>
            <div class="text-xs text-gray-500 mb-1">New Customers</div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($todayStats['customers']) }}</div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <div class="rounded-lg p-2">
                    <i class="fas fa-clock text-gray-900"></i>
                </div>
            </div>
            <div class="text-xs text-gray-500 mb-1">Pending Orders</div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_orders']) }}</div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <div class="rounded-lg p-2">
                    <i class="fas fa-exclamation-triangle text-gray-900"></i>
                </div>
            </div>
            <div class="text-xs text-gray-500 mb-1">Low Stock Alert</div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['low_stock_products']) }}</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                <span class="text-xs text-gray-500">Last 7 Days</span>
            </div>
            @php
                $hasRevenueData = collect($weeklyRevenue)->sum('revenue') > 0;
            @endphp
            @if($hasRevenueData)
                <canvas id="revenueChart" height="100"></canvas>
            @else
                <div class="flex items-center justify-center h-64 text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-5xl mb-3 text-gray-300"></i>
                        <p class="text-sm">No revenue data yet</p>
                        <p class="text-xs mt-1">Start making sales to see the chart</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Status Distribution (1/3 width) -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Order Status</h3>
            @php
                $hasOrderData = collect($orderStatusData)->sum() > 0;
            @endphp
            @if($hasOrderData)
                <canvas id="orderStatusChart" height="200"></canvas>
                <div class="mt-6 space-y-2">
                    @foreach($orderStatusData as $status => $count)
                        @if($count > 0)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 capitalize">{{ $status }}</span>
                            <span class="font-semibold text-gray-900">{{ $count }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-48 text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-chart-pie text-5xl mb-3 text-gray-300"></i>
                        <p class="text-sm">No orders this month</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Sales by Category & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales by Category -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Sales by Category</h3>
            @if($topCategories->count() > 0)
                <canvas id="categoryChart" height="200"></canvas>
            @else
                <div class="flex items-center justify-center h-64 text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-chart-bar text-5xl mb-3 text-gray-300"></i>
                        <p class="text-sm">No sales data this month</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Selling Products</h3>
                <p class="text-sm text-gray-500 mt-1">This week's best performers</p>
            </div>
            <div class="p-6">
                @if($top_products->count() > 0)
                <div class="space-y-4">
                    @foreach($top_products as $index => $product)
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center font-semibold text-gray-900">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-shrink-0">
                            @if($product->images && count($product->images) > 0)
                            <img src="{{ Storage::url($product->images[0]) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-12 h-12 rounded-lg object-cover">
                            @else
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-gray-400"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ $product->total_sold }}</p>
                            <p class="text-xs text-gray-500">sold</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-chart-line text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm">No sales data yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                    <p class="text-sm text-gray-500 mt-1">Latest customer orders</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" 
                   class="text-sm font-medium text-gray-900 hover:text-gray-700">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recent_orders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" 
                   class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-gray-900"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500">{{ $order->user->name }} • {{ $order->items->count() }} items</p>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded {{ 
                            $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' 
                        }}">
                            <i class="fas fa-circle text-[6px]"></i>
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                    <p class="text-sm">No orders yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Overall Statistics (1/3 width) -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Overall Statistics</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-gray-900 text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-600">Total Orders</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($stats['total_orders']) }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-gray-900 text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-600">Total Revenue</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-gray-900 text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-600">Total Customers</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-gray-900 text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-600">Total Products</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($stats['total_products']) }}</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Quick Actions</h4>
                <div class="space-y-2">
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
                       class="flex items-center justify-between p-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        <span>View Pending Orders</span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </a>
                    <a href="{{ route('admin.products.index', ['stock_status' => 'low_stock']) }}" 
                       class="flex items-center justify-between p-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        <span>Check Low Stock</span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </a>
                    <a href="{{ route('admin.products.create') }}" 
                       class="flex items-center justify-between p-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        <span>Add New Product</span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert for Low Stock -->
    @if($stats['low_stock_products'] > 0)
    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-orange-800">
                    Low Stock Alert: {{ $stats['low_stock_products'] }} products need restocking
                </p>
                <a href="{{ route('admin.products.index', ['stock_status' => 'low_stock']) }}" 
                   class="text-sm text-orange-700 underline hover:text-orange-900 mt-1 inline-block">
                    View products →
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    @if(collect($weeklyRevenue)->sum('revenue') > 0)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($weeklyRevenue, 'date')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode(array_column($weeklyRevenue, 'revenue')) !!},
                borderColor: 'rgb(31, 41, 55)',
                backgroundColor: 'rgba(31, 41, 55, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            }
                            return 'Rp ' + value;
                        }
                    },
                    grid: { display: true, drawBorder: false }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    @endif

    // Order Status Chart
    @if(collect($orderStatusData)->sum() > 0)
    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const orderStatusData = @json($orderStatusData);
    const filteredStatuses = Object.keys(orderStatusData).filter(key => orderStatusData[key] > 0);
    const filteredData = filteredStatuses.map(key => orderStatusData[key]);
    
    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: filteredStatuses.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            datasets: [{
                data: filteredData,
                backgroundColor: [
                    '#fbbf24', // pending
                    '#34d399', // confirmed
                    '#60a5fa', // processing
                    '#a78bfa', // shipped
                    '#10b981', // delivered
                    '#f87171'  // cancelled
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
    @endif

    // Category Chart
    @if($topCategories->count() > 0)
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryLabels = {!! json_encode($topCategories->pluck('name')) !!};
    const categoryData = {!! json_encode($topCategories->pluck('total_revenue')) !!};
    
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Revenue',
                data: categoryData,
                backgroundColor: 'rgba(31, 41, 55, 0.8)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            }
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush
@endsection