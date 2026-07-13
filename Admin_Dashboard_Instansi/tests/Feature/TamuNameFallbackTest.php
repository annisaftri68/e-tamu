<?php

namespace Tests\Feature;

use App\Models\Tamu;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TamuNameFallbackTest extends TestCase
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
    }

    public function test_tamu_name_accessor_falls_back_to_nama_lengkap(): void
    {
        Tamu::create([
            'nik' => '123456',
            'nama_lengkap' => 'Anisa Fitri',
            'instansi' => 'BKPSDM',
            'nomor_hp' => '08123456789',
            'keperluan' => 'Konsultasi',
            'bidang' => 'PSDM',
            'nomor_antrian' => 'A-01',
            'tanggal_kunjungan' => now()->toDateString(),
            'status' => 'waiting',
        ]);

        $this->assertSame('Anisa Fitri', Tamu::first()->nama);
    }
}
