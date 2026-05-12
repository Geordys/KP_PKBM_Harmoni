<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'registrations';

    protected $fillable = [
        'nomor_pendaftaran',
        'nama',
        'paket',
        'jk',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'hp',
        'email',
        'alamat',
        'rt_rw',
        'dusun',
        'kelurahan_desa',
        'kecamatan',
        'kode_pos',
        'jenis_tinggal',
        'transportasi',
        'telepon',
        'jenis_bank',
        'no_rekening',
        'sekolah_asal',
        'skhun',
        'penerima_kps_kip_pkh',
        'catatan',
        'catatan_read',
        'minta_izin_edit',
        'edit_allowed',
        'ayah_nama',
        'ayah_tahun_lahir',
        'ayah_pendidikan',
        'ayah_pekerjaan',
        'ayah_penghasilan',
        'ayah_nik',
        'ibu_nama',
        'ibu_tahun_lahir',
        'ibu_pendidikan',
        'ibu_pekerjaan',
        'ibu_penghasilan',
        'ibu_nik',
        'status',
    ];
}
