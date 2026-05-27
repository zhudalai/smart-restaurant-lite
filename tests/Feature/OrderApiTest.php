<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    private function seedMenu(): void
    {
        Menu::create(['name_jp' => 'まぐろ', 'name_en' => 'Tuna', 'price' => 300, 'category' => 'sushi']);
        Menu::create(['name_jp' => '醤油ラーメン', 'name_en' => 'Shoyu Ramen', 'price' => 850, 'category' => 'ramen']);
        Menu::create(['name_jp' => '抹茶アイス', 'name_en' => 'Matcha Ice', 'price' => 350, 'category' => 'dessert']);
    }

    #[Test]
    public function it_creates_an_order_with_items_and_calculates_total(): void
    {
        $this->seedMenu();

        $response = $this->postJson('/api/orders', [
            'table_number' => 3,
            'items' => [
                ['menu_id' => 1, 'quantity' => 2],
                ['menu_id' => 2, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'table_number', 'status', 'total_amount', 'items']])
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.total_amount', 1450)
            ->assertJsonPath('data.table_number', 3);

        $this->assertDatabaseHas('orders', [
            'table_number' => 3,
            'status' => 'pending',
            'total_amount' => 1450,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $response->json('data.id'),
            'menu_id' => 1,
            'quantity' => 2,
            'subtotal' => 600,
        ]);
    }

    #[Test]
    public function it_rejects_order_with_invalid_menu_id(): void
    {
        $this->seedMenu();

        $response = $this->postJson('/api/orders', [
            'table_number' => 1,
            'items' => [
                ['menu_id' => 9999, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items.0.menu_id']);
    }

    #[Test]
    public function it_rejects_order_with_zero_quantity(): void
    {
        $this->seedMenu();

        $response = $this->postJson('/api/orders', [
            'table_number' => 1,
            'items' => [
                ['menu_id' => 1, 'quantity' => 0],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items.0.quantity']);
    }

    #[Test]
    public function it_updates_order_status_through_full_lifecycle(): void
    {
        $this->seedMenu();

        $order = $this->postJson('/api/orders', [
            'table_number' => 5,
            'items' => [['menu_id' => 1, 'quantity' => 1]],
        ])->json('data');

        $orderId = $order['id'];
        $this->assertEquals('pending', $order['status']);

        $this->patchJson("/api/orders/{$orderId}/status", ['status' => 'preparing'])
            ->assertOk()
            ->assertJsonPath('data.status', 'preparing');

        $this->patchJson("/api/orders/{$orderId}/status", ['status' => 'served'])
            ->assertOk()
            ->assertJsonPath('data.status', 'served');

        $this->patchJson("/api/orders/{$orderId}/status", ['status' => 'paid'])
            ->assertOk()
            ->assertJsonPath('data.status', 'paid');
    }

    #[Test]
    public function it_rejects_invalid_status(): void
    {
        $order = Order::create(['table_number' => 1, 'status' => 'pending', 'total_amount' => 0]);

        $this->patchJson("/api/orders/{$order->id}/status", ['status' => 'invalid_status'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    #[Test]
    public function it_rolls_back_order_creation_on_invalid_item(): void
    {
        $this->seedMenu();

        $response = $this->postJson('/api/orders', [
            'table_number' => 1,
            'items' => [
                ['menu_id' => 1, 'quantity' => 1],
                ['menu_id' => 9999, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
    }

    #[Test]
    public function it_lists_orders_with_items_and_menu_data(): void
    {
        $this->seedMenu();

        $order = Order::create(['table_number' => 2, 'status' => 'pending', 'total_amount' => 600]);
        OrderItem::create(['order_id' => $order->id, 'menu_id' => 1, 'quantity' => 2, 'subtotal' => 600]);

        $response = $this->getJson('/api/orders');

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id', 'table_number', 'status', 'items']]]);
    }
}
