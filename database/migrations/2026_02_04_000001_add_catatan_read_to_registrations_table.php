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
        if (!Schema::hasColumn('registrations', 'catatan_read')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->boolean('catatan_read')->default(true)->after('catatan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('registrations', 'catatan_read')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->dropColumn('catatan_read');
            });
        }
    }
};
