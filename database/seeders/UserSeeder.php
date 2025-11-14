<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@pusatgear.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia'
        ]);
        $superAdmin->assignRole('super-admin');

        // Create Admin
        $admin = User::create([
            'name' => 'Admin Pusat Gear',
            'email' => 'admin@pusatgear.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '081234567891',
            'address' => 'Jakarta, Indonesia'
        ]);
        $admin->assignRole('admin');

        // Create Staff
        $staff = User::create([
            'name' => 'Staff Pusat Gear',
            'email' => 'staff@pusatgear.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '081234567892',
            'address' => 'Jakarta, Indonesia'
        ]);
        $staff->assignRole('staff');

        // Create Test Customer
        $customer = User::create([
            'name' => 'Customer Test',
            'email' => 'customer@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '081234567893',
            'address' => 'Bandung, Indonesia'
        ]);
        $customer->assignRole('customer');

        // Create your own account
        $mamat = User::create([
            'name' => 'Mamat Qurtifa',
            'email' => 'mamat@pusatgear.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '081234567894',
            'address' => 'Jakarta, Indonesia'
        ]);
        $mamat->assignRole('super-admin');
    }
}