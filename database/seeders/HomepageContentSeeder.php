<?php

namespace Database\Seeders;

use App\Models\HomepageContent;
use Illuminate\Database\Seeder;

class HomepageContentSeeder extends Seeder
{
    public function run()
    {
        $faqItems = [
            [
                'q' => 'Bagaimana cara daftar?',
                'a' => 'Klik tombol Daftar di atas, isi form pendaftaran, lalu siapkan dokumen sesuai syarat. Setelah itu admin akan verifikasi dan status akan diperbarui.',
                'icon' => 'user-plus'
            ],
            [
                'q' => 'Apa bedanya Paket B dan Paket C?',
                'a' => 'Paket B setara SMP, sedangkan Paket C setara SMA. Pilih sesuai kebutuhan pendidikan terakhir.',
                'icon' => 'book'
            ],
            [
                'q' => 'Kalau mau tanya langsung, kontaknya dimana?',
                'a' => 'Scroll ke bawah ke bagian Kontak. Kamu bisa isi nomor WhatsApp, telepon, atau email sesuai data PKBM Harmoni.',
                'icon' => 'phone'
            ]
        ];

        $alurItems = [
            [
                'title' => 'Isi Formulir',
                'desc' => 'Lengkapi data diri calon peserta didik secara online atau offline.'
            ],
            [
                'title' => 'Siapkan Berkas',
                'desc' => 'Siapkan dokumen persyaratan (KK, Akte, Ijazah, Foto).'
            ],
            [
                'title' => 'Verifikasi',
                'desc' => 'Admin memeriksa kelengkapan data dan dokumen Anda.'
            ],
            [
                'title' => 'Diterima',
                'desc' => 'Status diterima dan siap mengikuti kegiatan pembelajaran.'
            ]
        ];

        $contents = [
            // HERO SECTION
            [
                'section' => 'hero',
                'key' => 'hero_badge',
                'label' => 'Badge Hero',
                'value' => 'Pendaftaran Dibuka',
                'type' => 'text',
            ],
            [
                'section' => 'hero',
                'key' => 'hero_title',
                'label' => 'Judul Hero',
                'value' => 'Bergabunglah dengan PKBM Harmoni',
                'type' => 'text',
            ],
            [
                'section' => 'hero',
                'key' => 'hero_desc',
                'label' => 'Deskripsi Hero',
                'value' => 'Wujudkan impian kariermu bersama pendidikan kesetaraan terdepan. Pendidikan berkualitas dengan fasilitas modern dan tenaga pengajar profesional.',
                'type' => 'textarea',
            ],
            [
                'section' => 'hero',
                'key' => 'hero_bg',
                'label' => 'Background Hero',
                'value' => '/assets/foto-pkbm7.jfif',
                'type' => 'image',
            ],

            // TENTANG SECTION
            [
                'section' => 'tentang',
                'key' => 'about_title',
                'label' => 'Judul Tentang',
                'value' => 'Tentang PKBM Harmoni',
                'type' => 'text',
            ],
            [
                'section' => 'tentang',
                'key' => 'about_desc',
                'label' => 'Deskripsi Tentang',
                'value' => "PKBM Harmoni merupakan PKBM yang memiliki siswa terbanyak di Kecamatan Sumbang. Jumlah peserta didik laki-laki dan perempuan yang hampir seimbang memungkinkan dalam pembagian kelas heterogen.\n\nSekolah ini berkomitmen untuk menciptakan lingkungan belajar yang nyaman, aman, serta mendukung siswa dalam menggali potensi diri. Kami percaya bahwa pendidikan adalah investasi masa depan.\n\nLatar belakang peserta didik berada pada tingkat ekonomi menengah ke bawah dengan sarana prasarana yang kurang memadai, namun Profil Pelajar Pancasila mampu diimplementasikan secara utuh di PKBM Harmoni.",
                'type' => 'textarea',
            ],
            // Stats
            ['section' => 'tentang', 'key' => 'stats_siswa_val', 'label' => 'Angka Siswa Aktif', 'value' => '254', 'type' => 'text'],
            ['section' => 'tentang', 'key' => 'stats_siswa_label', 'label' => 'Label Siswa Aktif', 'value' => 'Siswa Aktif', 'type' => 'text'],
            ['section' => 'tentang', 'key' => 'stats_lulus_val', 'label' => 'Angka Kelulusan', 'value' => '100%', 'type' => 'text'],
            ['section' => 'tentang', 'key' => 'stats_lulus_label', 'label' => 'Label Kelulusan', 'value' => 'Kelulusan', 'type' => 'text'],
            ['section' => 'tentang', 'key' => 'stats_guru_val', 'label' => 'Angka Guru', 'value' => '13', 'type' => 'text'],
            ['section' => 'tentang', 'key' => 'stats_guru_label', 'label' => 'Label Guru', 'value' => 'Guru & Tendik', 'type' => 'text'],
            ['section' => 'tentang', 'key' => 'stats_gratis_val', 'label' => 'Angka Gratis', 'value' => '100%', 'type' => 'text'],
            ['section' => 'tentang', 'key' => 'stats_gratis_label', 'label' => 'Label Gratis', 'value' => 'Gratis', 'type' => 'text'],
            // Visi Misi
            ['section' => 'tentang', 'key' => 'visi', 'label' => 'Visi', 'value' => '"Visi: Berkarakter, Tanggungjawab dan Mandiri."', 'type' => 'text'],
            ['section' => 'tentang', 'key' => 'misi', 'label' => 'Misi', 'value' => 'Misi kami menanamkan kepribadian yang mantap, dinamis, kritis, inovatif, dan budi pekerti yang luhur serta menciptakan iklim kondusif guna menumbuhkembangkan pendidikan yang berkarakter.', 'type' => 'textarea'],

            // PROFIL GURU SECTION
            ['section' => 'guru', 'key' => 'guru_badge', 'label' => 'Badge Guru', 'value' => 'Profil Guru', 'type' => 'text'],
            ['section' => 'guru', 'key' => 'guru_title', 'label' => 'Judul Guru', 'value' => 'Guru & Tenaga Pendidik', 'type' => 'text'],
            ['section' => 'guru', 'key' => 'guru_subtitle', 'label' => 'Subjudul Guru', 'value' => 'Mengenal Lebih Dekat Pendidik Inspiratif Kami', 'type' => 'text'],
            [
                'section' => 'guru',
                'key' => 'guru_desc',
                'label' => 'Deskripsi Guru',
                'value' => "PKBM Harmoni didukung oleh tim tenaga pengajar yang kompeten, berpengalaman, dan memiliki dedikasi tinggi dalam dunia pendidikan kesetaraan. Kami terus berupaya menciptakan suasana belajar yang inklusif dan menyenangkan.\n\nTidak hanya berfokus pada akademik, kami juga menanamkan pendidikan karakter. Dengan pendekatan yang personal, para tutor siap membimbing setiap warga belajar untuk menggali potensi terbaik mereka.",
                'type' => 'textarea'
            ],

            // PROGRAM SECTION
            ['section' => 'program', 'key' => 'program_badge', 'label' => 'Badge Program', 'value' => 'Program', 'type' => 'text'],
            ['section' => 'program', 'key' => 'program_title', 'label' => 'Judul Program', 'value' => 'Program Pendidikan Unggulan', 'type' => 'text'],
            ['section' => 'program', 'key' => 'program_desc', 'label' => 'Deskripsi Program', 'value' => 'PKBM Harmoni menerapkan Kurikulum Merdeka (Tahun Pelajaran 2026/2027) yang mencakup Intrakurikuler, Projek Penguatan Profil Pelajar Pancasila, Ekstrakurikuler, dan Aktualisasi Budaya Positif.', 'type' => 'textarea'],
            
            // Paket B
            ['section' => 'program', 'key' => 'paket_b_title', 'label' => 'Judul Paket B', 'value' => 'Program Paket B', 'type' => 'text'],
            ['section' => 'program', 'key' => 'paket_b_desc', 'label' => 'Deskripsi Paket B', 'value' => 'Setara SMP. Menggunakan Kurikulum Merdeka (Fase D) untuk Kelas VII, VIII, dan IX. Pendekatan mata pelajaran yang terstruktur.', 'type' => 'textarea'],
            ['section' => 'program', 'key' => 'paket_b_umum', 'label' => 'Detail Umum Paket B', 'value' => 'Kelompok Umum: PAI, PKn, B.Indo, B.Inggris, MTK, IPA, IPS', 'type' => 'text'],
            ['section' => 'program', 'key' => 'paket_b_khusus', 'label' => 'Detail Khusus Paket B', 'value' => 'Kelompok Khusus: Pemberdayaan, Keterampilan Wajib & Pilihan', 'type' => 'text'],
            ['section' => 'program', 'key' => 'paket_b_pengembangan', 'label' => 'Pengembangan Diri Paket B', 'value' => 'Pengembangan Diri: Seni Musik & TIK (Komputer Digital)', 'type' => 'text'],
            ['section' => 'program', 'key' => 'paket_b_syarat', 'label' => 'Syarat Paket B', 'value' => 'Gratis & Syarat Mudah: KK, Akte, Ijazah SD', 'type' => 'text'],

            // Paket C
            ['section' => 'program', 'key' => 'paket_c_title', 'label' => 'Judul Paket C', 'value' => 'Program Paket C', 'type' => 'text'],
            ['section' => 'program', 'key' => 'paket_c_desc', 'label' => 'Deskripsi Paket C', 'value' => 'Setara SMA. Implementasi Kurikulum Merdeka Fase E (Kelas X) & Fase F (Kelas XI, XII). Siap kuliah atau kerja.', 'type' => 'textarea'],
            ['section' => 'program', 'key' => 'paket_c_umum', 'label' => 'Detail Umum Paket C', 'value' => 'Kelompok Umum: PAI, PKn, B.Indo, MTK, Sejarah, B.Inggris', 'type' => 'text'],
            ['section' => 'program', 'key' => 'paket_c_ips', 'label' => 'Detail Peminatan Paket C', 'value' => 'Peminatan IPS: Geografi, Sejarah, Sosiologi, Ekonomi', 'type' => 'text'],
            ['section' => 'program', 'key' => 'paket_c_pengembangan', 'label' => 'Pengembangan Diri Paket C', 'value' => 'Pengembangan Diri: Seni Musik & TIK (Komputer)', 'type' => 'text'],
            ['section' => 'program', 'key' => 'paket_c_syarat', 'label' => 'Syarat Paket C', 'value' => 'Gratis & Syarat Mudah: KK, Akte, Ijazah SMP', 'type' => 'text'],

            // ALUR SECTION
            ['section' => 'alur', 'key' => 'alur_badge', 'label' => 'Badge Alur', 'value' => 'Alur', 'type' => 'text'],
            ['section' => 'alur', 'key' => 'alur_title', 'label' => 'Judul Alur', 'value' => 'Alur Pendaftaran', 'type' => 'text'],
            ['section' => 'alur', 'key' => 'alur_desc', 'label' => 'Deskripsi Alur', 'value' => 'Ikuti langkah-langkah mudah berikut untuk bergabung menjadi warga belajar di PKBM Harmoni.', 'type' => 'text'],
            [
                'section' => 'alur',
                'key' => 'alur_items',
                'label' => 'Daftar Alur (JSON)',
                'value' => json_encode($alurItems),
                'type' => 'textarea'
            ],

            // GALERI SECTION
            ['section' => 'galeri', 'key' => 'galeri_badge', 'label' => 'Badge Galeri', 'value' => 'Galeri', 'type' => 'text'],
            ['section' => 'galeri', 'key' => 'galeri_title', 'label' => 'Judul Galeri', 'value' => 'Program & Kegiatan Unggulan', 'type' => 'text'],
            ['section' => 'galeri', 'key' => 'galeri_desc', 'label' => 'Deskripsi Galeri', 'value' => 'Kami menghadirkan berbagai program pendidikan dan kegiatan pengembangan diri untuk membentuk siswa yang berprestasi dan berkarakter.', 'type' => 'text'],

            // FAQ SECTION
            ['section' => 'faq', 'key' => 'faq_badge', 'label' => 'Badge FAQ', 'value' => 'FAQ', 'type' => 'text'],
            ['section' => 'faq', 'key' => 'faq_title', 'label' => 'Judul FAQ', 'value' => 'Pertanyaan Sering Diajukan', 'type' => 'text'],
            ['section' => 'faq', 'key' => 'faq_desc', 'label' => 'Deskripsi FAQ', 'value' => 'Punya pertanyaan seputar PKBM Harmoni? Cari jawaban cepat di sini.', 'type' => 'text'],
            // FAQ Dynamic Array
            [
                'section' => 'faq',
                'key' => 'faq_items',
                'label' => 'Daftar FAQ (JSON)',
                'value' => json_encode($faqItems),
                'type' => 'textarea'
            ],

            // KONTAK SECTION
            ['section' => 'kontak', 'key' => 'kontak_badge', 'label' => 'Badge Kontak', 'value' => 'Kontak', 'type' => 'text'],
            ['section' => 'kontak', 'key' => 'kontak_title', 'label' => 'Judul Kontak', 'value' => 'Pusat Layanan & Informasi', 'type' => 'text'],
            ['section' => 'kontak', 'key' => 'kontak_desc', 'label' => 'Deskripsi Kontak', 'value' => 'Kami siap membantu Anda setiap hari kerja. Jangan ragu untuk menghubungi kami untuk info lebih lanjut.', 'type' => 'text'],
            
            // Card WhatsApp
            ['section' => 'kontak', 'key' => 'kontak_wa', 'label' => 'Nomor WhatsApp', 'value' => '6285875978865', 'type' => 'text'],
            ['section' => 'kontak', 'key' => 'kontak_wa_desc', 'label' => 'Deskripsi Card WhatsApp', 'value' => 'Hubungi admin PKBM Harmoni melalui WhatsApp untuk informasi pendaftaran atau pertanyaan lainnya.', 'type' => 'textarea'],
            ['section' => 'kontak', 'key' => 'kontak_jam', 'label' => 'Jam Operasional', 'value' => "Senin - Jumat: 08:00 - 16:00 WIB\nSabtu: 08:00 - 12:00 WIB\nMinggu: Tutup", 'type' => 'textarea'],
            ['section' => 'kontak', 'key' => 'kontak_wa_note', 'label' => 'Catatan Response WA', 'value' => 'Admin akan merespons dalam 1-2 jam pada jam operasional', 'type' => 'text'],

            // Card Lokasi
            ['section' => 'kontak', 'key' => 'kontak_lokasi_desc', 'label' => 'Deskripsi Card Lokasi', 'value' => 'Lokasi PKBM Harmoni untuk kunjungan atau informasi lebih lanjut.', 'type' => 'textarea'],
            ['section' => 'kontak', 'key' => 'kontak_alamat', 'label' => 'Alamat Fisik', 'value' => 'Kotayasa RT 006 RW 006, Kecamatan Sumbang, Kabupaten Banyumas, Jawa Tengah', 'type' => 'textarea'],
            ['section' => 'kontak', 'key' => 'kontak_maps', 'label' => 'Link Google Maps', 'value' => 'https://maps.google.com/maps?q=Kotayasa+RT+006+RW+006,+Kecamatan+Sumbang,+Kabupaten+Banyumas,+Jawa+Tengah,+Indonesia', 'type' => 'text'],
            ['section' => 'kontak', 'key' => 'kontak_maps_embed', 'label' => 'URL Embed Google Maps', 'value' => 'https://maps.google.com/maps?q=Kotayasa%20RT%20006%20RW%20006%2C%20Sumbang%2C%20Banyumas%2C%20Jawa%20Tengah&t=&z=15&ie=UTF8&iwloc=&output=embed', 'type' => 'textarea'],

            // Card Bantuan
            ['section' => 'kontak', 'key' => 'kontak_email', 'label' => 'Alamat Email', 'value' => 'pkbmharmoni116@gmail.com', 'type' => 'text'],
            ['section' => 'kontak', 'key' => 'kontak_bantuan_desc', 'label' => 'Deskripsi Card Bantuan', 'value' => 'Kirim pertanyaan atau keluhan pendaftaran melalui email ke admin PKBM Harmoni.', 'type' => 'textarea'],
            ['section' => 'kontak', 'key' => 'kontak_bantuan_list', 'label' => 'Daftar Bantuan', 'value' => "Keluhan pendaftaran online\nPertanyaan program Paket B/C\nKonfirmasi status pendaftaran\nInformasi jadwal belajar", 'type' => 'textarea'],
            ['section' => 'kontak', 'key' => 'kontak_bantuan_note', 'label' => 'Catatan Support Email', 'value' => 'Email akan diproses dalam 1-2 hari kerja', 'type' => 'text'],
        ];

        foreach ($contents as $content) {
            HomepageContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }
        
        // Bersihkan key lama jika ada (opsional tapi bagus untuk kebersihan)
        HomepageContent::whereIn('key', ['faq_1_q', 'faq_1_a', 'faq_2_q', 'faq_2_a', 'faq_3_q', 'faq_3_a'])->delete();
    }
}
