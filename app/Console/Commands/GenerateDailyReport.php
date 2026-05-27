<?php

namespace App\Console\Commands;

use App\Models\DailyReport;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GenerateDailyReport extends Command
{
    protected $signature = 'report:daily {date? : YYYY-MM-DD (default: yesterday)}';
    protected $description = 'Generate AI daily sales report via OpenRouter';

    public function handle(): int
    {
        $date = $this->argument('date') ?? now()->subDay()->format('Y-m-d');

        $this->info("Generating report for {$date}...");

        $orders = Order::whereDate('created_at', $date)
            ->where('status', '!=', 'cancelled')
            ->with('items.menu')
            ->get();

        if ($orders->isEmpty()) {
            $this->warn("No orders found for {$date}");
            return self::SUCCESS;
        }

        $orderCount = $orders->count();
        $totalRevenue = $orders->sum('total_amount');
        $avgOrderValue = round($totalRevenue / $orderCount);

        $topItems = $orders->flatMap(fn($o) => $o->items)
            ->groupBy('menu.name_jp')
            ->map(fn($items) => [
                'name' => $items->first()->menu->name_jp,
                'quantity' => $items->sum('quantity'),
                'revenue' => $items->sum('subtotal'),
            ])
            ->sortByDesc('quantity')
            ->take(5)
            ->values()
            ->all();

        $reportData = [
            'date' => $date,
            'order_count' => $orderCount,
            'total_revenue' => $totalRevenue,
            'avg_order_value' => $avgOrderValue,
            'top_items' => $topItems,
        ];

        $aiSummary = $this->generateAiSummary($reportData);

        $report = DailyReport::create([
            'report_date' => $date,
            'total_revenue' => $totalRevenue,
            'order_count' => $orderCount,
            'avg_order_value' => $avgOrderValue,
            'top_items' => $topItems,
            'ai_summary_jp' => $aiSummary,
            'raw_data' => $reportData,
        ]);

        $this->info("✓ Report saved: #{$report->id}");
        $this->line("  Revenue: ¥{$totalRevenue}");
        $this->line("  Orders: {$orderCount}");
        $this->line("  AI Summary: {$aiSummary}");

        return self::SUCCESS;
    }

    private function generateAiSummary(array $data): string
    {
        $apiKey = config('services.openrouter.api_key');
        if (!$apiKey || $apiKey === 'your_openrouter_key_here') {
            return $this->fallbackSummary($data);
        }

        $prompt = $this->buildPrompt($data);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => config('app.url'),
                ])
                ->post(config('services.openrouter.base_url', 'https://openrouter.ai/api/v1') . '/chat/completions', [
                    'model' => 'anthropic/claude-3-haiku',
                    'messages' => [
                        ['role' => 'system', 'content' => 'あなたは飲食店の経営コンサルタントです。データを元に簡潔な日本語で営業日報を書いてください。100字以内。'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 200,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content') ?? $this->fallbackSummary($data);
            }
        } catch (\Exception $e) {
            $this->warn("AI generation failed: {$e->getMessage()}");
        }

        return $this->fallbackSummary($data);
    }

    private function buildPrompt(array $data): string
    {
        $items = collect($data['top_items'])
            ->map(fn($i) => "{$i['name']}={$i['quantity']}個")
            ->join(', ');

        return <<<PROMPT
日付: {$data['date']}
注文数: {$data['order_count']}
売上: ¥{$data['total_revenue']}
平均単価: ¥{$data['avg_order_value']}
トップ商品: {$items}

このデータから今日の営業所感を100字以内で書いてください。
PROMPT;
    }

    private function fallbackSummary(array $data): string
    {
        $topItem = $data['top_items'][0]['name'] ?? 'N/A';
        return "{$data['date']}の売上は¥{$data['total_revenue']}（{$data['order_count']}件）。人気商品は{$topItem}でした。";
    }
}
