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
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('no_hp');
            $table->string('jenis_bank')->nullable()->after('telepon');
            $table->string('no_rekening')->nullable()->after('jenis_bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['jenis_bank', 'no_rekening']);
            $table->string('no_hp', 20)->nullable()->after('alamat');
        });
    }
};
