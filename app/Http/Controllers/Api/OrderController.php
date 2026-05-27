<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['items.menu']);

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $orders = $query->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'table_number' => 'required|integer|min:1|max:99',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1|max:99',
            'note' => 'nullable|string|max:500',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $totalAmount = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $menu = \App\Models\Menu::findOrFail($item['menu_id']);
                $subtotal = $menu->price * $item['quantity'];
                $totalAmount += $subtotal;

                $items[] = [
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }

            $order = Order::create([
                'table_number' => $validated['table_number'],
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($items as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            return $order->load('items.menu');
        });

        return response()->json(['data' => $order], 201);
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with(['items.menu'])->findOrFail($id);
        return response()->json(['data' => $order]);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Order::STATUSES),
        ]);

        $order = Order::findOrFail($id);
        $order->status = $validated['status'];
        $order->save();

        return response()->json(['data' => $order->load('items.menu')]);
    }
}
