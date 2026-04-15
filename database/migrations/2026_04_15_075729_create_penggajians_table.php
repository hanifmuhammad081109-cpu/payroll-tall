<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->integer('bulan', 2);
            $table->integer('tahun', 4);
            $table->date('tanggal_proses');
            $table->integer('gaji_pokok');
            $table->integer('potongan');
            $table->integer('tunjangan');
            $table->integer('total_gaji');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
