<?php

use App\Http\Controllers\Api\DailyReportController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/menus', [MenuController::class, 'index']);
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);

Route::get('/reports/latest', [DailyReportController::class, 'latest']);
Route::get('/reports', [DailyReportController::class, 'index']);
Route::get('/reports/{id}', [DailyReportController::class, 'show']);
Route::post('/reports/generate', [DailyReportController::class, 'generate']);
