<?php

// One-off script to copy procurement quantity -> pharmacy_quantity when pharmacy_quantity is 0 or NULL
// Run: php scripts/sync_pharmacy_quantity.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap the application kernel so facades work
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Starting sync: setting pharmacy_quantity = quantity where pharmacy_quantity IS NULL or = 0\n";

$updated = DB::table('medicines')
    ->where(function ($q) {
        $q->whereNull('pharmacy_quantity')->orWhere('pharmacy_quantity', 0);
    })
    ->update(['pharmacy_quantity' => DB::raw('quantity')]);

echo "Updated rows: " . ($updated === null ? '0' : $updated) . "\n";

// Show a sample of first 10 medicines
$rows = DB::table('medicines')
    ->select('id','medical_id','name','quantity','pharmacy_quantity')
    ->orderBy('id')
    ->limit(10)
    ->get();

echo "Sample rows after update:\n";
foreach ($rows as $r) {
    echo sprintf("%s: %s qty=%s pharmacy=%s\n", $r->medical_id, $r->name, $r->quantity, $r->pharmacy_quantity);
}

echo "Done.\n";
