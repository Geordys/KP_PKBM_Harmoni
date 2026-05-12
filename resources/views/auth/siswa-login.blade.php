<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Siswa - PKBM Harmoni</title>
  <link rel="stylesheet" href="{{ asset('assets/css/siswa-auth.css') }}">
  <style>
    .google-btn {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 12px;
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      color: #374151;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      font-size: 16px;
    }

    .google-btn:hover {
      background: #f9fafb;
      border-color: #d1d5db;
    }

    #loginBtn {
      background: #2563eb !important;
    }

    #loginBtn:hover {
      background: #2563eb !important;
    }

    .auth-footer a {
      color: #2563eb !important;
    }
  </style>
</head>

<body>
  <div class="split-layout">

    <!-- Sisi Kiri: Formulir -->
    <div class="split-left">
      <a href="{{ url('/') }}" class="back-btn" title="Kembali">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
      </a>

      <div class="auth-header">
        <h1>Masuk</h1>
      </div>

      <form id="loginForm" class="auth-form">
        @csrf
        <div class="form-group">
          <label for="email">Email</label>
          <div class="password-container">
            <input type="email" id="email" name="email" placeholder="Masukkan Email" required
              style="padding-left: 16px;">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
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

        <button type="submit" class="auth-btn" id="loginBtn" style="background: #10b981; border-radius: 6px;">
          <span>Login</span>
        </button>

        <div style="text-align: center; margin: 20px 0; color: #6b7280; font-size: 14px; font-weight: 500;">
          OR
        </div>

        <a href="{{ url('/auth/google') }}" style="text-decoration: none; width: 100%;">
          <button type="button" class="google-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                fill="#4285F4" />
              <path
                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                fill="#34A853" />
              <path
                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                fill="#FBBC05" />
              <path
                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                fill="#EA4335" />
            </svg>
            <span>Google</span>
          </button>
        </a>

      </form>

      <div class="auth-footer" style="text-align: center; border-top: none;">
        <p>Belum punya akun? <a href="{{ route('register') }}" style="color: #10b981; font-weight: bold;">Daftar</a></p>
      </div>
    </div>

    <!-- Sisi Kanan: Hero -->
    <div class="split-right">
      <div class="hero-content" style="text-align: center;">
        <div class="hero-logo">
          <img src="{{ asset('assets/logo-pkbm-biru.png') }}" alt="Logo PKBM Harmoni">
        </div>
      </div>
    </div>

  </div>

  <!-- Modal Selamat Datang -->
  <div id="welcomeModal" class="modal-overlay">
    <div class="modal-content success-modal">
      <div class="success-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <h2 id="welcomeName">Selamat Datang!</h2>
      <p>Login berhasil. Memuat beranda...</p>
    </div>
  </div>

  <script src="{{ asset('assets/js/siswa-auth.js') }}"></script>
</body>

</html>