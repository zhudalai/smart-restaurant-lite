<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['table_number', 'status', 'total_amount', 'note'];

    protected $casts = [
        'table_number' => 'integer',
        'total_amount' => 'integer',
    ];

    public const STATUSES = ['pending', 'preparing', 'served', 'paid', 'cancelled'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function menuItems()
    {
        return $this->belongsToMany(Menu::class, 'order_items')
            ->withPivot('quantity', 'subtotal');
    }

    public function calculateTotal(): int
    {
        return $this->items->sum('subtotal');
    }
}
