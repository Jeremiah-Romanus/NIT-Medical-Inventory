<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$procZero = DB::table('medicines')->where('quantity', 0)->count();
$pharmZero = DB::table('medicines')->where('pharmacy_quantity', 0)->count();

echo "Medicines with procurement quantity = 0: $procZero\n";
echo "Medicines with pharmacy_quantity = 0: $pharmZero\n";
