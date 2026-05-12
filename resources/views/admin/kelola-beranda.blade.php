@extends('layouts.admin')

@section('title', 'Kelola Beranda')
@section('sidebar_subtitle', 'Manajemen Beranda')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/kelola-beranda.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
@endsection

@section('topbar_actions')
<div class="top-tabs">
    <button id="topTabMedia" class="top-tab active">Edit Foto & Poster</button>
    <button id="topTabText" class="top-tab">Edit Teks</button>
    <button id="topTabGuru" class="top-tab">Manajemen Guru</button>
</div>
@endsection

@section('content')
<div class="page-header">
        <h1>Kelola Beranda</h1>
        <p>Kelola gambar poster, foto kegiatan, teks dan data guru.</p>
      </div>

      <!-- 1. Ganti Poster -->
      <section id="panel-media" class="panel active">
        <div id="section-media-start"></div>
        <div
          style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 32px; display: flex; gap: 24px; align-items: flex-start; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
          <div style="flex: 0 0 300px;">
            <img id="currentPoster" src="{{ asset('assets/poster-pkbm-new.jfif') }}" alt="Poster Saat Ini"
              style="width: 100%; height: auto; object-fit: contain; border-radius: 12px; border: 1px solid #e2e8f0; display: block;"
              onerror="this.src='/assets/poster-pkbm-new.jfif'">
          </div>
          <div style="flex: 1;">
            <h2 style="margin: 0 0 8px 0; font-size: 18px; color: #1e293b;">Ganti Foto Poster</h2>
            <p style="color: #64748b; margin-bottom: 20px; font-size: 14px; line-height: 1.5;">
              Upload foto poster baru yang akan ditampilkan di halaman depan siswa.<br>
              <span style="font-size: 12px; color: #94a3b8;">Format: JPG/PNG/JPEG.</span>
            </p>
            <form id="posterForm" enctype="multipart/form-data">
              <div class="form-group">
                <label for="posterFile">Pilih file gambar poster (JPG, PNG, JPEG):</label>
                <input type="file" id="posterFile" name="poster" accept="image/*" required>
              </div>
              <div id="posterCropperWrapper" class="cropper-container-wrapper" style="margin-bottom: 20px;">
                <img id="posterCropperImage" src="" alt="To Crop" style="max-width: 100%;">
              </div>
              <div class="filter-controls" id="posterFilters">
                <div class="filter-presets">
                  <select id="posterPreset">
                    <option value="natural">Natural</option>
                    <option value="boost">Boost Color</option>
                    <option value="warm">Warm</option>
                    <option value="cool">Cool</option>
                    <option value="contrast">High Contrast</option>
                    <option value="soft">Soft</option>
                  </select>
                  <button type="button" id="posterReset">Reset</button>
                </div>
                <div class="filter-row">
                  <label>Kecerahan</label>
                  <input type="range" id="posterBright" min="50" max="150" value="100">
                  <div class="value" id="posterBrightVal">100%</div>
                </div>
                <div class="filter-row">
                  <label>Kontras</label>
                  <input type="range" id="posterContrast" min="50" max="150" value="100">
                  <div class="value" id="posterContrastVal">100%</div>
                </div>
                <div class="filter-row">
                  <label>Saturasi</label>
                  <input type="range" id="posterSaturate" min="50" max="180" value="110">
                  <div class="value" id="posterSaturateVal">110%</div>
                </div>
                <div class="filter-row">
                  <label>Nuansa</label>
                  <input type="range" id="posterHue" min="-30" max="30" value="0">
                  <div class="value" id="posterHueVal">0°</div>
                </div>
                <div class="filter-row">
                  <label>Blur</label>
                  <input type="range" id="posterBlur" min="0" max="4" value="0">
                  <div class="value" id="posterBlurVal">0px</div>
                </div>
              </div>
              <br>
              <button type="submit" class="btn primary">Upload Poster</button>
            </form>
          </div>
        </div>

        <!-- 2. Ganti Foto Kegiatan -->
        <div
          style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 32px; display: flex; gap: 24px; align-items: flex-start; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
          <div style="flex: 0 0 300px;">
            <!-- Default preview or placeholder -->
            <img id="currentActivityPhoto" src="https://placehold.co/300x200?text=Pilih+Nomor" alt="Preview Foto"
              style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; border: 1px solid #e2e8f0; display: block; background: #f8fafc;">
            <p id="previewLabel" style="text-align: center; margin-top: 8px; color: #64748b; font-size: 13px;">Preview
              Foto No: -</p>
          </div>
          <div style="flex: 1;">
            <h2 style="margin: 0 0 8px 0; font-size: 18px; color: #1e293b;">Ganti Foto Kegiatan</h2>
            <p style="color: #64748b; margin-bottom: 20px; font-size: 14px; line-height: 1.5;">
              Pilih file untuk mengganti salah satu foto kegiatan. Masukkan nomor foto yang ingin diganti untuk melihat
              foto
              saat ini.<br>
              <span style="font-size: 12px; color: #94a3b8;">Format: JPG/PNG/JPEG.</span>
            </p>
            <form id="dokumentasiForm" enctype="multipart/form-data">
              <div class="form-group">
                <label for="dokumentasiNumber">Nomor Foto Kegiatan (1-25):</label>
                <input type="number" id="dokumentasiNumber" name="number" min="1" max="25" required
                  placeholder="Masukkan nomor foto..."
                  style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
              </div>
              <div class="form-group" style="margin-top: 16px;">
                <label for="dokumentasiFile">Pilih file gambar kegiatan (JPG, PNG, JPEG):</label>
                <input type="file" id="dokumentasiFile" name="dokumentasi" accept="image/*" required>
              </div>
              <div id="dokumentasiCropperWrapper" class="cropper-container-wrapper" style="margin-bottom: 20px;">
                <img id="dokumentasiCropperImage" src="" alt="To Crop" style="max-width: 100%;">
              </div>
              <div class="filter-controls" id="dokumentasiFilters">
                <div class="filter-presets">
                  <select id="dokumentasiPreset">
                    <option value="natural">Natural</option>
                    <option value="boost">Boost Color</option>
                    <option value="warm">Warm</option>
                    <option value="cool">Cool</option>
                    <option value="contrast">High Contrast</option>
                    <option value="soft">Soft</option>
                  </select>
                  <button type="button" id="dokumentasiReset">Reset</button>
                </div>
                <div class="filter-row">
                  <label>Kecerahan</label>
                  <input type="range" id="dokumentasiBright" min="50" max="150" value="100">
                  <div class="value" id="dokumentasiBrightVal">100%</div>
                </div>
                <div class="filter-row">
                  <label>Kontras</label>
                  <input type="range" id="dokumentasiContrast" min="50" max="150" value="100">
                  <div class="value" id="dokumentasiContrastVal">100%</div>
                </div>
                <div class="filter-row">
                  <label>Saturasi</label>
                  <input type="range" id="dokumentasiSaturate" min="50" max="180" value="110">
                  <div class="value" id="dokumentasiSaturateVal">110%</div>
                </div>
                <div class="filter-row">
                  <label>Nuansa</label>
                  <input type="range" id="dokumentasiHue" min="-30" max="30" value="0">
                  <div class="value" id="dokumentasiHueVal">0°</div>
                </div>
                <div class="filter-row">
                  <label>Blur</label>
                  <input type="range" id="dokumentasiBlur" min="0" max="4" value="0">
                  <div class="value" id="dokumentasiBlurVal">0px</div>
                </div>
              </div>
              <br>
              <button type="submit" class="btn primary">Upload Kegiatan</button>
            </form>
          </div>
        </div>

        <!-- 3. Ganti Foto Group Guru + 4. Background Hero (stacked) -->
        <div class="media-stack">
          <!-- Card: Foto Tenaga Pendidik (Group) -->
          <div
            style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; gap: 24px; align-items: flex-start; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="flex: 0 0 300px;">
              <img id="currentGroupPhoto" src="{{ asset('assets/guru-pkbm.jpg') }}" alt="Foto Group"
                style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; border: 1px solid #e2e8f0; display: block;"
                onerror="this.src='/assets/guru-pkbm.jpg'">
            </div>
            <div style="flex: 1;">
              <h3 style="margin: 0 0 8px 0; font-size: 18px; color: #1e293b;">Foto Tenaga Pendidik (Group)</h3>
              <p style="color: #64748b; margin-bottom: 20px; font-size: 14px; line-height: 1.5;">
                Upload foto bersama tenaga pendidik yang akan ditampilkan di halaman depan siswa.
                Pastikan foto berkualitas baik (Landscape).<br>
                <span style="font-size: 12px; color: #94a3b8;">Format: JPG/PNG. Maksimal: 10MB.</span>
              </p>
              <form id="groupPhotoForm">
                <div class="form-group">
                  <label for="foto_group">Pilih file gambar group (JPG, PNG, JPEG):</label>
                  <input type="file" id="foto_group" name="foto_group" accept="image/*" required>
                </div>
                <div id="groupCropperWrapper" class="cropper-container-wrapper" style="margin-bottom: 20px;">
                  <img id="groupCropperImage" src="" alt="To Crop" style="max-width: 100%;">
                </div>
                <br>
                <button type="submit" class="btn primary">Upload Foto Tenaga Pendidik (Group)</button>
              </form>
            </div>
          </div>
          <!-- Card: Background Wallpaper Hero -->
          <div
            style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; gap: 24px; align-items: flex-start; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="flex: 0 0 300px;">
              <div id="currentHeroBg"
                style="width: 100%; height: 200px; background-size: cover; background-position: center; border-radius: 12px; border: 1px solid #e2e8f0;">
              </div>
            </div>
            <div style="flex: 1;">
              <h3 style="margin: 0 0 8px 0; font-size: 18px; color: #1e293b;">Background Wallpaper Hero</h3>
              <p style="color: #64748b; margin-bottom: 20px; font-size: 14px; line-height: 1.5;">
                Ganti gambar latar belakang utama (Hero) pada halaman depan.<br>
                <span style="font-size: 12px; color: #94a3b8;">Format: JPG/PNG. Disarankan ukuran lebar
                  (Widescreen).</span>
              </p>
              <form id="heroBgForm">
                <div class="form-group">
                  <label for="hero_bg_file">Pilih gambar background:</label>
                  <input type="file" id="hero_bg_file" name="file" accept="image/*" required>
                </div>
                <div id="heroCropperWrapper" class="cropper-container-wrapper"
                  style="margin-top: 20px; display: none; max-width: 100%; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                  <img id="heroCropperImage" src="" alt="To Crop" style="max-width: 100%; display: block;">
                </div>
                <br>
                <button type="submit" class="btn primary">Upload Background Hero</button>
              </form>
            </div>
          </div>
        </div>
      </section>

        <!-- 5. Kelola Konten Tekstual -->
        <section id="panel-text" class="panel" style="display:none">
          <div id="section-text-start"></div>
          <h2 class="section-title">Edit Konten Teks Beranda</h2>

          <!-- HERO SECTION -->
          <form class="homepage-form edit-section-card">
            <div class="form-header">
              <div class="form-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="3" y1="9" x2="21" y2="9"></line>
                </svg>
              </div>
              <h4>Bagian Hero (Atas)</h4>
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="label-modern">Badge Teks (Kecil)</label>
                <input type="text" name="hero_badge" class="input-modern" placeholder="Contoh: SELAMAT DATANG">
              </div>
              <div class="form-group">
                <label class="label-modern">Judul Utama</label>
                <textarea name="hero_title" rows="2" class="input-modern"
                  placeholder="Judul besar di halaman depan..."></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="label-modern">Deskripsi Hero</label>
              <textarea name="hero_desc" rows="3" class="input-modern"
                placeholder="Deskripsi singkat di bawah judul..."></textarea>
            </div>
            <div class="btn-save-container">
              <button type="submit" class="btn primary">Simpan Bagian Hero</button>
            </div>
          </form>

          <!-- HOME SECTION -->
          <form class="homepage-form edit-section-card">
            <div class="form-header">
              <div class="form-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                  <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
              </div>
              <h4>Home / Tentang</h4>
            </div>

            <div class="form-group">
              <label class="label-modern">Judul "Tentang"</label>
              <input type="text" name="about_title" class="input-modern">
            </div>
            <div class="form-group">
              <label class="label-modern">Konten Deskripsi</label>
              <textarea name="about_desc" rows="6" class="input-modern"></textarea>
            </div>

            <div class="form-grid-2">
              <div>
                <label class="label-modern">Visi</label>
                <input type="text" name="visi" class="input-modern">
              </div>
              <div>
                <label class="label-modern">Misi</label>
                <textarea name="misi" rows="2" class="input-modern"></textarea>
              </div>
            </div>

            <div class="form-grid-4" style="margin-top: 24px;">
              <div>
                <label class="label-modern">Siswa (Angka)</label>
                <input type="text" name="stats_siswa_val" class="input-modern">
                <label class="label-modern" style="margin-top:8px; font-size:11px;">Label</label>
                <input type="text" name="stats_siswa_label" class="input-modern">
              </div>
              <div>
                <label class="label-modern">Lulus (Angka)</label>
                <input type="text" name="stats_lulus_val" class="input-modern">
                <label class="label-modern" style="margin-top:8px; font-size:11px;">Label</label>
                <input type="text" name="stats_lulus_label" class="input-modern">
              </div>
              <div>
                <label class="label-modern">Guru (Angka)</label>
                <input type="text" name="stats_guru_val" class="input-modern">
                <label class="label-modern" style="margin-top:8px; font-size:11px;">Label</label>
                <input type="text" name="stats_guru_label" class="input-modern">
              </div>
              <div>
                <label class="label-modern">Gratis (Angka)</label>
                <input type="text" name="stats_gratis_val" class="input-modern">
                <label class="label-modern" style="margin-top:8px; font-size:11px;">Label</label>
                <input type="text" name="stats_gratis_label" class="input-modern">
              </div>
            </div>
            <div class="btn-save-container">
              <button type="submit" class="btn primary">Simpan Bagian Tentang</button>
            </div>
          </form>

          <!-- GURU SECTION -->
          <form class="homepage-form edit-section-card">
            <div class="form-header">
              <div class="form-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
              </div>
              <h4>Profil Guru</h4>
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="label-modern">Badge</label>
                <input type="text" name="guru_badge" class="input-modern">
              </div>
              <div class="form-group">
                <label class="label-modern">Judul</label>
                <input type="text" name="guru_title" class="input-modern">
              </div>
            </div>
            <div class="form-group">
              <label class="label-modern">Subjudul</label>
              <input type="text" name="guru_subtitle" class="input-modern">
            </div>
            <div class="form-group">
              <label class="label-modern">Deskripsi Guru</label>
              <textarea name="guru_desc" rows="4" class="input-modern"></textarea>
            </div>
            <div class="btn-save-container">
              <button type="submit" class="btn primary">Simpan Profil Guru</button>
            </div>
          </form>

          <!-- PROGRAM SECTION -->
          <form class="homepage-form edit-section-card">
            <div class="form-header">
              <div class="form-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                  <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
              </div>
              <h4>Program</h4>
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="label-modern">Badge</label>
                <input type="text" name="program_badge" class="input-modern">
              </div>
              <div class="form-group">
                <label class="label-modern">Judul</label>
                <input type="text" name="program_title" class="input-modern">
              </div>
            </div>
            <div class="form-group">
              <label class="label-modern">Deskripsi Program Umum</label>
              <textarea name="program_desc" rows="3" class="input-modern"></textarea>
            </div>

            <div class="form-grid-2" style="margin-top:20px;">
              <!-- Paket B -->
              <div class="card"
                style="padding: 24px; border: 1px solid var(--line); box-shadow: none; background: #f8fafc;">
                <h5 style="margin: 0 0 16px 0; font-size: 16px; color: var(--primary);">Isi Paket B</h5>
                <div class="form-group">
                  <label class="label-modern">Judul</label>
                  <input type="text" name="paket_b_title" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Deskripsi</label>
                  <textarea name="paket_b_desc" rows="3" class="input-modern"></textarea>
                </div>
                <div class="form-group">
                  <label class="label-modern">Kelompok Umum</label>
                  <input type="text" name="paket_b_umum" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Kelompok Khusus</label>
                  <input type="text" name="paket_b_khusus" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Pengembangan Diri</label>
                  <input type="text" name="paket_b_pengembangan" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Syarat & Gratis</label>
                  <input type="text" name="paket_b_syarat" class="input-modern">
                </div>
              </div>

              <!-- Paket C -->
              <div class="card"
                style="padding: 24px; border: 1px solid var(--line); box-shadow: none; background: #f8fafc;">
                <h5 style="margin: 0 0 16px 0; font-size: 16px; color: var(--primary);">Isi Paket C</h5>
                <div class="form-group">
                  <label class="label-modern">Judul</label>
                  <input type="text" name="paket_c_title" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Deskripsi</label>
                  <textarea name="paket_c_desc" rows="3" class="input-modern"></textarea>
                </div>
                <div class="form-group">
                  <label class="label-modern">Kelompok Umum</label>
                  <input type="text" name="paket_c_umum" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Peminatan IPS</label>
                  <input type="text" name="paket_c_ips" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Pengembangan Diri</label>
                  <input type="text" name="paket_c_pengembangan" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Syarat & Gratis</label>
                  <input type="text" name="paket_c_syarat" class="input-modern">
                </div>
              </div>
            </div>

            <div class="btn-save-container">
              <button type="submit" class="btn primary">Simpan Program</button>
            </div>
          </form>

          <!-- ALUR SECTION -->
          <form class="homepage-form edit-section-card" id="alurForm">
            <div class="form-header">
              <div class="form-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
                  <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
                  <path d="M2 2l7.586 7.586"></path>
                  <circle cx="11" cy="11" r="2"></circle>
                </svg>
              </div>
              <h4>Alur Pendaftaran</h4>
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="label-modern">Badge</label>
                <input type="text" name="alur_badge" class="input-modern">
              </div>
              <div class="form-group">
                <label class="label-modern">Judul</label>
                <input type="text" name="alur_title" class="input-modern">
              </div>
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
              <label class="label-modern">Deskripsi Alur</label>
              <textarea name="alur_desc" rows="2" class="input-modern"></textarea>
            </div>

            <!-- ALUR STEPS DYNAMIC -->
            <div id="alurStepsContainer" style="display: grid; gap: 15px; margin-bottom: 24px;">
              <!-- Items akan diisi via JS -->
            </div>

            <input type="hidden" name="alur_items" id="alurItemsInput">

            <div class="btn-save-container"
              style="display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
              <button type="button" id="addAlurBtn" class="btn"
                style="background: #eff6ff; color: var(--primary); border: 1px solid rgba(37, 99, 235, 0.2);">
                <i class="fas fa-plus"></i> Tambah Item Alur
              </button>
              <button type="button" id="deleteAlurBtn" class="btn"
                style="background: #dc2626; color: #fff; border: none; box-shadow: 0 4px 6px -1px rgba(220,38,38,0.3);">
                Hapus Item Alur
              </button>
              <button type="submit" class="btn primary">Simpan Alur</button>
            </div>
          </form>

          <!-- GALERI SECTION -->
          <form class="homepage-form edit-section-card">
            <div class="form-header">
              <div class="form-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
              </div>
              <h4>Galeri</h4>
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="label-modern">Badge</label>
                <input type="text" name="galeri_badge" class="input-modern">
              </div>
              <div class="form-group">
                <label class="label-modern">Judul</label>
                <input type="text" name="galeri_title" class="input-modern">
              </div>
            </div>
            <div class="form-group">
              <label class="label-modern">Deskripsi Galeri</label>
              <textarea name="galeri_desc" rows="2" class="input-modern"></textarea>
            </div>
            <div class="btn-save-container">
              <button type="submit" class="btn primary">Simpan Galeri</button>
            </div>
          </form>


          <!-- FAQ SECTION -->
          <form class="homepage-form edit-section-card" id="faqForm">
            <div class="form-header">
              <div class="form-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                  <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
              </div>
              <h4>FAQ (Pertanyaan Umum)</h4>
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="label-modern">Badge</label>
                <input type="text" name="faq_badge" class="input-modern">
              </div>
              <div class="form-group">
                <label class="label-modern">Judul</label>
                <input type="text" name="faq_title" class="input-modern">
              </div>
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
              <label class="label-modern">Deskripsi FAQ</label>
              <textarea name="faq_desc" rows="2" class="input-modern"></textarea>
            </div>

            <!-- FAQ ITEMS DYNAMIC -->
            <div id="faqListContainer" style="display: grid; gap: 15px; margin-bottom: 24px;">
              <!-- Items akan diisi via JS -->
            </div>

            <input type="hidden" name="faq_items" id="faqItemsInput">

            <div class="btn-save-container"
              style="display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
              <button type="button" id="addFaqBtn" class="btn"
                style="background: #eff6ff; color: var(--primary); border: 1px solid rgba(37, 99, 235, 0.2);">
                <i class="fas fa-plus"></i> Tambah Item FAQ
              </button>
              <button type="button" id="deleteFaqBtn" class="btn"
                style="background: #dc2626; color: #fff; border: none; box-shadow: 0 4px 6px -1px rgba(220,38,38,0.3);">
                Hapus Item FAQ
              </button>
              <button type="submit" class="btn primary">Simpan FAQ</button>
            </div>
          </form>

          <!-- KONTAK SECTION -->
          <form class="homepage-form edit-section-card">
            <div class="form-header">
              <div class="form-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path
                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                  </path>
                </svg>
              </div>
              <h4>Kontak</h4>
            </div>

            <!-- Header Kontak -->
            <div class="form-grid-2" style="margin-bottom: 24px;">
              <div class="form-group">
                <label class="label-modern">Badge Seksi</label>
                <input type="text" name="kontak_badge" class="input-modern">
              </div>
              <div class="form-group">
                <label class="label-modern">Judul Seksi</label>
                <input type="text" name="kontak_title" class="input-modern">
              </div>
              <div class="form-group" style="grid-column: span 2;">
                <label class="label-modern">Deskripsi Seksi</label>
                <textarea name="kontak_desc" rows="2" class="input-modern"></textarea>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">

              <!-- Card 1: WhatsApp -->
              <div class="card"
                style="padding: 20px; border: 1px solid var(--line); box-shadow: none; background: #f8fafc;">
                <h5
                  style="margin: 0 0 16px 0; font-size: 16px; color: var(--primary); border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                  Card WhatsApp</h5>
                <div class="form-group">
                  <label class="label-modern">Deskripsi Card</label>
                  <textarea name="kontak_wa_desc" rows="2" class="input-modern"></textarea>
                </div>
                <div class="form-group">
                  <label class="label-modern">Nomor WhatsApp</label>
                  <input type="text" name="kontak_wa" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Jam Operasional (List)</label>
                  <textarea name="kontak_jam" rows="4" class="input-modern"></textarea>
                </div>
                <div class="form-group">
                  <label class="label-modern">Catatan (Response Time)</label>
                  <input type="text" name="kontak_wa_note" class="input-modern">
                </div>
              </div>

              <!-- Card 2: Lokasi -->
              <div class="card"
                style="padding: 20px; border: 1px solid var(--line); box-shadow: none; background: #f8fafc;">
                <h5
                  style="margin: 0 0 16px 0; font-size: 16px; color: var(--primary); border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                  Card Lokasi</h5>
                <div class="form-group">
                  <label class="label-modern">Deskripsi Card</label>
                  <textarea name="kontak_lokasi_desc" rows="2" class="input-modern"></textarea>
                </div>
                <div class="form-group">
                  <label class="label-modern">Alamat Lengkap</label>
                  <textarea name="kontak_alamat" rows="2" class="input-modern"></textarea>
                </div>
                <div class="form-group">
                  <label class="label-modern">Link Google Maps (Tombol)</label>
                  <input type="text" name="kontak_maps" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">URL Embed Maps (Iframe Src)</label>
                  <textarea name="kontak_maps_embed" rows="4" class="input-modern"></textarea>
                </div>
              </div>

              <!-- Card 3: Bantuan -->
              <div class="card"
                style="padding: 20px; border: 1px solid var(--line); box-shadow: none; background: #f8fafc;">
                <h5
                  style="margin: 0 0 16px 0; font-size: 16px; color: var(--primary); border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                  Card Bantuan (Email)</h5>
                <div class="form-group">
                  <label class="label-modern">Deskripsi Card</label>
                  <textarea name="kontak_bantuan_desc" rows="2" class="input-modern"></textarea>
                </div>
                <div class="form-group">
                  <label class="label-modern">Email Resmi</label>
                  <input type="text" name="kontak_email" class="input-modern">
                </div>
                <div class="form-group">
                  <label class="label-modern">Daftar Bantuan (Satu per baris)</label>
                  <textarea name="kontak_bantuan_list" rows="4" class="input-modern"></textarea>
                </div>
                <div class="form-group">
                  <label class="label-modern">Catatan (Support Hours)</label>
                  <input type="text" name="kontak_bantuan_note" class="input-modern">
                </div>
              </div>

            </div>

            <div class="btn-save-container">
              <button type="submit" class="btn primary">Simpan Seluruh Kontak</button>
            </div>
          </form>
        </section>

        <!-- 4. Daftar Guru Grid -->
        <section id="panel-guru" class="panel" style="display:none">
          <div id="section-guru-start"></div>
          <h2
            style="margin-top: 40px; font-size: 24px; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 20px;">
            Manajemen Data Personal Guru</h2>

          <div style="margin-bottom: 20px;">
            <button onclick="openModal()" class="btn primary" style="padding: 10px 20px; font-size: 14px;">
              + Tambah Data Guru
            </button>
          </div>

          <div id="guruGrid" class="guru-grid" style="margin-bottom: 60px;">
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #64748b;">
              Memuat data guru...
            </div>
          </div>
        </section>

  <!-- Guru Modal -->
  <div id="guruModal" class="modal-overlay">
    <div class="modal-content">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 id="modalTitle" style="margin: 0; font-size: 20px; font-weight:700;">Tambah Guru Baru</h2>
        <button onclick="closeModal()"
          style="background: none; border: none; font-size: 24px; cursor: pointer; color:#64748b;">&times;</button>
      </div>

      <form id="guruForm">
        <input type="hidden" id="guruId">

        <div class="input-group" style="margin-bottom: 16px;">
          <label for="nama" style="display:block;margin-bottom:8px;font-weight:600;color:#64748b;">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" required placeholder="Contoh: Budi Santoso, S.Pd."
            style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px;">
        </div>

        <div class="input-group" style="margin-bottom: 16px;">
          <label for="jabatan" style="display:block;margin-bottom:8px;font-weight:600;color:#64748b;">Jabatan</label>
          <input type="text" id="jabatan" name="jabatan" required placeholder="Contoh: Guru Matematika"
            style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px;">
        </div>

        <div class="input-group" style="margin-bottom: 16px;">
          <label for="kategori" style="display:block;margin-bottom:8px;font-weight:600;color:#64748b;">Kategori</label>
          <select id="kategori" name="kategori" required
            style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; background:white;">
            <option value="pimpinan">Pimpinan / Struktural</option>
            <option value="pendidik">Tenaga Pendidik (Guru)</option>
          </select>
        </div>

        <div class="input-group" style="margin-bottom: 16px;">
          <label for="mapel" style="display:block;margin-bottom:8px;font-weight:600;color:#64748b;">Mapel yang
            Diajarkan</label>
          <input type="text" id="mapel" name="mapel" placeholder="Contoh: Matematika, Bahasa Indonesia"
            style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px;">
        </div>

        <div class="input-group" style="margin-bottom: 24px;">
          <label for="foto" style="display:block;margin-bottom:8px;font-weight:600;color:#64748b;">Foto Profil</label>
          <input type="file" id="foto" name="foto" accept="image/*"
            style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 10px;">

          <!-- Cropper Wrapper -->
          <div id="cropperWrapper" class="cropper-container-wrapper">
            <img id="cropperImage" src="" alt="To Crop">
          </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
          <button type="button" onclick="closeModal()"
            style="flex: 1; padding: 12px; background: #f1f5f9; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; color: #475569;">Batal</button>
          <button type="submit"
            style="flex: 1; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 600;">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="{{ asset('assets/js/kelola-beranda.js') }}?v={{ time() }}"></script>
<script>
    // Additional logic for panel switching (synced with tabs)
    const tabMedia = document.getElementById('topTabMedia');
    const tabText = document.getElementById('topTabText');
    const tabGuru = document.getElementById('topTabGuru');
    
    const panelMedia = document.getElementById('panel-media');
    const panelText = document.getElementById('panel-text');
    const panelGuru = document.getElementById('panel-guru');

    function switchPanel(panelToShow, activeTab) {
        [panelMedia, panelText, panelGuru].forEach(p => p.style.display = 'none');
        [tabMedia, tabText, tabGuru].forEach(t => t.classList.remove('active'));
        
        panelToShow.style.display = 'block';
        activeTab.classList.add('active');
    }

    tabMedia.onclick = () => switchPanel(panelMedia, tabMedia);
    tabText.onclick = () => switchPanel(panelText, tabText);
    tabGuru.onclick = () => switchPanel(panelGuru, tabGuru);
</script>
@endsection
