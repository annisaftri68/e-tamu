<?php

namespace Tests\Feature;

use App\Http\Controllers\LaporanController;
use App\Models\Tamu;
use App\Models\TamuArsip;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Tests\TestCase;

class ExcelMonthlyGroupingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('tamu', function (Blueprint $table) {
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
            $table->timestamps();
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
            $table->timestamps();
        });
    }

    public function test_it_groups_excel_export_by_month(): void
    {
        $now = now();
        $previous = now()->copy()->subMonth();

        Tamu::create([
            'nik' => '001',
            'nama_lengkap' => 'Tamu Bulan Ini',
            'instansi' => 'Instansi Baru',
            'nomor_hp' => '0811111111',
            'keperluan' => 'Keperluan Baru',
            'bidang' => 'PSDM',
            'nomor_antrian' => 'A-01',
            'tanggal_kunjungan' => $now->toDateString(),
            'status' => 'waiting',
            'created_at' => $now->toDateTimeString(),
            'updated_at' => $now->toDateTimeString(),
        ]);

        TamuArsip::create([
            'nik' => '002',
            'nama_lengkap' => 'Tamu Bulan Lalu',
            'instansi' => 'Instansi Lama',
            'nomor_hp' => '0822222222',
            'keperluan' => 'Keperluan Lama',
            'bidang' => 'PPEKA',
            'nomor_antrian' => 'A-02',
            'tanggal_kunjungan' => $previous->toDateString(),
            'status' => 'waiting',
            'created_at' => $previous->toDateTimeString(),
            'updated_at' => $previous->toDateTimeString(),
        ]);

        $controller = new LaporanController();
        $html = $controller->buildMonthlyExcelTables(new Request());

        $this->assertStringContainsString('Tamu Bulan Ini', $html);
        $this->assertStringContainsString('Tamu Bulan Lalu', $html);
        $this->assertStringContainsString('Bulan', $html);
    }
}
