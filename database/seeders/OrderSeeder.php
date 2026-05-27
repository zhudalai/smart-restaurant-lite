<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $menuIds = DB::table('menus')->pluck('id')->toArray();
        $statuses = ['pending', 'preparing', 'served', 'paid', 'cancelled'];
        $statusWeights = [10, 15, 20, 50, 5]; // 大部分已付款

        // 生成 30 天的订单，每天约 6-8 单
        for ($day = 29; $day >= 0; $day--) {
            $date = now()->subDays($day)->format('Y-m-d');
            $dailyOrders = rand(5, 10);

            for ($i = 0; $i < $dailyOrders; $i++) {
                $tableNumber = rand(1, 12);
                $status = $this->weightedRandom($statuses, $statusWeights);
                $createdAt = $date . ' ' . rand(10, 21) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);

                // 每单 1-4 个菜品
                $itemCount = rand(1, 4);
                $totalAmount = 0;
                $items = [];

                for ($j = 0; $j < $itemCount; $j++) {
                    $menuId = $menuIds[array_rand($menuIds)];
                    $menu = DB::table('menus')->find($menuId);
                    $quantity = rand(1, 3);
                    $subtotal = $menu->price * $quantity;
                    $totalAmount += $subtotal;

                    $items[] = [
                        'order_id' => null, // 先占位
                        'menu_id' => $menuId,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ];
                }

                $orderId = DB::table('orders')->insertGetId([
                    'table_number' => $tableNumber,
                    'status' => $status,
                    'total_amount' => $totalAmount,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($items as &$item) {
                    $item['order_id'] = $orderId;
                    $item['created_at'] = $createdAt;
                    $item['updated_at'] = $createdAt;
                }
                DB::table('order_items')->insert($items);
            }
        }
    }

    private function weightedRandom(array $items, array $weights): string
    {
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $cumulative = 0;

        foreach ($items as $i => $item) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) {
                return $item;
            }
        }

        return end($items);
    }
}
