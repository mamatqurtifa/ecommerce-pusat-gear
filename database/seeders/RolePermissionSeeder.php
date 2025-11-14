<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Category permissions
            'category.index',
            'category.create',
            'category.edit',
            'category.delete',
            
            // Product permissions
            'product.index',
            'product.create',
            'product.edit',
            'product.delete',
            
            // Order permissions
            'order.index',
            'order.show',
            'order.update',
            'order.delete',
            
            // User permissions
            'user.index',
            'user.show',
            'user.edit',
            'user.delete',
            
            // Dashboard permissions
            'dashboard.admin',
            'dashboard.analytics',
            
            // Settings permissions
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin Role - All permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin Role - Most permissions except user management
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'category.index',
            'category.create',
            'category.edit',
            'category.delete',
            'product.index',
            'product.create',
            'product.edit',
            'product.delete',
            'order.index',
            'order.show',
            'order.update',
            'dashboard.admin',
            'dashboard.analytics',
        ]);

        // Staff Role - Limited permissions
        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo([
            'category.index',
            'product.index',
            'product.edit',
            'order.index',
            'order.show',
            'order.update',
            'dashboard.admin',
        ]);

        // Customer Role - Basic customer permissions
        $customer = Role::create(['name' => 'customer']);
        // No specific permissions needed for customers, 
        // they can only access their own orders and profile
    }
}