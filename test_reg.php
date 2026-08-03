<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    $user = User::create([
        'name' => 'testuser3',
        'email' => 'test3@example.com',
        'password' => Hash::make('password123'),
        'role' => 'buyer',
        'seller_status' => 'approved',
    ]);
    echo "Success: User created with ID " . $user->id . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
