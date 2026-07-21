<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\TamuController;

// Endpoint untuk menerima data tamu dari kiosk
Route::post('/tamu', [TamuController::class, 'store']);

// ============================================================
//  API LOGIN - Untuk React Frontend
// ============================================================
Route::post('/login', function (Request $request) {
    // 1. Validasi input
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $userInput = trim($credentials['username']);

    // 2. Tentukan field login (NIP jika numerik 8+ digit, else username)
    if (preg_match('/^[0-9]{8,}$/', $userInput)) {
        $fieldType = 'nip';
    } else {
        $fieldType = 'username';
    }

    // 3. Cari user di database
    $user = \App\Models\User::where($fieldType, $userInput)->first();

    if ($user && Hash::check($credentials['password'], $user->password)) {
        // 4. Login sukses - return data user
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'nip' => $user->nip,
            ],
        ]);
    }

    // 5. Jika gagal
    return response()->json([
        'success' => false,
        'message' => 'Username/NIP atau Password salah!',
    ], 401);
});
