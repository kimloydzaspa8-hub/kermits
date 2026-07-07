<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('admin123');

        DB::table('admin')->updateOrInsert(
            ['email' => 'kermits@gmail.com'],
            [
                'name' => 'Default Admin',
                'password' => $password,
                'role' => 'admin',
                'is_active' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        DB::table('staff_accounts')->updateOrInsert(
            ['email' => 'kermits@gmail.com'],
            [
                'name' => 'Default Admin',
                'password' => $password,
                'role' => 'Admin',
                'status' => 'Active',
                'last_active_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
