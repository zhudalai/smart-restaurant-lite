<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'report_date', 'total_revenue', 'order_count',
        'avg_order_value', 'top_items', 'ai_summary_jp', 'raw_data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'top_items' => 'array',
        'raw_data' => 'array',
    ];
}
