@extends('admin.layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details: ' . $user->name)
@section('page-description', 'View comprehensive user information, activity history, and manage account settings.')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm font-medium">Back to Users</span>
        </a>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-gray-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-edit"></i>
                        <span class="text-sm font-medium">Edit User</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Section -->
                <div class="lg:col-span-1">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-3xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $user->name }}</h4>
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ 
                            $user->hasRole('super-admin') ? 'bg-purple-100 text-purple-700' : 
                            ($user->hasRole('admin') ? 'bg-blue-100 text-blue-700' : 
                            ($user->hasRole('staff') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700')) 
                        }}">
                            {{ ucfirst($user->roles->first()?->name ?? 'Customer') }}
                        </span>
                    </div>
                </div>
                
                <!-- Contact & Account Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contact Information -->
                    <div>
                        <h5 class="text-sm font-semibold text-gray-900 mb-4">Contact Information</h5>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope w-4 text-gray-400"></i>
                                <span class="text-sm text-gray-900">{{ $user->email }}</span>
                                @if($user->email_verified_at)
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                    <i class="fas fa-check"></i>Verified
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                    <i class="fas fa-exclamation"></i>Unverified
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone w-4 text-gray-400"></i>
                                <span class="text-sm text-gray-900">{{ $user->phone }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt w-4 text-gray-400 mt-0.5"></i>
                                <span class="text-sm text-gray-900">{{ $user->address }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div>
                        <h5 class="text-sm font-semibold text-gray-900 mb-4">Account Information</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">User ID</p>
                                <p class="text-sm font-medium text-gray-900">USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Total Orders</p>
                                <p class="text-sm font-medium text-gray-900">{{ number_format($user->orders()->count()) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Joined</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Last Updated</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->updated_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-600 mb-1">Total Orders</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_orders']) }}</div>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-600 mb-1">Total Spent</div>
                    <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</div>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-600 mb-1">Pending Orders</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_orders']) }}</div>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-600 mb-1">Completed Orders</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['completed_orders']) }}</div>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <a href="{{ route('admin.orders.index', ['user' => $user->id]) }}" 
                   class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    View All Orders →
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if($user->orders->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($user->orders->take(10) as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-sm font-medium text-gray-900 hover:text-gray-700">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ 
                                $order->status === 'delivered' ? 'bg-green-100 text-green-700' : 
                                ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                                ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) 
                            }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ 
                                $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 
                                ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') 
                            }}">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-gray-600 hover:text-gray-900 transition-colors"
                               title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-500">
                    <i class="fas fa-shopping-cart text-5xl mb-4 text-gray-300"></i>
                    <h3 class="text-lg font-medium mb-2">No orders yet</h3>
                    <p class="text-sm text-gray-400">This user hasn't placed any orders.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function verifyEmail(userId) {
    if (confirm('Are you sure you want to verify this user\'s email?')) {
        fetch(`/admin/api/users/${userId}/verify-email`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to verify email');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}
</script>
@endpush
@endsection