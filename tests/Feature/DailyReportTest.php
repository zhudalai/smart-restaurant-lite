<?php

namespace Tests\Feature;

use App\Models\DailyReport;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DailyReportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_lists_reports_ordered_by_date_desc(): void
    {
        DailyReport::create([
            'report_date' => '2026-05-20',
            'total_revenue' => 10000,
            'order_count' => 10,
            'avg_order_value' => 1000,
            'top_items' => [],
            'ai_summary_jp' => 'test',
        ]);
        DailyReport::create([
            'report_date' => '2026-05-25',
            'total_revenue' => 20000,
            'order_count' => 20,
            'avg_order_value' => 1000,
            'top_items' => [],
            'ai_summary_jp' => 'test',
        ]);

        $response = $this->getJson('/api/reports');

        $response->assertOk();
        $data = $response->json('data');
        $this->assertStringStartsWith('2026-05-25', $data[0]['report_date']);
        $this->assertStringStartsWith('2026-05-20', $data[1]['report_date']);
    }

    #[Test]
    public function it_returns_latest_report(): void
    {
        DailyReport::create([
            'report_date' => '2026-05-25',
            'total_revenue' => 15000,
            'order_count' => 15,
            'avg_order_value' => 1000,
            'top_items' => [['name' => 'まぐろ', 'quantity' => 10, 'revenue' => 3000]],
            'ai_summary_jp' => '今日は好調でした。',
        ]);

        $response = $this->getJson('/api/reports/latest');

        $response->assertOk()
            ->assertJsonPath('data.total_revenue', 15000)
            ->assertJsonPath('data.ai_summary_jp', '今日は好調でした。');
    }

    #[Test]
    public function it_returns_404_when_no_reports_exist(): void
    {
        $this->getJson('/api/reports/latest')->assertStatus(404);
    }

    #[Test]
    public function it_shows_single_report(): void
    {
        $report = DailyReport::create([
            'report_date' => '2026-05-25',
            'total_revenue' => 5580,
            'order_count' => 4,
            'avg_order_value' => 1395,
            'top_items' => [['name' => '抹茶アイス', 'quantity' => 5, 'revenue' => 1750]],
            'ai_summary_jp' => 'テストサマリー',
        ]);

        $this->getJson("/api/reports/{$report->id}")
            ->assertOk()
            ->assertJsonPath('data.total_revenue', 5580);
    }
}
