<?php

namespace Tests\Feature;

use App\Models\Checkout;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private const ADDRESS = [
        'recipient_name' => 'Budi Santoso',
        'phone' => '081234567890',
        'email' => 'budi@example.com',
        'address' => 'Jl. Melati No. 12, Jakarta Selatan 12110',
        'note' => 'Leave it with security',
    ];

    private function product(array $attributes = []): Product
    {
        $category = ProductCategory::firstOrCreate(['name' => 'Serums']);

        return Product::create($attributes + [
            'category_id' => $category->id,
            'name' => 'Niacinamide 10% Serum 30ml',
            'description' => 'Test serum',
            'price' => 159000,
            'stock' => 10,
        ]);
    }

    public function test_product_page_has_add_to_cart_and_no_buy_now(): void
    {
        $product = $this->product();

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Add to cart')
            ->assertSee(route('cart.store', $product), false)
            ->assertDontSee('Buy now');
    }

    public function test_guest_can_add_to_cart_and_sees_popup_and_count(): void
    {
        $product = $this->product();

        $this->from(route('products.show', $product))
            ->post(route('cart.store', $product), ['quantity' => 2])
            ->assertRedirect(route('products.show', $product))
            ->assertSessionHas('cart', [$product->id => 2]);

        $this->get(route('products.show', $product))
            ->assertSee('2 already in your', false);

        $this->followingRedirects()
            ->from(route('products.show', $product))
            ->post(route('cart.store', $product), ['quantity' => 1])
            ->assertSee('Added to cart')
            ->assertSee('<span class="count">3</span>', false);
    }

    public function test_cart_never_holds_more_than_the_stock(): void
    {
        $product = $this->product(['stock' => 3]);

        $this->post(route('cart.store', $product), ['quantity' => 5])->assertSessionHas('cart', [$product->id => 3]);

        $this->post(route('cart.store', $product), ['quantity' => 1])
            ->assertSessionHasErrors('quantity')
            ->assertSessionHas('cart', [$product->id => 3]);
    }

    public function test_out_of_stock_product_cannot_be_added(): void
    {
        $product = $this->product(['stock' => 0]);

        $this->get(route('products.show', $product))->assertSee('Out of stock')->assertDontSee('Add to cart');
        $this->post(route('cart.store', $product), ['quantity' => 1])->assertSessionHasErrors('quantity');
        $this->assertEmpty(session('cart', []));
    }

    public function test_cart_page_shows_totals_and_shipping(): void
    {
        $serum = $this->product(['price' => 159000]);
        $wash = $this->product(['name' => 'Charcoal Deep Face Wash 100ml', 'price' => 89000]);

        // Under Rp 200,000: shipping fee is added
        $this->withSession(['cart' => [$wash->id => 1]])
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Rp 15,000')
            ->assertSee('Rp 104,000')
            ->assertSee('Add Rp 111,000 more for free shipping');

        // Rp 248,000: free shipping
        $this->withSession(['cart' => [$serum->id => 1, $wash->id => 1]])
            ->get(route('cart.index'))
            ->assertSee('Subtotal (2 items)')
            ->assertSee('Rp 248,000')
            ->assertSee('You get free shipping');
    }

    public function test_quantity_can_be_changed_and_items_removed(): void
    {
        $product = $this->product();
        $this->withSession(['cart' => [$product->id => 2]]);

        $this->patch(route('cart.update', $product), ['quantity' => 3])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('cart', [$product->id => 3]);

        $this->patch(route('cart.update', $product), ['quantity' => 0])->assertSessionHas('cart', []);

        $this->withSession(['cart' => [$product->id => 2]])
            ->delete(route('cart.destroy', $product))
            ->assertSessionHas('cart', []);

        $this->get(route('cart.index'))->assertSee('Your cart is empty.');
    }

    public function test_guest_must_log_in_before_checkout(): void
    {
        $this->get(route('checkout.create'))->assertRedirect(route('login'));
    }

    public function test_empty_cart_goes_back_to_cart_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('checkout.create'))
            ->assertRedirect(route('cart.index'));
    }

    public function test_full_flow_place_order_then_payment_form_then_done(): void
    {
        $user = User::factory()->create();
        $serum = $this->product(['price' => 159000, 'stock' => 10]);
        $wash = $this->product(['name' => 'Charcoal Deep Face Wash 100ml', 'price' => 89000, 'stock' => 5]);

        $this->actingAs($user)->withSession(['cart' => [$serum->id => 2, $wash->id => 1]]);

        $this->get(route('checkout.create'))
            ->assertOk()
            ->assertSee('Place order')
            ->assertSee('Rp 407,000')
            ->assertSee($user->email, false);

        $response = $this->post(route('checkout.store'), self::ADDRESS);

        $checkout = Checkout::firstOrFail();
        $response->assertRedirect(route('checkout.payment', $checkout));
        $this->assertSame([], session('cart', []), 'cart is emptied after placing the order');

        // Totals, one order row per product, stock reduced
        $this->assertSame(407000, $checkout->subtotal);
        $this->assertSame(0, $checkout->shipping);
        $this->assertSame(407000, $checkout->total);
        $this->assertSame(2, Order::where('checkout_id', $checkout->id)->count());
        $this->assertDatabaseHas('orders', ['checkout_id' => $checkout->id, 'product_id' => $serum->id, 'quantity' => 2, 'total' => 318000, 'status' => 'pending', 'user_id' => $user->id]);
        $this->assertSame(8, $serum->fresh()->stock);
        $this->assertSame(4, $wash->fresh()->stock);

        // Payment page: the Message field is already filled with the order text
        $this->get(route('checkout.payment', $checkout))
            ->assertOk()
            ->assertSee($checkout->code)
            ->assertSee('123 456 7890')
            ->assertSee('- Niacinamide 10% Serum 30ml x2 = Rp 318,000')
            ->assertSee('- Charcoal Deep Face Wash 100ml x1 = Rp 89,000')
            ->assertSee('TOTAL    : Rp 407,000')
            ->assertSee('Address  : Jl. Melati No. 12, Jakarta Selatan 12110')
            ->assertSee('Note     : Leave it with security');

        $this->post(route('checkout.payment.send', $checkout), ['message' => 'Paid, see transfer from Budi'])
            ->assertRedirect(route('checkout.done', $checkout));

        $checkout->refresh();
        $this->assertSame('Paid, see transfer from Budi', $checkout->payment_message);
        $this->assertNotNull($checkout->payment_sent_at);

        $this->get(route('checkout.done', $checkout))->assertOk()->assertSee('We got your order.');
    }

    public function test_shipping_fee_is_charged_under_free_shipping_limit(): void
    {
        $wash = $this->product(['price' => 89000]);

        $this->actingAs(User::factory()->create())
            ->withSession(['cart' => [$wash->id => 1]])
            ->post(route('checkout.store'), self::ADDRESS);

        $checkout = Checkout::firstOrFail();
        $this->assertSame(15000, $checkout->shipping);
        $this->assertSame(104000, $checkout->total);
    }

    public function test_order_is_refused_when_stock_ran_out_meanwhile(): void
    {
        $product = $this->product(['stock' => 5]);
        $this->actingAs(User::factory()->create())->withSession(['cart' => [$product->id => 3]]);

        $product->update(['stock' => 2]); // someone else bought some

        $this->from(route('checkout.create'))
            ->post(route('checkout.store'), self::ADDRESS)
            ->assertRedirect(route('checkout.create'))
            ->assertSessionHasErrors('cart');

        $this->assertSame(0, Checkout::count());
        $this->assertSame(2, $product->fresh()->stock);
    }

    public function test_shipping_details_are_required(): void
    {
        $product = $this->product();

        $this->actingAs(User::factory()->create())
            ->withSession(['cart' => [$product->id => 1]])
            ->post(route('checkout.store'), [])
            ->assertSessionHasErrors(['recipient_name', 'phone', 'email', 'address']);

        $this->assertSame(0, Checkout::count());
    }

    public function test_customers_cannot_open_someone_elses_order(): void
    {
        $owner = User::factory()->create();
        $product = $this->product();
        $this->actingAs($owner)->withSession(['cart' => [$product->id => 1]])->post(route('checkout.store'), self::ADDRESS);
        $checkout = Checkout::firstOrFail();

        $stranger = User::factory()->create();
        $this->actingAs($stranger)->get(route('checkout.payment', $checkout))->assertNotFound();
        $this->actingAs($stranger)->post(route('checkout.payment.send', $checkout), ['message' => 'x'])->assertNotFound();
        $this->actingAs($stranger)->get(route('checkout.done', $checkout))->assertNotFound();
    }

    public function test_new_orders_show_up_on_the_admin_dashboard(): void
    {
        $product = $this->product();
        $customer = User::factory()->create();
        $this->actingAs($customer)->withSession(['cart' => [$product->id => 2]])->post(route('checkout.store'), self::ADDRESS);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('dashboard'))->assertOk()->assertSee('Rp 318');
    }
}
