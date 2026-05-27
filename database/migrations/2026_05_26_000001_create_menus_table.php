<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name_jp', 100);
            $table->string('name_en', 100)->nullable();
            $table->unsignedInteger('price');
            $table->string('category', 50);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
