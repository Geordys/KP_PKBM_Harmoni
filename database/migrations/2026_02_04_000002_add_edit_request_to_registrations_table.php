<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'minta_izin_edit')) {
                $table->boolean('minta_izin_edit')->default(false)->after('catatan_read');
            }
            if (!Schema::hasColumn('registrations', 'edit_allowed')) {
                $table->boolean('edit_allowed')->default(false)->after('minta_izin_edit');
            }
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'edit_allowed')) {
                $table->dropColumn('edit_allowed');
            }
            if (Schema::hasColumn('registrations', 'minta_izin_edit')) {
                $table->dropColumn('minta_izin_edit');
            }
        });
    }
};