<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to modify enum
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status ENUM('waiting', 'verified', 'accepted') DEFAULT 'waiting'");

        // Update existing data to new format
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

    public function down(): void
    {
        // Use raw SQL to revert enum
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status ENUM('MENUNGGU', 'DIVERIFIKASI', 'DITERIMA') DEFAULT 'MENUNGGU'");

        // Reverse data changes
        DB::table('registrations')
            ->where('status', 'waiting')
            ->update(['status' => 'MENUNGGU']);

        DB::table('registrations')
            ->where('status', 'verified')
            ->update(['status' => 'DIVERIFIKASI']);

        DB::table('registrations')
            ->where('status', 'accepted')
            ->update(['status' => 'DITERIMA']);
    }
};
