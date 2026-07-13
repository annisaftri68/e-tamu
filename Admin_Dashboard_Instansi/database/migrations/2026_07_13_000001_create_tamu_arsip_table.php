<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
            $table->timestamps();

            $table->index('created_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tamu_arsip');
    }
};
