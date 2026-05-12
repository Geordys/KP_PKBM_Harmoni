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
    Schema::table('users', function (Blueprint $table) {
        $table->string('username')->unique()->after('id');
        $table->string('password_hash')->nullable()->after('username');
        $table->string('role')->default('USER')->after('password_hash');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['username', 'password_hash', 'role']);
    });
}
};
