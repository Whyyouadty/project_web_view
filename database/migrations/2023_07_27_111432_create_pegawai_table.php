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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('user');
            $table->string('nama');
            $table->string('nidn');
            $table->foreignId('departement_id')->references('id')->on('departement');
            $table->foreignId('jabatan_id')->references('id')->on('jabatan');
            $table->string('ttl');
            $table->string('alamat');
            $table->string('agama');
            $table->string('jk');
            $table->string('no_hp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
