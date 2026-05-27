<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function index(): JsonResponse
    {
        $menus = Menu::where('is_active', true)
            ->orderBy('category')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => $menus,
            'categories' => $menus->pluck('category')->unique()->values(),
        ]);
    }
}
