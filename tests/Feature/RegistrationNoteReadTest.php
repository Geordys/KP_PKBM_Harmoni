<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class RegistrationNoteReadTest extends TestCase
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

    public function test_mark_note_read_endpoint_sets_flag()
    {
        // Create a registration with unread note
        $id = DB::table('registrations')->insertGetId([
            'nomor_pendaftaran' => 'PKBM-TEST-0002',
            'nama' => 'Siswa Test2',
            'paket' => 'C',
            'jk' => 'P',
            'tanggal_lahir' => '2004-02-02',
            'alamat' => 'Jl Test2',
            'kelurahan_desa' => 'Desa2',
            'kecamatan' => 'Kec2',
            'sekolah_asal' => 'SMP Test',
            'hp' => '08123456780',
            'catatan' => 'Cek dokumen',
            'catatan_read' => false,
            'status' => 'MENUNGGU',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $res = $this->postJson("/api/registrations/{$id}/note-read");
        $res->assertStatus(200)->assertJson(['success' => true]);

        $row = DB::table('registrations')->where('id', $id)->first();
        $this->assertTrue(isset($row->catatan_read) && ($row->catatan_read == 1 || $row->catatan_read === true));
    }
}
