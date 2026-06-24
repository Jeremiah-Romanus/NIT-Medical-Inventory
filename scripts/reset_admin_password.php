<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$admin = User::where('role', 'admin')->first();
if (! $admin) {
    echo "No admin user found.\n";
    exit(1);
}

$admin->password = Hash::make('Jeremiah@123');
$admin->save();

echo "Admin password reset for {$admin->email}\n";
echo "New password: Jeremiah@123\n";
