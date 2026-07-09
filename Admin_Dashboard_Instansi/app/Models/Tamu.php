<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    /**
     * Mengunci nama tabel asli di database e-Tamu.
     * Mengamankan sistem agar Laravel tidak otomatis mencari tabel 'tamus' (plural).
     * * @var string
     */
    protected $table = 'tamu';

    /**
     * Jika Primary Key di database e-Tamu Anda BUKAN bernama 'id' (misal: 'id_tamu'),
     * silakan hapus tanda komentar (//) pada baris di bawah ini dan sesuaikan namanya:
     */
    // protected $primaryKey = 'id_tamu';

    /**
     * Mengizinkan semua kolom untuk diisi secara massal (Mass Assignment Protection).
     * Sangat aman digunakan karena proses validasi data input sudah ketat di sisi controller.
     * * @var array
     */
    protected $guarded = [];

    /**
     * Memastikan kolom tanggal otomatis dikonversi menjadi objek Carbon/DateTime.
     * Ini sangat krusial agar method seperti $tamu->created_at->format('H:i') 
     * pada halaman Kunjungan Hari Ini tidak menghasilkan eror.
     * * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}