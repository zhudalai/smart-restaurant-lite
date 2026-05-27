<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // 寿司
            ['name_jp' => 'まぐろ', 'name_en' => 'Tuna Sushi', 'price' => 300, 'category' => 'sushi'],
            ['name_jp' => 'サーモン', 'name_en' => 'Salmon Sushi', 'price' => 280, 'category' => 'sushi'],
            ['name_jp' => 'えび', 'name_en' => 'Shrimp Sushi', 'price' => 250, 'category' => 'sushi'],
            ['name_jp' => 'たまご', 'name_en' => 'Egg Sushi', 'price' => 180, 'category' => 'sushi'],
            ['name_jp' => 'いくら', 'name_en' => 'Salmon Roe Sushi', 'price' => 350, 'category' => 'sushi'],
            // ラーメン
            ['name_jp' => '醤油ラーメン', 'name_en' => 'Shoyu Ramen', 'price' => 850, 'category' => 'ramen'],
            ['name_jp' => '味噌ラーメン', 'name_en' => 'Miso Ramen', 'price' => 900, 'category' => 'ramen'],
            ['name_jp' => 'とんこつラーメン', 'name_en' => 'Tonkotsu Ramen', 'price' => 950, 'category' => 'ramen'],
            ['name_jp' => 'つけ麺', 'name_en' => 'Tsukemen', 'price' => 1000, 'category' => 'ramen'],
            // 丼
            ['name_jp' => '牛丼', 'name_en' => 'Gyudon', 'price' => 700, 'category' => 'donburi'],
            ['name_jp' => '親子丼', 'name_en' => 'Oyakodon', 'price' => 750, 'category' => 'donburi'],
            ['name_jp' => '天丼', 'name_en' => 'Tendon', 'price' => 850, 'category' => 'donburi'],
            ['name_jp' => 'カツ丼', 'name_en' => 'Katsudon', 'price' => 900, 'category' => 'donburi'],
            // 飲み物
            ['name_jp' => '緑茶', 'name_en' => 'Green Tea', 'price' => 150, 'category' => 'drink'],
            ['name_jp' => '烏龍茶', 'name_en' => 'Oolong Tea', 'price' => 150, 'category' => 'drink'],
            ['name_jp' => 'コーラ', 'name_en' => 'Cola', 'price' => 200, 'category' => 'drink'],
            ['name_jp' => 'オレンジジュース', 'name_en' => 'Orange Juice', 'price' => 250, 'category' => 'drink'],
            ['name_jp' => '生ビール', 'name_en' => 'Draft Beer', 'price' => 550, 'category' => 'drink'],
            // デザート
            ['name_jp' => '抹茶アイス', 'name_en' => 'Matcha Ice Cream', 'price' => 350, 'category' => 'dessert'],
            ['name_jp' => 'プリン', 'name_en' => 'Pudding', 'price' => 300, 'category' => 'dessert'],
        ];

        foreach ($menus as $menu) {
            $menu['created_at'] = now();
            $menu['updated_at'] = now();
            DB::table('menus')->insert($menu);
        }
    }
}
