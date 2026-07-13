<?php

namespace Tests\Feature;

use App\Models\Tamu;
use App\Models\TamuArsip;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MonthlyArchiveTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('tamu', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('nama')->nullable();
            $table->string('instansi')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->text('keperluan')->nullable();
            $table->string('bidang')->nullable();
            $table->string('nomor_antrian')->nullable();
            $table->date('tanggal_kunjungan')->nullable();
            $table->string('status')->default('waiting');
            $table->text('saran')->nullable();
            $table->integer('kemudahan_penggunaan')->nullable();
            $table->integer('tampilan_sistem')->nullable();
            $table->integer('kecepatan_sistem')->nullable();
            $table->integer('stabilitas_sistem')->nullable();
            $table->integer('keramahan_pegawai')->nullable();
            $table->integer('kecepatan_pelayanan')->nullable();
            $table->integer('kejelasan_informasi')->nullable();
            $table->integer('kepuasan_keseluruhan')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('tamu_arsip', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('instansi')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->text('keperluan')->nullable();
            $table->string('bidang')->nullable();
            $table->string('nomor_antrian')->nullable();
            $table->date('tanggal_kunjungan')->nullable();
            $table->string('status')->default('waiting');
            $table->text('saran')->nullable();
            $table->integer('kemudahan_penggunaan')->nullable();
            $table->integer('tampilan_sistem')->nullable();
            $table->integer('kecepatan_sistem')->nullable();
            $table->integer('stabilitas_sistem')->nullable();
            $table->integer('keramahan_pegawai')->nullable();
            $table->integer('kecepatan_pelayanan')->nullable();
            $table->integer('kejelasan_informasi')->nullable();
            $table->integer('kepuasan_keseluruhan')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function test_it_archives_previous_month_records_and_keeps_current_month_active(): void
    {
        $now = Carbon::now();
        $previousMonth = $now->copy()->subMonth();

        Tamu::create([
            'nik' => '1111',
            'nama_lengkap' => 'Tamu Lama',
            'nama' => 'Tamu Lama',
            'instansi' => 'Instansi Lama',
            'nomor_hp' => '08123456789',
            'keperluan' => 'Keperluan Lama',
            'bidang' => 'PSDM',
            'nomor_antrian' => 'A-001',
            'tanggal_kunjungan' => $previousMonth->copy()->startOfMonth()->toDateString(),
            'status' => 'waiting',
            'created_at' => $previousMonth->copy()->startOfMonth()->toDateTimeString(),
            'updated_at' => $previousMonth->copy()->startOfMonth()->toDateTimeString(),
        ]);

        Tamu::create([
            'nik' => '2222',
            'nama_lengkap' => 'Tamu Baru',
            'nama' => 'Tamu Baru',
            'instansi' => 'Instansi Baru',
            'nomor_hp' => '08123456780',
            'keperluan' => 'Keperluan Baru',
            'bidang' => 'PPEKA',
            'nomor_antrian' => 'A-002',
            'tanggal_kunjungan' => $now->copy()->startOfMonth()->toDateString(),
            'status' => 'waiting',
            'created_at' => $now->copy()->startOfMonth()->toDateTimeString(),
            'updated_at' => $now->copy()->startOfMonth()->toDateTimeString(),
        ]);

        $archivedCount = Tamu::archiveMonthly();

        $this->assertSame(1, $archivedCount);
        $this->assertSame(1, Tamu::count());
        $this->assertSame(1, TamuArsip::count());
    }
}
