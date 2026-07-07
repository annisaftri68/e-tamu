<?php

use Illuminate\Support\Facades\Route;

// Mengarahkan halaman utama langsung ke tampilan dashboard admin
Route::get('/', function () {
    return view('welcome');
});