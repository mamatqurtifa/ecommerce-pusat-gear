@extends('admin.layouts.app')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna: ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- User Info Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm transition duration-150">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition duration-150">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Picture & Basic Info -->
                <div class="lg:col-span-1">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-3xl text-gray-600"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $user->name }}</h4>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ 
                            $user->hasRole('super-admin') ? 'bg-purple-100 text-purple-800' : 
                            ($user->hasRole('admin') ? 'bg-blue-100 text-blue-800' : 
                            ($user->hasRole('staff') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) 
                        }}">
                            {{ ucfirst($user->roles->first()?->name ?? 'No Role') }}
                        </span>
                    </div>
                </div>
                
                <!-- Detailed Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contact Information -->
                    <div>
                        <h5 class="text-md font-semibold text-gray-800 mb-4">Informasi Kontak</h5>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope w-5 text-gray-400 mr-3"></i>
                                <span class="text-gray-900">{{ $user->email }}</span>
                                @if($user->email_verified_at)
                                <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Verified
                                </span>
                                @else
                                <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation mr-1"></i>Unverified
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone w-5 text-gray-400 mr-3"></i>
                                <span class="text-gray-900">{{ $user->phone }}</span>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt w-5 text-gray-400 mr-3 mt-1"></i>
                                <span class="text-gray-900">{{ $user->address }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div>
                        <h5 class="text-md font-semibold text-gray-800 mb-4">Informasi Akun</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Bergabung</p>
                                <p class="font-medium text-gray-900">{{ $user->created_at->format('d F Y, H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Terakhir Update</p>
                                <p class="font-medium text-gray-900">{{ $user->updated_at->format('d F Y, H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-blue-50 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total Pesanan</p>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_orders']) }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-green-50 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Total Belanja</p>
                    <p class="text-3xl font-bold text-green-600">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Pesanan Pending</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ number_format($stats['pending_orders']) }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">Pesanan Selesai</p>
                    <p class="text-3xl font-bold text-purple-600">{{ number_format($stats['completed_orders']) }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h3>
                <a href="{{ route('admin.orders.index', ['user' => $user->id]) }}" 
                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Lihat Semua Pesanan
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if($user->orders->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order #
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pembayaran
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($user->orders->take(10) as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-blue-600 hover:text-blue-700 font-medium">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-900 font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ 
                                $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) 
                            }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ 
                                $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') 
                            }}">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-blue-600 hover:text-blue-700" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-8">
                <div class="text-gray-500">
                    <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                    <p class="text-lg font-medium mb-2">Belum ada pesanan</p>
                    <p class="text-gray-400">User ini belum pernah melakukan pemesanan.</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-150">
                <i class="fas fa-edit mr-2"></i>Edit Pengguna
            </a>
            
            @if(!$user->email_verified_at)
            <button onclick="verifyEmail({{ $user->id }})" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-150">
                <i class="fas fa-check mr-2"></i>Verifikasi Email
            </button>
            @endif
            
            @if($user->id !== auth()->id() && $user->orders()->count() === 0)
            <form action="{{ route('admin.users.destroy', $user) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition duration-150">
                    <i class="fas fa-trash mr-2"></i>Hapus Pengguna
                </button>
            </form>
            @elseif($user->id !== auth()->id())
            <button disabled 
                    class="bg-gray-400 text-white px-4 py-2 rounded-lg font-medium cursor-not-allowed"
                    title="User memiliki riwayat pesanan">
                <i class="fas fa-trash mr-2"></i>Tidak Dapat Dihapus
            </button>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function verifyEmail(userId) {
    if (confirm('Apakah Anda yakin ingin memverifikasi email user ini?')) {
        // AJAX call untuk verify email - akan dibuat nanti
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
                alert('Gagal memverifikasi email');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}
</script>
@endpush
@endsection