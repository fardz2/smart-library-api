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
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string("cover")->nullable();
            $table->string('judul_buku');
            $table->string('penerbit');
            $table->string('pengarang');
            $table->string('sinopsis');
            $table->date('tahun_terbit');
            $table->integer('jumlah_buku');
            $table->string('lokasi_rak_buku');
            $table->string("pdf_buku")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
