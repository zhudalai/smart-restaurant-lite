<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/pos');
});

Route::get('/pos', function () {
    return view('pos');
});

Route::get('/reports', function () {
    return view('reports');
});
