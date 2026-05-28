<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/app/{path?}', 'app')
    ->where('path', '.*')
    ->name('operations.app');
