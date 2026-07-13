<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TamuController extends Controller
{
    /**
     * Menerima dan menyimpan data tamu dari mesin kiosk.
     */
    public function store(Request $request)
    {
        // 1. Validasi Keamanan Token API (Ganti token sesuai kebutuhan Anda)
        $apiKey = $request->header('X-API-Key');
        if ($apiKey !== 'KioskSecureToken2026') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: API Key tidak valid.'
            ], 401);
        }

        // 2. Validasi Kelengkapan Data dari Kiosk
        $request->validate([
            'nama' => 'required|string|max:100',
            'instansi' => 'required|string|max:100',
            'keperluan' => 'required|string',
        ]);

        try {
            // 3. Simpan data langsung ke tabel database admin yang sudah ada
            // Sesuai petunjuk Anda sebelumnya, ganti nama tabel & kolom di bawah ini
            // dengan nama tabel dan kolom asli yang ada di database MySQL admin Anda.
            DB::table('nama_tabel_admin_anda')->insert([
                'kolom_nama' => $request->nama,
                'kolom_instansi' => $request->instansi,
                'kolom_keperluan' => $request->keperluan,
                'created_at' => now(), // Diisi jika tabel admin memiliki kolom timestamp
            ]);

            // 4. Beri respon balik ke kiosk bahwa data berhasil disimpan
            return response()->json([
                'success' => true,
                'message' => 'Data tamu berhasil dicatat di database admin.'
            ], 201);

        } catch (\Exception $e) {
            // Penanganan jika ada error pada database
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan ke database: ' . $e->getMessage()
            ], 500);
        }
    }
}