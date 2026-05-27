<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name_jp', 'name_en', 'price', 'category', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
