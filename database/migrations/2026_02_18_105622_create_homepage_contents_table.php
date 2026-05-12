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
        Schema::create('homepage_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section')->index(); // 'hero', 'about', 'guru', 'program', 'alur', 'galeri', 'faq', 'kontak'
            $table->string('key')->unique();
            $table->string('label')->nullable();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, editor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_contents');
    }
};
