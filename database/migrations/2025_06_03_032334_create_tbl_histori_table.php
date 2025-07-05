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
    Schema::create('tbl_histori', function (Blueprint $table) {
    $table->id();
    $table->string('nama_pelanggan');
    $table->string('id_pelanggan');
    $table->timestamp('tanggal_diagnosa');
    $table->json('kode_gangguan'); // array G1, G4, dll
    $table->json('hasil_diagnosa'); // array hasil (kode, solusi, gangguan)
    $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_histori');
    }
};
