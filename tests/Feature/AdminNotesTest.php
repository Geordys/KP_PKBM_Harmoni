<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AdminNotesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create minimal tables for testing (compatible with sqlite)
        if (!Schema::hasTable('registrations')) {
            Schema::create('registrations', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nomor_pendaftaran')->unique();
                $table->string('nama');
                $table->string('paket', 2)->nullable();
                $table->string('jk', 2)->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->text('alamat')->nullable();
                $table->string('kelurahan_desa')->nullable();
                $table->string('kecamatan')->nullable();
                $table->string('sekolah_asal')->nullable();
                $table->string('hp')->nullable();
                $table->text('catatan')->nullable();
                $table->boolean('catatan_read')->default(true);
                $table->string('status')->default('MENUNGGU');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('auth_tokens')) {
            Schema::create('auth_tokens', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->nullable();
                $table->string('token')->unique();
                $table->timestamps();
            });
        }
    }

    public function test_admin_update_sets_catatan_and_marks_unread()
    {
        // Create a registration
        $id = DB::table('registrations')->insertGetId([
            'nomor_pendaftaran' => 'PKBM-TEST-0001',
            'nama' => 'Siswa Test',
            'paket' => 'B',
            'jk' => 'L',
            'tanggal_lahir' => '2005-01-01',
            'alamat' => 'Jl Test',
            'kelurahan_desa' => 'Desa',
            'kecamatan' => 'Kec',
            'sekolah_asal' => 'SMA Test',
            'hp' => '08123456789',
            'status' => 'MENUNGGU',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create auth token for admin
        $token = Str::random(40);
        DB::table('auth_tokens')->insert([
            'user_id' => 1,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Call admin update (PUT)
        $res = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/admin/registrations/PKBM-TEST-0001', [
                'catatan' => 'Mohon lengkapi ijazah',
            ]);

        if ($res->status() !== 200) {
            fwrite(STDERR, "ADMIN UPDATE RESPONSE: " . $res->getContent() . "\n");
        }

        $res->assertStatus(200)->assertJson(['success' => true]);

        $row = DB::table('registrations')->where('id', $id)->first();
        $this->assertEquals('Mohon lengkapi ijazah', $row->catatan);
        $this->assertTrue(isset($row->catatan_read) && ($row->catatan_read == 0 || $row->catatan_read == false));
    }
}
