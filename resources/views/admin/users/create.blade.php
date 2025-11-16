@extends('admin.layouts.app')

@section('title', 'Add User')
@section('page-title', 'Add New User')
@section('page-description', 'Create a new user account with specific roles and permissions.')

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

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

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
                                   value="{{ old('name') }}"
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
                                   value="{{ old('email') }}"
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
                                   value="{{ old('phone') }}"
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
                                      required>{{ old('address') }}</textarea>
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
                        <p class="text-sm text-gray-500 mt-1">Set a secure password for the new user</p>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('password') border-red-500 @enderror"
                                   placeholder="Enter password (min. 8 characters)"
                                   required>
                            @error('password')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                   placeholder="Repeat password"
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar (1/3 width) -->
            <div class="space-y-6">
                <!-- Role Selection -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">User Role</h3>
                    </div>
                    
                    <div class="p-6">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-900 mb-2">
                                Assign Role <span class="text-red-500">*</span>
                            </label>
                            <select id="role" 
                                    name="role" 
                                    class="w-full px-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('role') border-red-500 @enderror"
                                    required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role Info -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-xs font-semibold text-gray-900 mb-2">Role Permissions</h4>
                            <div class="text-xs text-gray-600 space-y-1">
                                <p><strong>Admin:</strong> Full access to manage system</p>
                                <p><strong>Staff:</strong> Limited access to manage orders</p>
                                <p><strong>Customer:</strong> Can place orders only</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium shadow-sm">
                        <i class="fas fa-save"></i>
                        <span>Create User</span>
                    </button>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>
                </div>

                <!-- Info Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex gap-3">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900 mb-1">Important</h4>
                            <p class="text-xs text-blue-700">The user will be automatically verified upon creation and can access the system immediately.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection