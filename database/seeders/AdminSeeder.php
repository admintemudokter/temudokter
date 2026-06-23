<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@konsulku.id'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@konsulku.id',
                'password' => Hash::make('password'),
            ]
        );
    }
}
