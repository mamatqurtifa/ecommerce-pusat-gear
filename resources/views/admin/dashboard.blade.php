@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Customers -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Customer</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_customers']) }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Total Produk</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_products']) }}</p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                <i class="fas fa-box text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm">Total Pesanan</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_orders']) }}</p>
            </div>
            <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Total Revenue</p>
                <p class="text-3xl font-bold">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                <i class="fas fa-money-bill-wave text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Order Status -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pesanan</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                <div class="flex items-center">
                    <div class="bg-yellow-500 rounded-full p-2 mr-3">
                        <i class="fas fa-clock text-white text-sm"></i>
                    </div>
                    <span class="font-medium text-gray-700">Pending</span>
                </div>
                <span class="text-2xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                <div class="flex items-center">
                    <div class="bg-green-500 rounded-full p-2 mr-3">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <span class="font-medium text-gray-700">Sudah Dibayar</span>
                </div>
                <span class="text-2xl font-bold text-green-600">{{ $stats['paid_orders'] }}</span>
            </div>
        </div>
    </div>

    <!-- Stock Status -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Stok</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                <div class="flex items-center">
                    <div class="bg-red-500 rounded-full p-2 mr-3">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <span class="font-medium text-gray-700">Stok Menipis</span>
                </div>
                <span class="text-2xl font-bold text-red-600">{{ $stats['low_stock_products'] }}</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <div class="bg-blue-500 rounded-full p-2 mr-3">
                        <i class="fas fa-tags text-white text-sm"></i>
                    </div>
                    <span class="font-medium text-gray-700">Total Kategori</span>
                </div>
                <span class="text-2xl font-bold text-blue-600">{{ $stats['total_categories'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders & Top Products -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Recent Orders -->
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h3>
                <a href="{{ route('admin.orders.index') }}" 
                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($recent_orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="pb-3">Order #</th>
                            <th class="pb-3">Customer</th>
                            <th class="pb-3">Total</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recent_orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="text-blue-600 hover:text-blue-700 font-medium">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="py-3 text-gray-900">{{ $order->user->name }}</td>
                            <td class="py-3 text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="py-3 text-gray-500 text-sm">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                <p>Belum ada pesanan</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Produk Terpopuler</h3>
        </div>
        <div class="p-6">
            @if($top_products->count() > 0)
            <div class="space-y-4">
                @foreach($top_products as $product)
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ Str::limit($product->name, 30) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $product->order_items_count }} terjual
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-gray-900">
                            {{ $product->order_items_count }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-chart-line text-4xl mb-4"></i>
                <p>Belum ada data penjualan</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection