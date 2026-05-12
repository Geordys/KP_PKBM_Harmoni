<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class RegistrationStatusNoteIncludedTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

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
    }

    public function test_status_endpoint_returns_catatan_and_flag()
    {
        $id = DB::table('registrations')->insertGetId([
            'nomor_pendaftaran' => 'PKBM-TEST-0003',
            'nama' => 'Siswa Test3',
            'paket' => 'B',
            'jk' => 'L',
            'tanggal_lahir' => '2003-03-03',
            'alamat' => 'Jl Test3',
            'kelurahan_desa' => 'Desa3',
            'kecamatan' => 'Kec3',
            'sekolah_asal' => 'SMA Test3',
            'hp' => '08123456781',
            'catatan' => 'Periksa NIK',
            'catatan_read' => false,
            'status' => 'MENUNGGU',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $res = $this->getJson("/api/registrations/status?registration_id={$id}");
        $res->assertStatus(200)->assertJsonFragment([
            'id' => $id,
            'catatan' => 'Periksa NIK',
            'catatan_read' => false,
        ]);
    }
}
