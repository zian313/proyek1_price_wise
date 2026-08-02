<?php
// Test submitReturInfo from buyer perspective
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\User;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== TEST SUBMIT RETUR INFO ===" . PHP_EOL;

// Cari order refund_disetujui yang belum punya data retur
$order = Order::where('status', 'refund_disetujui')
    ->whereNull('no_resi_retur')
    ->first();

if (!$order) {
    echo "Tidak ada order refund_disetujui yang kosong data retur." . PHP_EOL;
    exit;
}

echo "Order ID: {$order->id}, Status Before: {$order->status}" . PHP_EOL;

// Login sebagai buyer pemilik order ini
$buyer = User::find($order->user_id);
Auth::login($buyer);
echo "Logged in as: {$buyer->name} (ID: {$buyer->id})" . PHP_EOL;

// Buat request palsu
$request = Request::create(
    "/orders/{$order->id}/submit-retur",
    'POST',
    [
        'bank_refund'     => 'BCA',
        'norek_refund'    => '9876543210',
        'namarek_refund'  => 'Test Buyer',
        'ekspedisi_retur' => 'JNE',
        'no_resi_retur'   => 'RESI-TEST-' . time(),
    ]
);
$request->setUserResolver(function() use ($buyer) { return $buyer; });

// Panggil controller
$ctrl = new TransactionController();
try {
    $response = $ctrl->submitReturInfo($request, $order->id);
    $order->refresh();
    echo "Response Type: " . get_class($response) . PHP_EOL;
    echo "Order ID {$order->id} Status After: {$order->status}" . PHP_EOL;
    echo "No Resi Retur: {$order->no_resi_retur}" . PHP_EOL;
    echo "Norek Refund: {$order->norek_refund}" . PHP_EOL;

    if ($order->status === 'barang_diretur') {
        echo PHP_EOL . "✅ SUCCESS: Status berubah ke barang_diretur dengan benar!" . PHP_EOL;
    } else {
        echo PHP_EOL . "❌ GAGAL: Status masih {$order->status}" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
