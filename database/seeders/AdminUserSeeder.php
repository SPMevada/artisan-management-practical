<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $create_admin_data = [
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin@123'),
            'phone' => '9988776655',
            'subject' => '',
            'bio' => '',
            'role' => 'admin',
        ];

        User::create($create_admin_data);
    }
}
