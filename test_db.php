<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$products = App\Models\Product::orderBy('id', 'desc')->take(3)->get();
foreach ($products as $p) {
    echo "ID: " . $p->id . PHP_EOL;
    echo "RAW_FOTO: " . $p->getRawOriginal('foto') . PHP_EOL;
    echo "FOTOS_ARRAY: " . json_encode($p->fotos) . PHP_EOL;
    echo "FOTO_SINGLE: " . $p->foto . PHP_EOL;
    echo "--------------------------" . PHP_EOL;
}
