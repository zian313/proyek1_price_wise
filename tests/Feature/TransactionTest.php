<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_step_checkout_and_payment_flow(): void
    {
        // 1. Setup seller, category, and product with bank details
        $seller = User::factory()->create([
            'role' => 'seller',
        ]);
        $category = Category::create(['name' => 'Electronic']);
        $product = Product::create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'nama_produk' => 'Laptop Bekas',
            'deskripsi' => 'Kondisi 95% mulus',
            'harga' => 5000000,
            'stok' => 1,
            'bank_name' => 'M-Banking Mandiri',
            'no_rekening' => '9876543210',
            'atas_nama' => 'John Seller Account',
        ]);

        // 2. Setup buyer and authenticate
        $buyer = User::factory()->create(['role' => 'buyer']);

        // 3. STEP 1: Submit checkout form
        $checkoutData = [
            'nama' => 'Penerima Test',
            'email' => 'penerima@example.com',
            'alamat' => 'Jl. Kebenaran No. 45, Jakarta',
            'ekspedisi' => 'JNE Express',
            'metode_pembayaran' => 'M-Banking Mandiri',
        ];

        $response = $this->actingAs($buyer)
            ->post(route('checkout.store', $product->id), $checkoutData);

        // Verify order is created in database with 'menunggu_pembayaran' status
        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'total_harga' => $product->harga,
            'status' => 'menunggu_pembayaran',
            'nama' => 'Penerima Test',
            'email' => 'penerima@example.com',
            'alamat' => 'Jl. Kebenaran No. 45, Jakarta',
            'ekspedisi' => 'JNE Express',
            'metode_pembayaran' => 'M-Banking Mandiri',
        ]);

        $order = Order::latest()->first();

        // Verify redirect to payment page
        $response->assertRedirect(route('orders.payment', $order->id));

        // Get payment page and assert it shows seller's bank details
        $paymentPageResponse = $this->actingAs($buyer)->get(route('orders.payment', $order->id));
        $paymentPageResponse->assertSee('9876543210');
        $paymentPageResponse->assertSee('John Seller Account');
        $paymentPageResponse->assertSee('M-Banking Mandiri');

        // 4. STEP 2: Submit payment proof
        Storage::fake('public');
        $file = UploadedFile::fake()->image('bukti_tf.jpg');

        $paymentResponse = $this->actingAs($buyer)
            ->post(route('orders.pay', $order->id), [
                'bukti_transfer' => $file,
            ]);

        // Verify status changed to 'menunggu_verifikasi'
        $order->refresh();
        $this->assertEquals('menunggu_verifikasi', $order->status);
        $this->assertNotNull($order->bukti_transfer);

        // Verify redirect to history page
        $paymentResponse->assertRedirect(route('orders.history'));
    }
}
