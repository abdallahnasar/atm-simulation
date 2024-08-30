<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('test1234'),
        ]);
    }
}

