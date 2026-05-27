<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->unique();
            $table->unsignedInteger('total_revenue')->default(0);
            $table->unsignedInteger('order_count')->default(0);
            $table->unsignedInteger('avg_order_value')->default(0);
            $table->json('top_items')->nullable();
            $table->text('ai_summary_jp')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();
            $table->index('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
