<?php

namespace Tests\Feature;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MenuApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_active_menus_grouped_by_category(): void
    {
        Menu::create(['name_jp' => 'まぐろ', 'name_en' => 'Tuna', 'price' => 300, 'category' => 'sushi']);
        Menu::create(['name_jp' => 'サーモン', 'name_en' => 'Salmon', 'price' => 280, 'category' => 'sushi']);
        Menu::create(['name_jp' => '醤油ラーメン', 'name_en' => 'Shoyu Ramen', 'price' => 850, 'category' => 'ramen']);
        Menu::create(['name_jp' => '(非公開)', 'name_en' => 'Hidden', 'price' => 100, 'category' => 'other', 'is_active' => false]);

        $response = $this->getJson('/api/menus');

        $response->assertOk()
            ->assertJsonStructure(['data', 'categories'])
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(['name_jp' => 'まぐろ'])
            ->assertJsonFragment(['name_jp' => 'サーモン'])
            ->assertJsonFragment(['name_jp' => '醤油ラーメン'])
            ->assertJsonMissing(['name_jp' => '(非公開)']);
    }

    #[Test]
    public function it_returns_categories_list(): void
    {
        Menu::create(['name_jp' => 'まぐろ', 'name_en' => 'Tuna', 'price' => 300, 'category' => 'sushi']);
        Menu::create(['name_jp' => 'ラーメン', 'name_en' => 'Ramen', 'price' => 850, 'category' => 'ramen']);

        $response = $this->getJson('/api/menus');

        $categories = $response->json('categories');
        $this->assertContains('sushi', $categories);
        $this->assertContains('ramen', $categories);
    }
}
