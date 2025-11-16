@extends('admin.layouts.app')

@section('title', 'Users')
@section('page-title', 'Users')
@section('page-description', 'Manage user accounts, roles, and permissions to ensure secure access and proper authorization.')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <!-- Total Users -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-600 mb-1">Total Users</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['total_users'] ?? 0) }}</div>
            <div class="text-xs text-gray-500">Total registered customers</div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-600 mb-1">Active Users</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['active_users'] ?? 0) }}</div>
            <div class="text-xs text-gray-500">Users with recent activity or purchases</div>
        </div>

        <!-- New This Month -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-600 mb-1">New This Month</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['new_this_month'] ?? 0) }}</div>
            <div class="text-xs text-gray-500">Users registered this month</div>
        </div>

        <!-- Staffs -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-600 mb-1">Staffs</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['staffs'] ?? 0) }}</div>
            <div class="text-xs text-gray-500">Current staffs accounts</div>
        </div>

        <!-- Admins -->
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="text-xs text-gray-600 mb-1">Admins</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['admins'] ?? 0) }}</div>
            <div class="text-xs text-gray-500">Current admin accounts</div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="bg-white rounded-xl border border-gray-200">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3">
                <!-- Filter Button -->
                <button type="button" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-filter text-gray-600"></i>
                    <span class="text-sm font-medium text-gray-700">Filter</span>
                </button>

                <!-- Role Tabs -->
                <div class="flex gap-2">
                    <button type="submit" name="role" value="" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ !$request->role ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All
                    </button>
                    <button type="submit" name="role" value="customer" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $request->role === 'customer' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Customer
                    </button>
                    <button type="submit" name="role" value="admin" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $request->role === 'admin' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Admin
                    </button>
                    <button type="submit" name="role" value="staff" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $request->role === 'staff' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Staff
                    </button>
                </div>

                <div class="flex-1"></div>

                <!-- Add User Button -->
                <a href="{{ route('admin.users.create') }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span class="text-sm font-medium">Add User</span>
                </a>

                <!-- Export -->
                <a href="{{ route('admin.users.index', array_merge(request()->all(), ['export' => 'excel'])) }}" 
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
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900 font-medium">USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">{{ ucfirst($user->roles->first()?->name ?? 'Customer') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ $user->email_verified_at ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $user->email_verified_at ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-gray-600 hover:text-gray-900 transition-colors"
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-gray-600 hover:text-gray-900 transition-colors"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-5xl mb-4 text-gray-300"></i>
                                <h3 class="text-lg font-medium mb-2">No users found</h3>
                                <p class="text-sm text-gray-400">Try adjusting your filters or add a new user.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <select class="text-sm border-gray-300 rounded-lg focus:ring-gray-900 focus:border-gray-900">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span class="text-sm text-gray-600">
                        Showing {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }}
                    </span>
                </div>

                <div class="flex items-center gap-1">
                    @if($users->onFirstPage())
                        <button disabled class="px-3 py-2 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </a>
                    @endif

                    @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if($page == $users->currentPage())
                            <button class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium">{{ $page }}</button>
                        @elseif($page == 1 || $page == $users->lastPage() || abs($page - $users->currentPage()) <= 2)
                            <a href="{{ $url }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">{{ $page }}</a>
                        @elseif($page == 2 || $page == $users->lastPage() - 1)
                            <span class="px-2 text-gray-400">...</span>
                        @endif
                    @endforeach

                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
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