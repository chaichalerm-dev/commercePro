<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_stat_cards_and_widgets(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create(['payment_status' => PaymentStatus::Paid, 'grand_total' => 1500]);
        Product::factory()->create(['name' => 'Nearly Gone Gadget', 'stock' => 2]);

        $this->actingAs($admin)->get('/admin')
            ->assertOk()
            ->assertSee('รายได้ทั้งหมด')
            ->assertSee($order->order_number)
            ->assertSee('Nearly Gone Gadget');
    }

    public function test_revenue_is_summed_from_paid_orders_only(): void
    {
        Order::factory()->create(['payment_status' => PaymentStatus::Paid, 'grand_total' => 1000, 'created_at' => now()]);
        Order::factory()->create(['payment_status' => PaymentStatus::Paid, 'grand_total' => 250, 'created_at' => now()]);
        Order::factory()->create(['payment_status' => PaymentStatus::Unpaid, 'grand_total' => 9999, 'created_at' => now()]);

        $service = app(DashboardService::class);

        $this->assertSame(1250.0, $service->cards()['revenue']);
        $this->assertSame(1250.0, array_sum($service->revenueChart(7)['values']));
    }

    public function test_top_products_ranks_by_quantity_sold(): void
    {
        $bestSeller = Product::factory()->create(['price' => 100]);
        $slowMover = Product::factory()->create(['price' => 100]);

        $paidOrder = Order::factory()->create(['payment_status' => PaymentStatus::Paid]);
        $paidOrder->items()->create(['product_id' => $bestSeller->id, 'product_name' => $bestSeller->name, 'qty' => 5, 'price' => 100, 'total' => 500]);
        $paidOrder->items()->create(['product_id' => $slowMover->id, 'product_name' => $slowMover->name, 'qty' => 1, 'price' => 100, 'total' => 100]);

        $unpaidOrder = Order::factory()->create(['payment_status' => PaymentStatus::Unpaid]);
        $unpaidOrder->items()->create(['product_id' => $slowMover->id, 'product_name' => $slowMover->name, 'qty' => 50, 'price' => 100, 'total' => 5000]);

        $top = app(DashboardService::class)->topProducts();

        $this->assertSame($bestSeller->name, $top->first()->product_name);
        $this->assertEquals(5, $top->first()->qty_sold);
    }
}
