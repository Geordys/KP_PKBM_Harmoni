<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, change enum to VARCHAR to allow any values temporarily
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status VARCHAR(20) DEFAULT 'MENUNGGU'");

        // Update existing data back to original format
        DB::statement("UPDATE registrations SET status = 'MENUNGGU' WHERE status = 'waiting'");
        DB::statement("UPDATE registrations SET status = 'DIVERIFIKASI' WHERE status = 'verified'");
        DB::statement("UPDATE registrations SET status = 'DITERIMA' WHERE status = 'accepted'");

        // THEN modify back to enum with correct values
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status ENUM('MENUNGGU', 'DIVERIFIKASI', 'DITERIMA') DEFAULT 'MENUNGGU'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use raw SQL to change back to English enum
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status ENUM('waiting', 'verified', 'accepted') DEFAULT 'waiting'");

        // Update existing data back to English format
        DB::table('registrations')
            ->where('status', 'MENUNGGU')
            ->update(['status' => 'waiting']);

        DB::table('registrations')
            ->where('status', 'DIVERIFIKASI')
            ->update(['status' => 'verified']);

        DB::table('registrations')
            ->where('status', 'DITERIMA')
            ->update(['status' => 'accepted']);
    }
};
