<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    protected $appends = ['nama'];

    public function getNamaAttribute(): ?string
    {
        return $this->attributes['nama_lengkap'] ?? $this->attributes['nama'] ?? null;
    }

    public static function archiveMonthly(): int
    {
        $previousMonthStart = now()->copy()->startOfMonth()->subMonth();
        $previousMonthEnd = $previousMonthStart->copy()->endOfMonth();

        $records = static::query()
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->get();

        if ($records->isEmpty()) {
            return 0;
        }

        $payload = $records->map(function ($record) {
            return [
                'nik' => $record->nik,
                'nama_lengkap' => $record->nama_lengkap ?? $record->nama,
                'instansi' => $record->instansi,
                'nomor_hp' => $record->nomor_hp,
                'keperluan' => $record->keperluan,
                'bidang' => $record->bidang,
                'nomor_antrian' => $record->nomor_antrian,
                'tanggal_kunjungan' => $record->tanggal_kunjungan,
                'status' => $record->status,
                'saran' => $record->saran,
                'kemudahan_penggunaan' => $record->kemudahan_penggunaan,
                'tampilan_sistem' => $record->tampilan_sistem,
                'kecepatan_sistem' => $record->kecepatan_sistem,
                'stabilitas_sistem' => $record->stabilitas_sistem,
                'keramahan_pegawai' => $record->keramahan_pegawai,
                'kecepatan_pelayanan' => $record->kecepatan_pelayanan,
                'kejelasan_informasi' => $record->kejelasan_informasi,
                'kepuasan_keseluruhan' => $record->kepuasan_keseluruhan,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ];
        })->all();

        DB::transaction(function () use ($records, $payload) {
            TamuArsip::insert($payload);
            $records->each(fn ($record) => $record->delete());
        });

        return $records->count();
    }
}