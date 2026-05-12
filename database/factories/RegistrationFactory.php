<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Registration;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nomor_pendaftaran' => 'PKBM-' . date('Ymd') . '-' . str_pad((string)$this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'nama' => $this->faker->name(),
            'paket' => $this->faker->randomElement(['B', 'C']),
            'jk' => $this->faker->randomElement(['L', 'P']),
            'nisn' => $this->faker->optional()->numerify('##########'),
            'nik' => $this->faker->numerify('################'),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '-15 years'),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha']),
            'hp' => $this->faker->phoneNumber(),
            'email' => $this->faker->optional()->email(),
            'alamat' => $this->faker->address(),
            'rt_rw' => $this->faker->numerify('#/##'),
            'dusun' => $this->faker->optional()->word(),
            'kelurahan_desa' => $this->faker->city(),
            'kecamatan' => $this->faker->city(),
            'kode_pos' => $this->faker->optional()->postcode(),
            'jenis_tinggal' => $this->faker->optional()->randomElement(['Bersama orang tua', 'Wali', 'Kos', 'Asrama']),
            'transportasi' => $this->faker->optional()->randomElement(['Jalan kaki', 'Sepeda', 'Sepeda motor', 'Mobil pribadi', 'Angkutan umum']),
            'telepon' => $this->faker->optional()->phoneNumber(),
            'sekolah_asal' => $this->faker->randomElement(['SMP Negeri', 'SMP Swasta', 'SMA Negeri', 'SMA Swasta']) . ' ' . $this->faker->numberBetween(1, 50),
            'skhun' => $this->faker->optional()->numerify('##########'),
            'penerima_kps_kip_pkh' => $this->faker->optional()->randomElement(['Ya', 'Tidak']),
            'catatan' => $this->faker->optional()->sentence(),
            'ayah_nama' => $this->faker->name('male'),
            'ayah_tahun_lahir' => $this->faker->optional()->numberBetween(1960, 1980),
            'ayah_pendidikan' => $this->faker->optional()->randomElement(['SD', 'SMP', 'SMA', 'Diploma', 'Sarjana']),
            'ayah_pekerjaan' => $this->faker->optional()->jobTitle(),
            'ayah_penghasilan' => $this->faker->optional()->randomElement(['< 500.000', '500.000 - 1.000.000', '1.000.000 - 2.000.000', '> 2.000.000']),
            'ayah_nik' => $this->faker->optional()->numerify('################'),
            'ibu_nama' => $this->faker->name('female'),
            'ibu_tahun_lahir' => $this->faker->optional()->numberBetween(1960, 1980),
            'ibu_pendidikan' => $this->faker->optional()->randomElement(['SD', 'SMP', 'SMA', 'Diploma', 'Sarjana']),
            'ibu_pekerjaan' => $this->faker->optional()->jobTitle(),
            'ibu_penghasilan' => $this->faker->optional()->randomElement(['< 500.000', '500.000 - 1.000.000', '1.000.000 - 2.000.000', '> 2.000.000']),
            'ibu_nik' => $this->faker->optional()->numerify('################'),
            'status' => $this->faker->randomElement(['MENUNGGU', 'DITERIMA', 'DITOLAK']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
