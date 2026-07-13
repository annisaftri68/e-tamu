<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TamuController;


// Endpoint untuk menerima data tamu dari kiosk
Route::post('/tamu', [TamuController::class, 'store']);