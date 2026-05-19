@extends('layouts.app')

@section('title', 'PKBM Harmoni - Beranda Calon Siswa')

@section('content')
    <style>
        /* Standarisasi Gelombang */
        .section-divider {
            position: absolute;
            bottom: -5px;
            /* Overlap lebih dalam untuk menghilangkan celah */
            left: 0;
            width: 100%;
            height: 60px;
            overflow: hidden;
            line-height: 0;
            z-index: -1;
            /* Moved behind everything to avoid any interaction issues */
            pointer-events: none;
        }

        .section-divider svg {
            position: relative;
            display: block;
            width: 100%;
            height: 100%;
        }

        /* Jarak antar seksi yang benar-benar merapat */
        section.block {
            padding: 0 0 80px 0 !important;
            margin-top: 0 !important;
            /* Padding atas 0 agar benar-benar merapat ke gelombang */
            position: relative;
        }

        /* Memberikan sedikit ruang bernapas untuk judul konten agar tidak menempel banget ke SVG */
        section.block .container {
            padding-top: 40px;
        }

        /* Penyesuaian Hero agar transisi mulus */
        .hero {
            position: relative;
            overflow: hidden;
            padding-bottom: 60px;
        }

        .container {
            position: relative;
            z-index: 2;
        }

        /* Guru & Galeri Grid fixes */
        .guru-card {
            transition: transform 0.3s ease;
        }

        .guru-card:hover {
            transform: translateY(-5px);
        }

        @keyframes modalSlideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <!-- HERO (Hanya latar belakang) -->
    <div class="hero" id="homeSection"
        style="background-image: url('{{ asset('assets/foto-pkbm7.jfif') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height:560px; display:flex; align-items:center;">
        <div class="container" style="text-align:center; color:white;">

            <span id="heroBadge"
                style="display:inline-block; padding: 10px 20px; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border-radius: 50px; border: 1px solid rgba(255,255,255,0.3); font-size: 14px; font-weight: 600; margin-bottom: 24px; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                Pendaftaran Dibuka
            </span>

            <h1 id="heroTitle" class="hero-title"
                style="font-size: 48px; font-weight: 800; line-height: 1.2; margin-bottom: 20px;">
                Bergabunglah dengan <br>PKBM Harmoni
            </h1>

            <p id="heroDesc"
                style="font-size: 18px; opacity: 0.95; max-width: 640px; margin: 0 auto 40px; text-shadow: 0 2px 10px rgba(0,0,0,0.5); line-height: 1.6; font-weight: 400;">
                Wujudkan impian kariermu bersama pendidikan kesetaraan terdepan. Pendidikan berkualitas dengan fasilitas
                modern dan tenaga pengajar profesional.
            </p>

            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                @guest
                    <a href="{{ url('/login') }}" id="heroDaftarBtn" class="btn primary"
                        style="background: #2563eb; color: #fff; border:none; padding: 14px 36px; font-size: 16px; border-radius: 12px; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);">
                        Daftar Sekarang
                    </a>
                @else
                    <a href="{{ url('/pendaftaran') }}" id="heroDaftarBtn" class="btn primary"
                        style="background: #2563eb; color: #fff; border:none; padding: 14px 36px; font-size: 16px; border-radius: 12px; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);">
                        @if (auth()->user()->registrations()->exists())
                            Cek Status Pendaftaran
                        @else
                            Mulai Pendaftaran
                        @endif
                    </a>
                @endguest
            </div>
        </div>
        <!-- Gelombang (Hero to Tentang) -->
        <div class="section-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C0,0,10.21,41.92,45.63,56.71,118.84,87.27,222.19,74.78,321.39,56.44Z"
                    fill="#ffffff"></path>
            </svg>
        </div>
    </div>

    <!-- TENTANG -->
    <section class="block" id="tentang" style="background: #ffffff;">
        <div class="container">
            <div class="card about-card"
                style="padding: 40px; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); background: white;">
                <div class="about-grid">
                    <!-- KOLOM KIRI: Konten -->
                    <div style="display: flex; flex-direction: column; justify-content: center;">
                        <h3 id="aboutTitle"
                            style="font-size: 28px; font-weight: 800; color: #333; margin-bottom: 24px; line-height: 1.3; text-transform: none; letter-spacing: -1px;">
                            Tentang <span style="color: #2563eb;">PKBM Harmoni</span>
                        </h3>

                        <div id="aboutDesc" style="font-size: 15px; line-height: 1.7; color: #475569; margin-bottom: 30px;">
                            <p style="margin-bottom: 16px;">
                                <b>PKBM Harmoni</b> merupakan PKBM yang memiliki siswa terbanyak di Kecamatan Sumbang.
                                Jumlah
                                peserta didik laki-laki dan perempuan yang hampir seimbang memungkinkan dalam pembagian
                                kelas
                                heterogen.
                            </p>
                            <p style="margin-bottom: 16px;">
                                Sekolah ini berkomitmen untuk menciptakan lingkungan belajar yang nyaman, aman, serta
                                mendukung
                                siswa dalam menggali potensi diri. Kami percaya bahwa pendidikan adalah investasi masa
                                depan.
                            </p>
                            <p style="margin-bottom: 0;">
                                Latar belakang peserta didik berada pada tingkat ekonomi menengah ke bawah dengan sarana
                                prasarana yang
                                kurang memadai, namun <b>Profil Pelajar Pancasila</b> mampu diimplementasikan secara utuh di
                                PKBM
                                Harmoni.
                            </p>
                        </div>

                        <!-- STATISTIK -->
                        <div class="stats-container"
                            style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                            <div style="text-align: center;">
                                <div id="statsSiswaVal" style="font-size: 24px; font-weight: 800; color: #2563eb;">254</div>
                                <div id="statsSiswaLabel"
                                    style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">
                                    Siswa Aktif</div>
                            </div>
                            <div style="text-align: center;">
                                <div id="statsLulusVal" style="font-size: 24px; font-weight: 800; color: #16a34a;">100%
                                </div>
                                <div id="statsLulusLabel"
                                    style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">
                                    Kelulusan</div>
                            </div>
                            <div style="text-align: center;">
                                <div id="statsGuruVal" style="font-size: 24px; font-weight: 800; color: #f59e0b;">13</div>
                                <div id="statsGuruLabel"
                                    style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">
                                    Guru & Tendik</div>
                            </div>
                            <div style="text-align: center;">
                                <div id="statsGratisVal" style="font-size: 24px; font-weight: 800; color: #9333ea;">100%
                                </div>
                                <div id="statsGratisLabel"
                                    style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase;">
                                    Gratis</div>
                            </div>
                        </div>

                        <!-- KUTIPAN VISI MISI -->
                        <div class="visi-misi-block"
                            style="border-left: 4px solid #2563eb; padding-left: 20px; margin-bottom: 30px;">
                            <p id="visiContent"
                                style="font-size: 16px; font-style: italic; color: #444; font-weight: 500; margin-bottom: 12px; line-height: 1.6;">
                                "Visi: Berkarakter, Tanggungjawab dan Mandiri."
                            </p>
                            <p id="misiContent" style="font-size: 14px; color: #64748b; line-height: 1.6;">
                                Misi kami menanamkan kepribadian yang mantap, dinamis, kritis, inovatif, dan budi pekerti
                                yang luhur
                                serta menciptakan iklim kondusif guna menumbuhkembangkan pendidikan yang berkarakter.
                            </p>
                        </div>

                        <div>
                            <a href="#kontak" class="btn primary"
                                style="background: #2563eb; border: none; padding: 12px 24px; font-size: 14px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); border-radius: 8px; color: white; display: inline-block; text-decoration: none;">
                                Info Selengkapnya →
                            </a>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Gambar -->
                    <div style="height: 100%; display: flex; align-items: center; justify-content: center; width: 100%;">
                        <div class="poster-container"
                            style="border-radius: 12px; overflow: hidden; width: 100%; max-width: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                            <img src="{{ asset('assets/poster-pkbm-new.jfif') }}" alt="Suasana Belajar"
                                style="width: 100%; height: auto; display: block;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gelombang (Tentang to Profil Guru) -->
        <div class="section-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C0,0,10.21,41.92,45.63,56.71,118.84,87.27,222.19,74.78,321.39,56.44Z"
                    fill="#f1f5f9"></path>
            </svg>
        </div>
    </section>

    <!-- PROFIL GURU -->
    <section class="block" id="profil-guru" style="background: #f1f5f9;">
        <div class="container">
            <div style="text-align:center; max-width:800px; margin:0 auto 40px;">
                <span id="guruBadge"
                    style="display:inline-block; background:#dbeafe; color:#1d4ed8; padding:8px 24px; border-radius:50px; font-weight:700; font-size:16px; margin-bottom:16px;">Profil
                    Guru</span>
                <h2 id="guruTitle" style="font-size: 32px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Guru &
                    Tenaga Pendidik
                </h2>
                <h3 id="guruSubtitle" style="font-size: 18px; font-weight: 400; color: #64748b; line-height: 1.6;">Mengenal
                    Lebih Dekat
                    Pendidik Inspiratif Kami</h3>
            </div>

            <div class="card"
                style="padding: 40px; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);">

                <!-- Foto Group & Deskripsi Umum -->
                <div id="guruGeneralContent" style="margin-bottom: 50px;">
                    <div
                        style="width: 100%; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1); margin-bottom: 35px;">
                        <img id="imgGuruGroup" src="{{ asset('assets/guru-pkbm.jpg') }}" alt="Tenaga Pendidik PKBM Harmoni"
                            style="width: 100%; height: auto; max-height: 500px; object-fit: cover; display: block;">
                    </div>
                    <div style="max-width: 900px; margin: 0 auto; text-align: center;">
                        <div id="textGuruDesc" style="color: #475569; line-height: 1.8; font-size: 16px;">
                            <p style="margin-bottom: 20px;">PKBM Harmoni didukung oleh tim tenaga pengajar yang kompeten,
                                berpengalaman, dan memiliki dedikasi tinggi dalam dunia pendidikan kesetaraan. Kami terus
                                berupaya menciptakan suasana belajar yang inklusif dan menyenangkan.</p>
                            <p>Tidak hanya berfokus pada akademik, kami juga menanamkan pendidikan karakter. Dengan
                                pendekatan yang personal, para tutor siap membimbing setiap warga belajar untuk menggali
                                potensi terbaik mereka.</p>
                        </div>
                    </div>
                </div>

                <div id="loadingGuru" style="text-align:center; color:#64748b; margin: 40px 0;">Memuat data guru...</div>
                <div id="staffGrid" class="staff-grid"
                    style="display: flex; justify-content: center; gap: 24px; flex-wrap: wrap;"></div>

                <div style="text-align: center; margin-top: 40px;">
                    <button id="btnLihatGuru" class="btn primary" onclick="openGuruModal()"
                        style="display:none; padding: 12px 32px; font-size: 15px; border-radius: 50px; background: #2563eb;">
                        Lihat Guru Selengkapnya
                    </button>
                </div>
            </div>
        </div>
        <!-- Gelombang (Guru to Program) -->
        <div class="section-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C0,0,10.21,41.92,45.63,56.71,118.84,87.27,222.19,74.78,321.39,56.44Z"
                    fill="#ffffff"></path>
            </svg>
        </div>

        <!-- Guru Modal V3 (Inside Content Section) -->
        <div id="guruModalV3"
            style="display:none; position: fixed; inset: 0; z-index: 999999; background: rgba(0,0,0,0.85); align-items: center; justify-content: center; padding: 20px;">
            <div
                style="background: white; width: 100%; max-width: 1000px; max-height: 90vh; border-radius: 24px; display: flex; flex-direction: column; position: relative; box-shadow: 0 25px 50px rgba(0,0,0,0.5);">
                <div
                    style="padding: 20px 30px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: #1e293b;">Seluruh Guru & Tenaga Pendidik
                    </h3>
                    <button onclick="closeGuruModal()"
                        style="background: #f1f5f9; border: none; width: 40px; height: 40px; border-radius: 50%; font-size: 24px; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center;">&times;</button>
                </div>
                <div id="staffGridFullV3" class="staff-grid"
                    style="padding: 20px; overflow-y: auto; flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; justify-items: center;">
                </div>
            </div>
        </div>
    </section>

    <!-- PROGRAM -->
    <section class="block" id="program" style="background: white;">
        <div class="container">
            <div style="text-align:center; max-width:800px; margin:0 auto 50px;">
                <span id="programBadge"
                    style="display:inline-block; background:#ecfdf5; color:#059669; padding:8px 24px; border-radius:50px; font-weight:700; font-size:16px; margin-bottom:20px;">Program</span>
                <h2 id="programTitle" style="font-size:36px; font-weight:800; color:#1e293b; margin-bottom:20px;">Program
                    Pendidikan Unggulan
                </h2>
                <p id="programDesc" style="color:#64748b; line-height:1.8; font-size: 16px;">
                    PKBM Harmoni menerapkan <b>Kurikulum Merdeka</b> (Tahun Pelajaran 2026/2027) yang mencakup
                    Intrakurikuler, Projek Penguatan Profil Pelajar Pancasila, Ekstrakurikuler, dan Aktualisasi Budaya
                    Positif.
                </p>
            </div>

            <div class="grid2"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
                <!-- Paket B -->
                <div class="card"
                    style="padding:40px; border:1px solid #e2e8f0; border-radius:20px; box-shadow:0 10px 40px -10px rgba(0,0,0,0.05); display: flex; flex-direction: column;">
                    <h3 id="paketBTitle"
                        style="font-size:22px; font-weight:800; color:#1e293b; margin-bottom:12px; text-align: center; min-height: 54px; display: flex; align-items: center; justify-content: center;">
                        Program Paket B</h3>
                    <p id="paketBDesc" style="color:#64748b; margin-bottom:24px; text-align: center; min-height: 80px;">
                        Setara SMP. Menggunakan <b>Kurikulum Merdeka (Fase D)</b> untuk Kelas VII, VIII, dan IX. Pendekatan
                        mata pelajaran yang terstruktur.
                    </p>
                    <ul style="list-style: none; padding: 0; margin-bottom: 30px; font-size: 14px; color: #475569;">
                        <li style="display: flex; align-items: start; margin-bottom: 12px; min-height: 50px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3"
                                style="min-width:18px; margin-right: 12px; margin-top: 3px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span id="paketBUmum"><b>Kelompok Umum:</b> PAI, PKn, B.Indo, B.Inggris, MTK, IPA, IPS</span>
                        </li>
                        <li style="display: flex; align-items: start; margin-bottom: 12px; min-height: 50px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3"
                                style="min-width:18px; margin-right: 12px; margin-top: 3px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span id="paketBKhusus"><b>Kelompok Khusus:</b> Pemberdayaan, Keterampilan Wajib &
                                Pilihan</span>
                        </li>
                        <li style="display: flex; align-items: start; margin-bottom: 12px; min-height: 50px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3"
                                style="min-width:18px; margin-right: 12px; margin-top: 3px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span id="paketBPengembangan"><b>Pengembangan Diri:</b> Seni Musik & TIK (Komputer
                                Digital)</span>
                        </li>
                        <li style="display: flex; align-items: start; margin-bottom: 12px; min-height: 50px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3"
                                style="min-width:18px; margin-right: 12px; margin-top: 3px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span id="paketBSyarat"><b>Gratis & Syarat:</b> KK, Akte, Ijazah SD</span>
                        </li>
                    </ul>

                </div>
                <!-- Paket C -->
                <div class="card"
                    style="padding:40px; border:1px solid #e2e8f0; border-radius:20px; box-shadow:0 10px 40px -10px rgba(0,0,0,0.05); display: flex; flex-direction: column;">
                    <h3 id="paketCTitle"
                        style="font-size:22px; font-weight:800; color:#1e293b; margin-bottom:12px; text-align: center; min-height: 54px; display: flex; align-items: center; justify-content: center;">
                        Program Paket C</h3>
                    <p id="paketCDesc" style="color:#64748b; margin-bottom:24px; text-align: center; min-height: 80px;">
                        Setara SMA. Implementasi <b>Kurikulum Merdeka</b> Fase E (Kelas X) & Fase F (Kelas XI, XII). Siap
                        kuliah atau kerja.
                    </p>
                    <ul style="list-style: none; padding: 0; margin-bottom: 30px; font-size: 14px; color: #475569;">
                        <li style="display: flex; align-items: start; margin-bottom: 12px; min-height: 50px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3"
                                style="min-width:18px; margin-right: 12px; margin-top: 3px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span id="paketCUmum"><b>Kelompok Umum:</b> PAI, PKn, B.Indo, MTK, Sejarah, B.Inggris</span>
                        </li>
                        <li style="display: flex; align-items: start; margin-bottom: 12px; min-height: 50px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3"
                                style="min-width:18px; margin-right: 12px; margin-top: 3px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span id="paketCIps"><b>Peminatan IPS:</b> Geografi, Sejarah, Sosiologi, Ekonomi</span>
                        </li>
                        <li style="display: flex; align-items: start; margin-bottom: 12px; min-height: 50px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3"
                                style="min-width:18px; margin-right: 12px; margin-top: 3px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span id="paketCPengembangan"><b>Pengembangan Diri:</b> Seni Musik & TIK (Komputer)</span>
                        </li>
                        <li style="display: flex; align-items: start; margin-bottom: 12px; min-height: 50px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3"
                                style="min-width:18px; margin-right: 12px; margin-top: 3px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span id="paketCSyarat"><b>Gratis & Syarat:</b> KK, Akte, Ijazah SMP</span>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <!-- Gelombang (Program to Alur) -->
        <div class="section-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C0,0,10.21,41.92,45.63,56.71,118.84,87.27,222.19,74.78,321.39,56.44Z"
                    fill="#f1f5f9"></path>
            </svg>
        </div>
    </section>

    <!-- ALUR -->
    <section class="block" id="alur" style="background: #f1f5f9;">
        <div class="container">
            <div style="text-align:center; max-width:800px; margin:0 auto 50px;">
                <span id="alurBadge"
                    style="display:inline-block; background:#f3e8ff; color:#7e22ce; padding:8px 24px; border-radius:50px; font-weight:700; font-size:16px; margin-bottom:20px;">Alur</span>
                <h2 id="alurTitle" style="font-size:36px; font-weight:800; color:#1e293b; margin-bottom: 20px;">Alur
                    Pendaftaran</h2>
                <p id="alurDesc" style="color:#64748b; line-height:1.8; font-size: 16px;">
                    Ikuti langkah-langkah mudah berikut untuk bergabung menjadi warga belajar di PKBM Harmoni.
                </p>
            </div>
            <div id="alurGrid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:30px;">
                <!-- Items will be loaded via JS -->
            </div>
        </div>
        <!-- Gelombang (Alur to Galeri) -->
        <div class="section-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C0,0,10.21,41.92,45.63,56.71,118.84,87.27,222.19,74.78,321.39,56.44Z"
                    fill="#ffffff"></path>
            </svg>
        </div>
    </section>

    <!-- GALERI -->
    <section class="block" id="galeri" style="background: white;">
        <div class="container">
            <div style="text-align:center; max-width:800px; margin:0 auto 50px;">
                <span id="galeriBadge"
                    style="display:inline-block; background:#fff1f2; color:#be123c; padding:8px 24px; border-radius:50px; font-weight:700; font-size:16px; margin-bottom:20px;">Galeri</span>
                <h2 id="galeriTitle" style="font-size:36px; font-weight:800; color:#1e293b;">Program & Kegiatan Unggulan
                </h2>
                <p id="galeriDesc" style="color:#64748b; line-height:1.8; font-size: 16px;">
                    Kami menghadirkan berbagai program pendidikan dan kegiatan pengembangan diri untuk membentuk siswa yang
                    berprestasi dan berkarakter.
                </p>
            </div>
            <div id="galleryGrid" class="gallery-grid"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;"></div>
            <div style="text-align: center; margin-top: 40px;">
                <button id="btnLihatGaleri" class="btn primary" onclick="openGalleryModal()"
                    style="display:none; padding: 12px 32px; font-size: 15px; border-radius: 50px; background: #2563eb; color: white;">Lihat
                    Galeri Selengkapnya</button>
            </div>
        </div>
        <!-- Gelombang (Galeri to FAQ) -->
        <div class="section-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C0,0,10.21,41.92,45.63,56.71,118.84,87.27,222.19,74.78,321.39,56.44Z"
                    fill="#f1f5f9"></path>
            </svg>
        </div>

        <!-- Galeri Modal V3 (Inside Content Section) -->
        <div id="galleryModalV3"
            style="display:none; position: fixed; inset: 0; z-index: 999998; background: rgba(0,0,0,0.85); align-items: center; justify-content: center; padding: 20px;">
            <div
                style="background: white; width: 100%; max-width: 1000px; max-height: 90vh; border-radius: 24px; display: flex; flex-direction: column; position: relative; box-shadow: 0 25px 50px rgba(0,0,0,0.5);">
                <div
                    style="padding: 20px 30px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: #1e293b;">Galeri Kegiatan & Fasilitas
                    </h3>
                    <button onclick="closeGalleryModal()"
                        style="background: #f1f5f9; border: none; width: 40px; height: 40px; border-radius: 50%; font-size: 24px; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center;">&times;</button>
                </div>
                <div id="galleryGridFullV3"
                    style="padding: 30px; overflow-y: auto; flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; justify-items: center; min-height: 200px;">
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="block" id="faq" style="background: #f1f5f9;">
        <div class="container">
            <div style="text-align:center; max-width:800px; margin:0 auto 50px;">
                <span id="faqBadge"
                    style="display:inline-block; background:#ecfdf5; color:#0891b2; padding:8px 24px; border-radius:50px; font-weight:700; font-size:16px; margin-bottom:20px;">FAQ</span>
                <h2 id="faqTitle" style="font-size:36px; font-weight:800; color:#1e293b;">Pertanyaan Sering Diajukan</h2>
                <p id="faqDesc" style="color:#64748b;">Temukan jawaban atas pertanyaan umum seputar pendaftaran dan program
                    di PKBM
                    Harmoni.</p>
            </div>
            <div class="card"
                style="padding: 40px; border-radius: 20px; background: white; border: 1px solid #e2e8f0; max-width: 900px; margin: 0 auto; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);">
                <div id="faqContainer" style="display: flex; flex-direction: column; gap: 15px;">
                    <!-- FAQ Items will be loaded via JS -->
                </div>
            </div>
        </div>
        <!-- Gelombang (FAQ to Kontak) -->
        <div class="section-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C0,0,10.21,41.92,45.63,56.71,118.84,87.27,222.19,74.78,321.39,56.44Z"
                    fill="#ffffff"></path>
            </svg>
        </div>
    </section>

    <!-- KONTAK -->
    <section class="block" id="kontak" style="background: white;">
        <div class="container">
            <div style="text-align:center; max-width:800px; margin:0 auto 50px;">
                <span id="kontakBadge"
                    style="display:inline-block; background:#eef2ff; color:#4338ca; padding:8px 24px; border-radius:50px; font-weight:700; font-size:16px; margin-bottom:20px;">Kontak</span>
                <h2 id="kontakTitle" style="font-size:36px; font-weight:800; color:#1e293b;">Pusat Layanan & Informasi</h2>
                <p id="kontakDesc" style="color:#64748b;">Kami siap membantu Anda setiap hari kerja. Jangan ragu untuk
                    menghubungi kami
                    untuk info lebih lanjut.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <!-- WhatsApp -->
                <div class="card"
                    style="padding: 30px; border: 1px solid #e2e8f0; border-radius: 16px; display: flex; flex-direction: column; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="font-size: 20px; font-weight: 800; color: #1e293b; margin: 0;">KONTAK</h3>
                        <div
                            style="width: 44px; height: 44px; background: #e8fcf0; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(37, 211, 102, 0.15);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-11.7c.9 0 1.8.2 2.6.5l3.9-1.2-1.2 3.9c.3.8.5 1.7.5 2.6z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p id="kontakWaDesc" style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                        Hubungi admin PKBM
                        Harmoni melalui WhatsApp untuk informasi pendaftaran atau pertanyaan lainnya.</p>
                    <div style="margin-bottom: 20px;">
                        <h4 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 700; color: #1e293b;">Jam Operasional:
                        </h4>
                        <ul id="kontakJam"
                            style="margin: 0; padding: 0; list-style: none; font-size: 13px; color: #64748b;">
                            <li>Senin - Jumat: 08:00 - 16:00 WIB</li>
                            <li>Sabtu: 08:00 - 12:00 WIB</li>
                            <li>Minggu: Tutup</li>
                        </ul>
                    </div>
                    <div
                        style="margin-top: auto; margin-bottom: 20px; padding: 12px; background: #eff6ff; border-radius: 12px; border-left: 4px solid #2563eb;">
                        <p id="kontakWaNote" style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
                            <strong>💬 Response Time:</strong> Admin
                            merespons dalam 1-2 jam pada jam kerja.
                        </p>
                    </div>
                    <a id="kontakWaLink" href="https://wa.me/6285875978865" target="_blank" class="btn primary"
                        onclick="window.open(this.href, '_blank'); return false;"
                        style="display: block; text-align: center; background: #2563eb; color: white; padding: 12px; border-radius: 10px; text-decoration: none; font-weight: 700; position: relative; z-index: 9999 !important; pointer-events: auto !important; cursor: pointer !important;">Chat
                        WhatsApp</a>
                </div>

                <!-- Lokasi -->
                <div class="card"
                    style="padding: 30px; border: 1px solid #e2e8f0; border-radius: 16px; display: flex; flex-direction: column; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="font-size: 20px; font-weight: 800; color: #1e293b; margin: 0;">LOKASI</h3>
                        <div
                            style="width: 44px; height: 44px; background: #eef2ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                    </div>
                    <p id="kontakAlamat" style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 10px;">
                        Kotayasa RT 006 RW
                        006, Kecamatan Sumbang, Kabupaten Banyumas, Jawa Tengah</p>
                    <div
                        style="margin-top: 10px; margin-bottom: 20px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; height: 180px;">
                        <iframe id="kontakMapsEmbed"
                            src="https://maps.google.com/maps?q=Kotayasa%20RT%20006%20RW%20006%2C%20Sumbang%2C%20Banyumas%2C%20Jawa%20Tengah&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                    <a id="kontakMapsLink"
                        href="https://maps.google.com/maps?q=Kotayasa+RT+006+RW+006,+Kecamatan+Sumbang,+Kabupaten+Banyumas,+Jawa+Tengah,+Indonesia"
                        target="_blank" class="btn primary"
                        style="margin-top: auto; display: block; text-align: center; background: #2563eb; color: white; padding: 12px; border-radius: 10px; text-decoration: none; font-weight: 700; position: relative; z-index: 10;">Buka
                        di Maps</a>
                </div>

                <!-- Bantuan -->
                <div class="card"
                    style="padding: 30px; border: 1px solid #e2e8f0; border-radius: 16px; display: flex; flex-direction: column; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="font-size: 20px; font-weight: 800; color: #1e293b; margin: 0;">BANTUAN</h3>
                        <div
                            style="width: 44px; height: 44px; background: #fff1f2; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(225, 29, 72, 0.15);">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                        </div>
                    </div>
                    <p id="kontakBantuanDesc"
                        style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">Kirim pertanyaan
                        atau
                        keluhan pendaftaran melalui email ke admin PKBM Harmoni.</p>
                    <div style="margin-bottom: 15px;">
                        <h4 style="margin: 0 0 8px 0; font-size: 13px; font-weight: 700; color: #1e293b;">Bantuan untuk:
                        </h4>
                        <ul id="kontakBantuanList" style="margin: 0; padding: 0 0 0 15px; font-size: 12px; color: #64748b;">
                            <li>Keluhan pendaftaran online</li>
                            <li>Informasi Paket B/C</li>
                            <li>Konfirmasi status</li>
                            <li>Jadwal belajar</li>
                        </ul>
                    </div>
                    <div
                        style="margin-top: auto; margin-bottom: 20px; padding: 12px; background: rgba(22,163,74,.06); border-radius: 12px; border-left: 4px solid #16a34a;">
                        <p id="kontakEmailInfo" style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
                            <strong>📧 Email:</strong>
                            <span id="kontakEmailText">pkbmharmoni116@gmail.com</span>
                        </p>
                    </div>
                    <a id="kontakEmailLink"
                        href="https://mail.google.com/mail/?view=cm&fs=1&to=pkbmharmoni116@gmail.com&su=Tanya%20Seputar%20PKBM%20Harmoni"
                        target="_blank" class="btn primary" onclick="window.open(this.href, '_blank'); return false;"
                        style="display: block; text-align: center; background: #2563eb; color: white; padding: 12px; border-radius: 10px; text-decoration: none; font-weight: 700; position: relative; z-index: 9999 !important; pointer-events: auto !important; cursor: pointer !important;">Kirim
                        Email</a>
                </div>
            </div>
        </div>
        <!-- Gelombang (Kontak to Footer) -->
        <div class="section-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C0,0,10.21,41.92,45.63,56.71,118.84,87.27,222.19,74.78,321.39,56.44Z"
                    fill="#0f172a"></path>
            </svg>
        </div>
    </section>
@endsection

@section('modals')
    <!-- Modal Galeri (MOVED TO CONTENT) -->

    <!-- Lightbox Modal -->
    <div id="imageLightboxModal" class="modal" onclick="closeLightbox()"
        style="display:none; position: fixed; z-index: 2000000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); align-items:center; justify-content:center; cursor: zoom-out;">
        <span
            style="position: absolute; top: 20px; right: 30px; color: white; font-size: 40px; font-weight: bold; cursor: pointer;">&times;</span>
        <img id="lightboxImg" src=""
            style="max-width: 90%; max-height: 90%; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.5); cursor: default; transition: transform 0.3s ease;"
            onclick="event.stopPropagation()">
    </div>
@endsection

@section('scripts')
    <script>
        // Utility Logic
        function copyToClipboard(text, type) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    title: `${type} Berhasil Disalin!`,
                    text: text,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true,
                    customClass: {
                        popup: 'swal2-container'
                    }
                });
            }).catch(err => {
                console.error('Gagal menyalin:', err);
                const tempInput = document.createElement('input');
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                Swal.fire({
                    title: `${type} Berhasil Disalin!`,
                    text: text,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            });
        }

        // Guru Logic
        let allGuruData = [];

        document.addEventListener('DOMContentLoaded', () => {
            fetchHomepageContent();
            fetchGuru();

            // Gallery Logic
            const galleryImages = [];
            for (let i = 1; i <= 9; i++) galleryImages.push(`/assets/foto-pkbm${i}.jfif`);
            renderGallery(galleryImages);
        });

        async function fetchHomepageContent() {
            try {
                const response = await fetch("{{ url('api/homepage-content') }}");
                const result = await response.json();
                if (result.success && result.data) {
                    const data = result.data;

                    // Update Hero
                    if (data.hero_badge) {
                        const hBadge = document.getElementById('heroBadge');
                        if (hBadge) hBadge.innerText = data.hero_badge;
                    }
                    if (data.hero_title) {
                        const hTitle = document.getElementById('heroTitle');
                        if (hTitle) hTitle.innerHTML = data.hero_title.replace(/\n/g, '<br>');
                    }
                    if (data.hero_desc) {
                        const hDesc = document.getElementById('heroDesc');
                        if (hDesc) hDesc.innerText = data.hero_desc;
                    }

                    // Update About
                    if (data.about_title) {
                        const aTitle = document.getElementById('aboutTitle');
                        if (aTitle) aTitle.innerHTML = data.about_title;
                    }
                    if (data.about_desc) {
                        const aDesc = document.getElementById('aboutDesc');
                        if (aDesc) aDesc.innerHTML = data.about_desc.split('\n\n').map(p => `<p style="margin-bottom:16px;">${p}</p>`).join('');
                    }

                    // Update Stats
                    if (data.stats_siswa_val) document.getElementById('statsSiswaVal').innerText = data.stats_siswa_val;
                    if (data.stats_siswa_label) document.getElementById('statsSiswaLabel').innerText = data.stats_siswa_label;
                    if (data.stats_lulus_val) document.getElementById('statsLulusVal').innerText = data.stats_lulus_val;
                    if (data.stats_lulus_label) document.getElementById('statsLulusLabel').innerText = data.stats_lulus_label;
                    if (data.stats_guru_val) document.getElementById('statsGuruVal').innerText = data.stats_guru_val;
                    if (data.stats_guru_label) document.getElementById('statsGuruLabel').innerText = data.stats_guru_label;
                    if (data.stats_gratis_val) document.getElementById('statsGratisVal').innerText = data.stats_gratis_val;
                    if (data.stats_gratis_label) document.getElementById('statsGratisLabel').innerText = data.stats_gratis_label;

                    // Update Visi Misi
                    if (data.visi) document.getElementById('visiContent').innerText = data.visi;
                    if (data.misi) document.getElementById('misiContent').innerText = data.misi;

                    // Update Kontak Section
                    if (data.kontak_badge) document.getElementById('kontakBadge').innerText = data.kontak_badge;
                    if (data.kontak_title) document.getElementById('kontakTitle').innerText = data.kontak_title;
                    if (data.kontak_desc) document.getElementById('kontakDesc').innerText = data.kontak_desc;

                    if (data.kontak_wa_desc) document.getElementById('kontakWaDesc').innerText = data.kontak_wa_desc;
                    if (data.kontak_jam) {
                        const jamList = document.getElementById('kontakJam');
                        if (jamList) {
                            jamList.innerHTML = data.kontak_jam.split('\n').map(item => `<li>${item}</li>`).join('');
                        }
                    }
                    if (data.kontak_wa_note) {
                        const waNote = document.getElementById('kontakWaNote');
                        if (waNote) waNote.innerHTML = `<strong>💬 Response Time:</strong> ${data.kontak_wa_note}`;
                    }
                    if (data.kontak_wa) {
                        const waLink = document.getElementById('kontakWaLink');
                        if (waLink) {
                            const waNum = data.kontak_wa.replace(/\+/g, '').replace(/\s/g, '').replace(/-/g, '').trim();
                            waLink.href = `https://wa.me/${waNum}`;
                            waLink.onclick = function () {
                                window.open(`https://wa.me/${waNum}`, '_blank');
                                return false;
                            };
                        }
                    }

                    if (data.kontak_alamat && document.getElementById('kontakAlamat')) {
                        document.getElementById('kontakAlamat').innerText = data.kontak_alamat;
                    }
                    if (data.kontak_maps_embed && document.getElementById('kontakMapsEmbed')) {
                        document.getElementById('kontakMapsEmbed').src = data.kontak_maps_embed;
                    }
                    if (data.kontak_maps && document.getElementById('kontakMapsLink')) {
                        document.getElementById('kontakMapsLink').href = data.kontak_maps;
                    }

                    if (data.kontak_bantuan_desc && document.getElementById('kontakBantuanDesc')) {
                        document.getElementById('kontakBantuanDesc').innerText = data.kontak_bantuan_desc;
                    }
                    if (data.kontak_bantuan_list) {
                        const bantuanList = document.getElementById('kontakBantuanList');
                        if (bantuanList) {
                            bantuanList.innerHTML = data.kontak_bantuan_list.split('\n').map(item => `<li>${item}</li>`).join('');
                        }
                    }
                    if (data.kontak_email) {
                        const emailText = document.getElementById('kontakEmailText');
                        const emailLink = document.getElementById('kontakEmailLink');
                        if (emailText) emailText.innerText = data.kontak_email.trim();
                        if (emailLink) {
                            const emailAddr = data.kontak_email.trim();
                            const subject = encodeURIComponent("Tanya Seputar PKBM Harmoni");
                            const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${emailAddr}&su=${subject}`;
                            emailLink.href = gmailUrl;
                            emailLink.onclick = function () {
                                window.open(gmailUrl, '_blank');
                                return false;
                            };
                        }
                    }

                    // Update Guru Section
                    if (data.guru_badge) {
                        const gBadge = document.getElementById('guruBadge');
                        if (gBadge) gBadge.innerText = data.guru_badge;
                    }
                    if (data.guru_title) {
                        const gTitle = document.getElementById('guruTitle');
                        if (gTitle) gTitle.innerText = data.guru_title;
                    }
                    if (data.guru_subtitle) {
                        const gSubtitle = document.getElementById('guruSubtitle');
                        if (gSubtitle) gSubtitle.innerText = data.guru_subtitle;
                    }
                    if (data.guru_desc) {
                        const descContainer = document.getElementById('textGuruDesc');
                        if (descContainer) {
                            descContainer.innerHTML = data.guru_desc.split('\n\n').map(p => `<p style="margin-bottom:20px;">${p}</p>`).join('');
                        }
                    }

                    // Update Program Section
                    if (data.program_badge) document.getElementById('programBadge').innerText = data.program_badge;
                    if (data.program_title) document.getElementById('programTitle').innerText = data.program_title;
                    if (data.program_desc) document.getElementById('programDesc').innerText = data.program_desc;

                    if (data.paket_b_title) document.getElementById('paketBTitle').innerText = data.paket_b_title;
                    if (data.paket_b_desc) document.getElementById('paketBDesc').innerText = data.paket_b_desc;
                    if (data.paket_b_umum) document.getElementById('paketBUmum').innerHTML = `<b>Kelompok Umum:</b> ${data.paket_b_umum}`;
                    if (data.paket_b_khusus) document.getElementById('paketBKhusus').innerHTML = `<b>Kelompok Khusus:</b> ${data.paket_b_khusus}`;
                    if (data.paket_b_pengembangan) document.getElementById('paketBPengembangan').innerHTML = `<b>Pengembangan Diri:</b> ${data.paket_b_pengembangan}`;
                    if (data.paket_b_syarat) document.getElementById('paketBSyarat').innerHTML = `<b>Gratis & Syarat:</b> ${data.paket_b_syarat}`;

                    if (data.paket_c_title) document.getElementById('paketCTitle').innerText = data.paket_c_title;
                    if (data.paket_c_desc) document.getElementById('paketCDesc').innerText = data.paket_c_desc;
                    if (data.paket_c_umum) document.getElementById('paketCUmum').innerHTML = `<b>Kelompok Umum:</b> ${data.paket_c_umum}`;
                    if (data.paket_c_ips) document.getElementById('paketCIps').innerHTML = `<b>Peminatan IPS:</b> ${data.paket_c_ips}`;
                    if (data.paket_c_pengembangan) document.getElementById('paketCPengembangan').innerHTML = `<b>Pengembangan Diri:</b> ${data.paket_c_pengembangan}`;
                    if (data.paket_c_syarat) document.getElementById('paketCSyarat').innerHTML = `<b>Gratis & Syarat:</b> ${data.paket_c_syarat}`;

                    // Update Alur Section
                    if (data.alur_badge) document.getElementById('alurBadge').innerText = data.alur_badge;
                    if (data.alur_title) document.getElementById('alurTitle').innerText = data.alur_title;
                    if (data.alur_desc) document.getElementById('alurDesc').innerText = data.alur_desc;
                    if (data.alur_items) {
                        const items = JSON.parse(data.alur_items);
                        const grid = document.getElementById('alurGrid');
                        if (grid && items.length > 0) {
                            grid.innerHTML = items.map((item, idx) => `
                                            <div style="text-align:center; padding:30px; border:1px solid #e2e8f0; border-radius:16px; background:white; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
                                                <div style="width:50px; height:50px; background:#2563eb; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:800; margin:0 auto 20px;">
                                                    ${idx + 1}</div>
                                                <h4 style="font-size:18px; font-weight:700; color:#1e293b; margin-bottom:10px;">${item.title}</h4>
                                                <p style="font-size:14px; color:#64748b; line-height:1.6;">${item.desc}</p>
                                            </div>
                                        `).join('');
                        }
                    }

                    // Update FAQ Section
                    if (data.faq_badge) document.getElementById('faqBadge').innerText = data.faq_badge;
                    if (data.faq_title) document.getElementById('faqTitle').innerText = data.faq_title;
                    if (data.faq_desc) document.getElementById('faqDesc').innerText = data.faq_desc;
                    if (data.faq_items) {
                        const items = JSON.parse(data.faq_items);
                        const container = document.getElementById('faqContainer');
                        if (container && items.length > 0) {
                            container.innerHTML = items.map(item => `
                                            <details style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer;">
                                                <summary style="font-weight: 700; color: #1e293b;">${item.q}</summary>
                                                <p style="margin-top: 10px; color: #64748b;">${item.a}</p>
                                            </details>
                                        `).join('');
                        }
                    }

                    // Update Galeri Section
                    if (data.galeri_badge) document.getElementById('galeriBadge').innerText = data.galeri_badge;
                    if (data.galeri_title) document.getElementById('galeriTitle').innerText = data.galeri_title;
                    if (data.galeri_desc) document.getElementById('galeriDesc').innerText = data.galeri_desc;
                    if (data.galeri_images) {
                        window.homepageGaleriImages = JSON.parse(data.galeri_images);
                        renderGallery(window.homepageGaleriImages);
                    }

                    // Update Group Photo if exists in DB or use default
                    const imgGroup = document.getElementById('imgGuruGroup');
                    if (imgGroup) {
                        if (data.foto_group) {
                            imgGroup.src = data.foto_group;
                        } else {
                            imgGroup.src = "{{ asset('assets/guru-pkbm.jpg') }}";
                        }
                    }
                }
            } catch (err) {
                console.error('Failed to fetch homepage content:', err);
            }
        }

        async function fetchGuru() {
            try {
                // Use url() to ensure correct path regardless of environment
                const response = await fetch("{{ url('api/guru') }}");
                const result = await response.json();
                if (result.success) {
                    window.allGuruData = result.data;
                    renderGuru(window.allGuruData);
                }
            } catch (err) {
                console.error('Error fetching guru:', err);
            }
        }

        function renderGuru(data) {
            const grid = document.getElementById('staffGrid');
            const btn = document.getElementById('btnLihatGuru');
            const loading = document.getElementById('loadingGuru');
            if (loading) loading.style.display = 'none';

            if (!grid) return;
            grid.innerHTML = '';
            const limit = window.innerWidth < 768 ? 2 : 4;
            data.slice(0, limit).forEach(item => grid.appendChild(createGuruCard(item)));
            if (data.length > limit && btn) btn.style.display = 'inline-block';
        }

        function createGuruCard(item) {
            const card = document.createElement('div');
            card.className = "guru-card";
            card.style.cssText = "width: 220px; border: 1px solid #eee; border-radius: 16px; overflow: hidden; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: all 0.3s ease;";

            // Path for placeholder image if original fails
            const fallbackImg = "https://ui-avatars.com/api/?name=" + encodeURIComponent(item.nama) + "&background=random&color=fff&size=240";
            const actualImg = item.foto_url || fallbackImg;

            const imgHtml = `
                            <div class="img-wrapper" style="width:100%; height:240px; overflow:hidden; background:#f1f5f9; cursor: zoom-in;" onclick="openLightbox('${actualImg}')">
                                <img src="${actualImg}" onerror="this.src='${fallbackImg}'; this.onerror=null;" 
                                    style="width:100%; height:100%; object-fit:cover; display:block; transition: transform 0.5s ease;">
                            </div>
                        `;

            card.innerHTML = `
                            ${imgHtml}
                            <div style="padding: 16px; text-align: center;">
                                <div style="font-weight: 800; color: #1e293b; font-size: 15px; margin-bottom: 4px;">${item.nama}</div>
                                <div style="color: #2563eb; font-size: 13px; font-weight: 600;">${item.jabatan}</div>
                                <div style="color: #64748b; font-size: 11px; margin-top: 6px; text-transform: uppercase; letter-spacing: 0.05em;">${item.mapel || '-'}</div>
                            </div>
                        `;

            // Hover effect for image
            const imgEl = card.querySelector('img');
            card.onmouseenter = () => { if (imgEl) imgEl.style.transform = 'scale(1.1)'; };
            card.onmouseleave = () => { if (imgEl) imgEl.style.transform = 'scale(1)'; };

            return card;
        }

        function openLightbox(src) {
            const modal = document.getElementById('imageLightboxModal');
            const img = document.getElementById('lightboxImg');
            if (!modal || !img) return;
            img.src = src;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scroll
        }

        function closeLightbox() {
            const modal = document.getElementById('imageLightboxModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Restore scroll
            }
        }

        function openGuruModal() {
            const modal = document.getElementById('guruModalV3');
            const grid = document.getElementById('staffGridFullV3');
            if (!modal || !grid) {
                console.error('Modal elements not found');
                return;
            }

            grid.innerHTML = '';
            const data = window.allGuruData || [];
            console.log('Opening Guru Modal with data:', data);

            if (data.length > 0) {
                data.forEach(item => {
                    const card = createGuruCard(item);
                    grid.appendChild(card);
                });
            } else {
                grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #64748b; padding: 60px;">Belum ada data guru tersedia.</div>';
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeGuruModal() {
            const modal = document.getElementById('guruModalV3');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // Gallery Logic
        function renderGallery(images) {
            const grid = document.getElementById('galleryGrid');
            const btn = document.getElementById('btnLihatGaleri');
            if (!grid) return;
            grid.innerHTML = '';
            const limit = window.innerWidth < 768 ? 2 : 4;
            images.slice(0, limit).forEach(src => grid.appendChild(createGalleryItem(src)));
            if (images.length > limit && btn) btn.style.display = 'inline-block';
        }

        function createGalleryItem(src) {
            const div = document.createElement('div');
            div.innerHTML = `<img src="${src}" style="width:100%; height:200px; object-fit:cover; border-radius:12px; cursor:zoom-in;" onclick="openLightbox('${src}')">`;
            return div;
        }

        function openGalleryModal() {
            console.log('Attempting to open gallery modal...');
            const modal = document.getElementById('galleryModalV3');
            const grid = document.getElementById('galleryGridFullV3');

            if (!modal || !grid) {
                console.error('Gallery modal elements not found:', { modal, grid });
                return;
            }

            grid.innerHTML = '';
            console.log('Generating gallery items...');
            try {
                if (window.homepageGaleriImages && window.homepageGaleriImages.length > 0) {
                    window.homepageGaleriImages.forEach(src => {
                        grid.appendChild(createGalleryItem(src));
                    });
                } else {
                    for (let i = 1; i <= 9; i++) {
                        const src = `/assets/foto-pkbm${i}.jfif`;
                        const item = createGalleryItem(src);
                        grid.appendChild(item);
                    }
                }
                console.log('Gallery items appended successfully');
            } catch (err) {
                console.error('Error populating gallery grid:', err);
                grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: red;">Gagal memuat galeri.</div>';
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            console.log('Gallery modal display set to flex');
        }

        function closeGalleryModal() {
            const modal = document.getElementById('galleryModalV3');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }
    </script>
@endsection