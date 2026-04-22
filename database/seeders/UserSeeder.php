<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a Pharmacist user
        User::firstOrCreate(
            ['email' => 'pharmacist@nit.com'],
            [
                'name' => 'Dr. James Kipchoge',
                'password' => bcrypt('password123'),
                'role' => 'pharmacist',
            ]
        );

        // Create a Procurement Officer user
        User::firstOrCreate(
            ['email' => 'procurement@nit.com'],
            [
                'name' => 'John Mwangi',
                'password' => bcrypt('password123'),
                'role' => 'procurement',
            ]
        );
    }
}
