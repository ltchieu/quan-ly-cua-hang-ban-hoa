<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCancellationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cart_is_preserved_when_payment_is_cancelled()
    {
        // 1. Create a user and a product
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        // 2. Add the product to the cart
        $this->actingAs($user)->post(route('cart.add', $product->id), [
            'quantity' => 1,
        ]);

        // 3. Go to checkout and choose an online payment method
        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'full_name' => 'Test User',
            'phone' => '123456789',
            'address' => '123 Test Street',
            'payment_method' => 'momo',
        ]);

        // 4. Extract the temporary order ID from the redirect
        $redirectUrl = $response->headers->get('Location');
        $tempOrderId = substr($redirectUrl, strrpos($redirectUrl, '/') + 1);

        // 5. Simulate cancelling the payment
        $cancelResponse = $this->actingAs($user)->get(route('payment.cancel', $tempOrderId));

        // 6. Assert that the cart is not empty
        $this->assertNotEmpty(session('cart'));

        // 7. Assert that the user is redirected to the checkout page
        $cancelResponse->assertRedirect(route('checkout.index'));
    }
}
