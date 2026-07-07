<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Admin User',
                'email' => 'kermit@gmail.com',
                'password' => 'admin123',
                'role' => 'Admin',
                'status' => 'Active',
                'last_active_at' => now(),
            ],
            [
                'name' => 'Cashier Lead',
                'email' => 'cashier@gmail.com',
                'password' => 'cashier123',
                'role' => 'Cashier',
                'status' => 'Active',
                'last_active_at' => now()->subDay(),
            ],
            [
                'name' => 'Delivery Rider',
                'email' => 'rider@gmail.com',
                'password' => 'rider123',
                'role' => 'Rider',
                'status' => 'Active',
                'last_active_at' => now()->subHours(2),
            ],
            [
                'name' => 'Kitchen Lead',
                'email' => 'kitchen@kermits.local',
                'password' => 'staff123',
                'role' => 'Staff',
                'status' => 'Inactive',
                'last_active_at' => now()->subDays(10),
            ],
        ];

        foreach ($accounts as $account) {
            DB::table('staff_accounts')->updateOrInsert(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make($account['password']),
                    'role' => $account['role'],
                    'status' => $account['status'],
                    'last_active_at' => $account['last_active_at'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
};
