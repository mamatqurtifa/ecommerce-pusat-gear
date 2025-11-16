@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-description', 'Update user information, roles, and permissions to maintain accurate records.')

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

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Form (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Enter full name"
                                   required>
                            @error('name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-900 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('email') border-red-500 @enderror"
                                   placeholder="user@example.com"
                                   required>
                            @error('email')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-900 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('phone') border-red-500 @enderror"
                                   placeholder="081234567890"
                                   required>
                            @error('phone')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-900 mb-2">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="4"
                                      class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent resize-none @error('address') border-red-500 @enderror"
                                      placeholder="Enter complete address"
                                      required>{{ old('address', $user->address) }}</textarea>
                            @error('address')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Security Settings</h3>
                        <p class="text-sm text-gray-500 mt-1">Leave password fields empty if you don't want to change it</p>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                                New Password
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('password') border-red-500 @enderror"
                                   placeholder="Enter new password (min. 8 characters)">
                            @error('password')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">
                                Confirm New Password
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                   placeholder="Repeat new password">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar (1/3 width) -->
            <div class="space-y-6">
                <!-- Role & Status -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Role & Status</h3>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-900 mb-2">
                                User Role <span class="text-red-500">*</span>
                            </label>
                            <select id="role" 
                                    name="role" 
                                    class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('role') border-red-500 @enderror"
                                    required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" 
                                        {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                Account Status
                            </label>
                            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg {{ $user->email_verified_at ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                <i class="fas {{ $user->email_verified_at ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                                <span class="text-sm font-medium">{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Account Information</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">User ID</span>
                                <span class="font-medium text-gray-900">USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Joined</span>
                                <span class="font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Last Updated</span>
                                <span class="font-medium text-gray-900">{{ $user->updated_at->format('d M Y') }}</span>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total Orders</span>
                                    <span class="font-semibold text-gray-900">{{ $user->orders()->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium shadow-sm">
                        <i class="fas fa-save"></i>
                        <span>Save Changes</span>
                    </button>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>

                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors font-medium">
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection