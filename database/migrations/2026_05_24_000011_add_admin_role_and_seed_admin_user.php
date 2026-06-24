<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'pharmacist', 'procurement') NOT NULL DEFAULT 'pharmacist'");
        }

        if (DB::getDriverName() !== 'sqlite') {
            DB::table('users')->updateOrInsert(
                ['email' => 'admin@nitinventory.local'],
                [
                    'name' => 'JEREMIAH ROMANUS',
                    'phone' => '+255700000001',
                    'role' => 'admin',
                    'password' => Hash::make('Jeremiah@123'),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::table('users')
                ->where('email', 'admin@nitinventory.local')
                ->where('role', 'admin')
                ->delete();
        }

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('pharmacist', 'procurement') NOT NULL DEFAULT 'pharmacist'");
        }
    }
};
