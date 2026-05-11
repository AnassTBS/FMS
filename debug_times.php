<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Delivery;

echo "Current Time: " . now()->toDateTimeString() . "\n";
foreach(Delivery::all() as $d) {
    $oldStatus = $d->status;
    $d->syncStatus();
    echo "ID: {$d->id}, Dep: " . ($d->departure_date ? $d->departure_date->toDateTimeString() : 'N/A') . ", Old: {$oldStatus}, New: {$d->status}\n";
}
