<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login - PKBM Harmoni</title>
    <style>
        :root {
            --bg: #f6f7fb;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --line: #e5e7eb;

            /* biru sebagai warna utama */
            --primary-50: #eef4ff;
            --primary-600: #2563eb;
            /* biru */
            --primary-700: #1d4ed8;
            /* biru lebih gelap */
            --primary-500: #3b82f6;
            /* biru */

            --radius: 18px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
        }

        .card {
            width: min(1100px, 100%);
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1.25fr;
            box-shadow: 0 10px 25px rgba(17, 24, 39, .08);
        }

        /* LEFT */
        .left {
            padding: 32px 30px;
        }

        .badge {
            color: var(--primary-600);
            font-weight: 800;
            font-size: 14px;
            margin-top: 6px;
        }

        .left h1 {
            margin: 10px 0 6px;
            font-size: 30px;
            line-height: 1.2;
        }

        .left .sub {
            margin: 0 0 22px;
            color: var(--muted);
        }

        label {
            display: block;
            margin-top: 14px;
            font-weight: 700;
            color: #374151;
        }

        .field {
            position: relative;
            margin-top: 8px;
        }

        input {
            width: 100%;
            padding: 12px 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            outline: none;
            font-size: 14px;
            background: #fff;
        }

        input:focus {
            border-color: rgba(37, 99, 235, .45);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
        }

        .toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 6px;
            color: #6b7280;
        }

        .toggle svg {
            width: 18px;
            height: 18px;
        }

        .btn {
            width: 100%;
            border: none;
            padding: 12px 14px;
            border-radius: 12px;
            background: var(--primary-600);
            color: #fff;
            font-weight: 800;
            cursor: pointer;
            font-size: 15px;
            margin-top: 16px;
            box-shadow: 0 10px 20px rgba(37, 99, 235, .18);
        }

        .btn:hover {
            background: var(--primary-700);
        }

        /* status: disembunyikan dulu, tampil kalau ada pesan */
        .msg {
            display: none;
            margin-top: 14px;
            font-size: 13px;
            color: var(--muted);
            white-space: pre-wrap;
            background: #f9fafb;
            border: 1px dashed var(--line);
            border-radius: 12px;
            padding: 10px 12px;
        }

        .msg.show {
            display: block;
        }

        .msg.ok {
            border-style: solid;
            border-color: rgba(37, 99, 235, .35);
            background: rgba(37, 99, 235, .06);
            color: #1f2937;
        }

        .msg.err {
            border-style: solid;
            border-color: rgba(239, 68, 68, .35);
            background: rgba(239, 68, 68, .06);
            color: #1f2937;
        }

        /* RIGHT */
        .right {
            background: var(--primary-50);
            border-left: 1px solid var(--line);
            padding: 34px 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 14px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 10px;
        }

        .logo {
            width: 70px;
            height: 70px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .caps {
            letter-spacing: .24em;
            font-weight: 900;
            color: var(--primary-600);
            font-size: 13px;
        }

        .brand-name {
            font-weight: 900;
            font-size: 22px;
            margin-top: 2px;
        }

        /* DIPERBAIKI: judul kanan biar rapi */
        .title {
            font-size: 40px;
            /* dari 44 -> 40 biar ga kepanjangan turun */
            line-height: 1.12;
            margin: 6px 0 4px;
            max-width: 560px;
            /* kontrol lebar teks */
            letter-spacing: -0.02em;
            word-break: keep-all;
            /* lebih enak untuk bahasa Indonesia */
        }

        .desc {
            margin: 0;
            color: #6b7280;
            max-width: 560px;
            line-height: 1.55;
        }

        @media (max-width: 900px) {
            .card {
                grid-template-columns: 1fr;
            }

            .right {
                border-left: none;
                border-top: 1px solid var(--line);
            }

            .title {
                font-size: 32px;
                max-width: 100%;
            }

            .desc {
                max-width: 100%;
            }
        }

        /* Mobile specific: Reverse order (Info on top, Login on bottom) */
        @media (max-width: 767px) {
            .card {
                display: flex;
                flex-direction: column-reverse;
            }

            .right {
                border-top: none;
                border-bottom: 1px solid var(--line);
            }
        }


        /* Modal Welcome */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-box {
            background: white;
            padding: 32px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.95);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-overlay.active .modal-box {
            transform: scale(1);
        }

        .modal-icon {
            width: 64px;
            height: 64px;
            background: #dbeafe;
            color: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .modal-icon svg {
            width: 32px;
            height: 32px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 8px;
        }

        .modal-desc {
            color: #64748b;
            font-size: 14px;
            margin: 0 0 24px;
            line-height: 1.5;
        }

        .modal-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
        }

        .modal-btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">

            <!-- LEFT (FORM LOGIN) -->
            <div class="left">
                <!-- tombol back dihapus -->

                <div class="badge">Admin Login</div>
                <h1>Masuk Untuk Mengelola</h1>
                <p class="sub">Gunakan Akun Admin PKBM Untuk Mengakses Dashboard.</p>

                <form id="formLogin">
                    <label for="username">Username</label>
                    <div class="field">
                        <input id="username" name="username" placeholder="Admin" autocomplete="username" required />
                    </div>

                    <label for="password">Password</label>
                    <div class="field">
                        <input id="password" name="password" type="password" placeholder="••••••••"
                            autocomplete="current-password" required />
                        <button class="toggle" type="button" id="togglePw" aria-label="Tampilkan password">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>

                    <button class="btn" type="submit">Masuk Dashboard Admin</button>
                </form>

                <div id="msg" class="msg"></div>
            </div>

            <!-- RIGHT (INFO PANEL) -->
            <div class="right">
                <div class="brand">
                    <div class="logo">
                        <img src="{{ asset('assets/logo-pkbm.png') }}" alt="Logo PKBM Harmoni" />
                    </div>
                    <div>
                        <div class="caps">ADMIN PORTAL</div>
                        <div class="brand-name">PKBM Harmoni</div>
                        <div class="caps" style="margin-top:6px;">MANAJEMEN INTERNAL</div>
                    </div>
                </div>

                <div class="title">Selamat Datang di Aplikasi Pengelolaan PKBM Harmoni</div>
                <p class="desc">
                    Dashboard Terpusat Untuk Kebutuhan Administrasi Pendaftaran.
                </p>
            </div>

        </div>
    </div>

    <!-- WELCOME MODAL -->
    <div id="welcomeModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h3 class="modal-title">Login Berhasil!</h3>
            <p class="modal-desc">Selamat datang kembali, Admin.<br>Mengalihkan Anda ke dashboard...</p>
            <button class="modal-btn" onclick="goToDashboard()">Masuk Sekarang</button>
        </div>
    </div>

    <script>
        const API_LOGIN = "{{ route('admin.login.post') }}";
        const msgEl = document.getElementById("msg");

        const showMsg = (text, type) => {
            msgEl.textContent = text;
            msgEl.className = "msg show " + (type || "");
        };
        const hideMsg = () => {
            msgEl.textContent = "";
            msgEl.className = "msg";
        };

        document.getElementById("togglePw").onclick = () => {
            const pw = document.getElementById("password");
            pw.type = (pw.type === "password") ? "text" : "password";
        };

        function goToDashboard() {
            window.location.href = "{{ route('admin.dashboard') }}";
        }

        document.getElementById("formLogin").addEventListener("submit", async (e) => {
            e.preventDefault();
            hideMsg();

            const username = document.getElementById("username").value.trim();
            const password = document.getElementById("password").value;
            const btn = e.target.querySelector('button[type="submit"]');

            btn.disabled = true;
            btn.innerHTML = "Memproses...";

            try {
                const res = await fetch(API_LOGIN, {
                    method: "POST",
                    credentials: 'same-origin',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await res.json();

                if (!res.ok) {
                    showMsg("Gagal login: " + (data.message || "Unknown error"), "err");
                    btn.disabled = false;
                    btn.innerHTML = "Masuk Dashboard Admin";
                    return;
                }

                sessionStorage.setItem("token_admin", data.token);

                // Show Modal
                const modal = document.getElementById('welcomeModal');
                modal.classList.add('active');

                // Auto redirect after 2s
                setTimeout(() => {
                    goToDashboard();
                }, 2000);

            } catch (err) {
                showMsg("Error: " + err.message, "err");
                btn.disabled = false;
                btn.innerHTML = "Masuk Dashboard Admin";
            }
        });
    </script>

</body>

</html>