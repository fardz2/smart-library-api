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
        Schema::create('buku_yang_dipinjam', function (Blueprint $table) {
            $table->id();
            $table->string('peminjaman_id');
            $table->foreign('peminjaman_id')->references('peminjaman_id')->on('peminjaman')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('buku_id');
            $table->foreignId('user_id')->constrained();
            $table->boolean('status')->default(false);
            $table->foreign('buku_id')->references('id')->on('buku')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_yang_dipinjam');
    }
};
