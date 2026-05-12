<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Siswa - PKBM Harmoni</title>
  <link rel="stylesheet" href="{{ asset('assets/css/siswa-auth.css') }}">
  <style>
    #registerBtn {
      background: #2563eb !important;
    }

    #registerBtn:hover {
      background: #2563eb !important;
    }

    .auth-footer a {
      color: #2563eb !important;
    }
  </style>
</head>

<body>
  <div class="split-layout">

    <!-- Sisi Kiri Formulir -->
    <div class="split-left">
      <a href="{{ route('login') }}" class="back-btn" title="Kembali">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
      </a>

      <div class="auth-header">
        <h1>Daftar</h1>
      </div>

      <form id="registerForm" class="auth-form">
        <div class="form-group">
          <label for="nama_lengkap">Nama Lengkap</label>
          <div class="password-container">
            <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" required
              style="padding-left: 16px;">
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Masukkan Email" required>
        </div>



        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Masukkan Password" minlength="8" required>
            <button type="button" class="password-toggle" id="togglePassword">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                class="eye-icon">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                class="eye-off-icon" style="display: none;">
                <path
                  d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                </path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
              </svg>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm_password">Konfirmasi Password</label>
          <div class="password-container">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password"
              required>
            <button type="button" class="password-toggle" id="toggleConfirmPassword">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                class="eye-icon">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                class="eye-off-icon" style="display: none;">
                <path
                  d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                </path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
              </svg>
            </button>
          </div>
        </div>



        <div class="form-options">
          <label class="checkbox">
            <input type="checkbox" id="agree" required>
            <span>Saya setuju dengan <a href="javascript:void(0)" onclick="openTncModal()"
                style="cursor: pointer; color: #2563eb; text-decoration: underline;">Syarat & Ketentuan</a></span>
          </label>
        </div>

        <button type="submit" class="auth-btn" id="registerBtn" style="background:#2563eb; border-radius: 6px;">
          <span>Daftar</span>
        </button>
      </form>

      <div class="auth-footer" style="text-align: center; border-top: none;">
        <p>Sudah punya akun? <a href="{{ route('login') }}" style="color: #2563eb; font-weight: bold;">Masuk</a></p>
      </div>
    </div>

    <!-- Sisi Kanan Hero -->
    <div class="split-right">
      <div class="hero-content" style="text-align: center;">
        <div class="hero-logo">
          <img src="{{ asset('assets/logo-pkbm-biru.png') }}" alt="Logo PKBM Harmoni">
        </div>
      </div>
    </div>

  </div>

  <!-- Modal S&K -->
  <div id="tncModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Syarat & Ketentuan</h2>
        <button onclick="closeTncModal()" class="close-modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Selamat datang di Pusat Kegiatan Belajar Masyarakat (PKBM) Harmoni.</p>
        <p>Sebelum melanjutkan pendaftaran, mohon membaca dan menyetujui ketentuan berikut:</p>
        <ol>
          <li><strong>Kebenaran Data</strong>: Calon Peserta Didik menjamin bahwa seluruh data yang diisikan dalam
            formulir pendaftaran adalah benar, akurat, dan dapat dipertanggungjawabkan.</li>
          <li><strong>Kepatuhan Aturan</strong>: Bersedia mematuhi segala peraturan, tata tertib, dan kebijakan yang
            berlaku di lingkungan PKBM Harmoni demi kelancaran proses belajar mengajar.</li>
          <li><strong>Dokumen Pendukung</strong>: Bersedia melengkapi dokumen administrasi (seperti Ijazah, KK, Akta
            Kelahiran) serta surat pindah (jika mutasi) sesuai dengan jadwal yang ditentukan.</li>
          <li><strong>Integritas Akademik</strong>: Berkomitmen untuk mengikuti proses pembelajaran dengan jujur dan
            tidak melakukan plagiarisme atau kecurangan dalam bentuk apapun.</li>
          <li><strong>Penggunaan Data</strong>: Menyetujui bahwa data yang diberikan akan digunakan oleh pihak sekolah
            untuk keperluan administrasi akademik dan pelaporan ke dinas terkait (Dapodik).</li>
        </ol>
        <p>Dengan mencentang kotak "Saya setuju", Anda menyatakan telah membaca, memahami, dan menyepakati seluruh
          syarat dan ketentuan di atas.</p>
      </div>
      <div class="modal-footer">
        <button onclick="acceptTnc()" class="auth-btn" style="width: auto; padding: 10px 24px;">Saya Setuju</button>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/js/siswa-auth.js') }}"></script>
</body>

</html>
