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
        Schema::create('registrations', function (Blueprint $table) {
        $table->id();

        // Identitas pendaftaran
        $table->string('nomor_pendaftaran')->unique();
        $table->enum('paket', ['B', 'C']);
        $table->enum('status', ['MENUNGGU', 'DIVERIFIKASI', 'DITERIMA'])->default('MENUNGGU');

        // BIODATA
        $table->string('nama');
        $table->enum('jk', ['L', 'P'])->nullable();
        $table->string('nisn')->nullable();
        $table->string('nik')->nullable();
        $table->string('tempat_lahir')->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->string('agama')->nullable();
        $table->string('hp')->nullable();
        $table->string('email')->nullable();

        // ALAMAT (dipisah biar rapi)
        $table->text('alamat')->nullable();
        $table->string('rt_rw')->nullable();
        $table->string('dusun')->nullable();
        $table->string('kelurahan_desa')->nullable();
        $table->string('kecamatan')->nullable();
        $table->string('kode_pos')->nullable();
        $table->string('jenis_tinggal')->nullable();
        $table->string('transportasi')->nullable();
        $table->string('telepon')->nullable();

        // SEKOLAH & BANTUAN
        $table->string('sekolah_asal')->nullable();
        $table->string('skhun')->nullable();
        $table->string('penerima_kps_kip_pkh')->nullable(); // misal: "KIP", "PKH", dll
        $table->string('non_kps')->nullable();
        $table->text('catatan')->nullable();

        // ORANG TUA
        $table->string('ayah_nama')->nullable();
        $table->string('ayah_tahun_lahir')->nullable();
        $table->string('ayah_pendidikan')->nullable();
        $table->string('ayah_pekerjaan')->nullable();
        $table->string('ayah_penghasilan')->nullable();
        $table->string('ayah_nik')->nullable();

        $table->string('ibu_nama')->nullable();
        $table->string('ibu_tahun_lahir')->nullable();
        $table->string('ibu_pendidikan')->nullable();
        $table->string('ibu_pekerjaan')->nullable();
        $table->string('ibu_penghasilan')->nullable();
        $table->string('ibu_nik')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
