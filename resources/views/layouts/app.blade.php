<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'PKBM Harmoni - Penerimaan Peserta Didik Baru')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/siswa-beranda.css?v=2.0') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('styles')
    @yield('head_scripts')

    <style>
        /* Shared logout styles */
        .logout-confirm-btn {
            border-radius: 10px !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3) !important;
            transition: all 0.2s ease !important;
        }

        .logout-confirm-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4) !important;
        }

        .logout-cancel-btn {
            border-radius: 10px !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            background: #f1f5f9 !important;
            color: #64748b !important;
            transition: all 0.2s ease !important;
        }

        /* Mobile Global Refinements */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px !important;
            }

            .brand img {
                width: 36px !important;
                height: 36px !important;
            }

            .responsive-brand-text .t1 {
                font-size: 14px !important;
            }

            .user-greeting {
                display: block !important;
                max-width: 80px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                font-size: 12px !important;
            }

            .nav-right {
                gap: 5px !important;
            }

            #navStatus {
                font-size: 10px !important;
                padding: 3px 8px !important;
            }

            .brand-wrapper {
                min-width: 0 !important;
                flex: 1 !important;
            }

            .nav-right {
                flex-shrink: 0 !important;
            }
        }

        @media (max-width: 480px) {
            .responsive-brand-text {
                display: none !important;
            }

            .topbar .container {
                padding: 0 6px !important;
                /* Extremely tight padding for mobile */
            }

            .brand img {
                width: 28px !important;
                /* Smaller logo */
                height: 28px !important;
            }

            .brand-wrapper {
                gap: 4px !important;
            }

            .auth-buttons .btn.primary,
            .auth-buttons .btn.outline {
                padding: 4px 8px !important;
                font-size: 10px !important;
            }

            .auth-buttons {
                gap: 2px !important;
            }

            /* Logged in Nav Refinements */
            #userButtons {
                gap: 3px !important;
            }

            #navStatus {
                font-size: 8px !important;
                padding: 2px 4px !important;
                border-radius: 4px !important;
                white-space: nowrap !important;
            }

            .status-prefix {
                display: none !important;
            }

            #notifBtn,
            .action-group .icon-btn {
                width: 28px !important;
                height: 28px !important;
                border-radius: 6px !important;
            }

            #notifBtn svg,
            .action-group .icon-btn svg {
                width: 14px !important;
                height: 14px !important;
            }

            .action-group {
                gap: 2px !important;
            }

            .action-group .btn.primary {
                padding: 4px 8px !important;
                font-size: 10px !important;
            }

            #topRightActions {
                gap: 3px !important;
            }

            .user-greeting {
                max-width: 50px !important;
                /* Aggressive shrink for user name */
                font-size: 9px !important;
                margin-right: 2px !important;
            }

            .greeting-text {
                display: none !important;
            }

            .btn-hamburger {
                width: 28px !important;
                height: 28px !important;
                padding: 2px !important;
            }

            .btn-hamburger svg {
                width: 16px !important;
                height: 16px !important;
            }
        }

        @media (max-width: 360px) {
            .user-greeting {
                max-width: 40px !important;
                font-size: 8px !important;
            }

            .action-group .btn.primary {
                padding: 3px 6px !important;
                font-size: 9px !important;
            }

            #navStatus {
                font-size: 7px !important;
            }
        }
    </style>
</head>

<body>
    @section('header')
    <header class="topbar">
        <div class="container" style="max-width: none; padding: 0 40px; width: 100%;">
            <div class="nav-wrap">
                <div class="brand-wrapper" style="display: flex; align-items: center; gap: 12px;">
                    <button class="btn-hamburger" onclick="toggleMobileNav()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>

                    <div class="brand">
                        <img src="{{ asset('assets/logo-pkbm.png') }}" alt="Logo PKBM Harmoni">
                        <div class="responsive-brand-text">
                            <div class="t1">PKBM Harmoni</div>
                            <div class="t2">Penerimaan Peserta Didik Baru</div>
                            <div class="t3">Informasi Paket B (SMP) & Paket C (SMA)</div>
                        </div>
                    </div>
                </div>

                <nav class="nav nav-center">
                    <a href="{{ url('/#homeSection') }}">Home</a>
                    <a href="{{ url('/#profil-guru') }}">Profil Guru</a>
                    <a href="{{ url('/#program') }}">Program</a>
                    <a href="{{ url('/#alur') }}">Alur</a>
                    <a href="{{ url('/#galeri') }}">Galeri</a>
                    <a href="{{ url('/#faq') }}">FAQ</a>
                    <a href="{{ url('/#kontak') }}">Kontak</a>
                    <a href="{{ url('/pendaftaran?mode=cek_status') }}" id="navCekStatus" style="display:none">Cek
                        Status</a>
                </nav>

                <div class="nav-right" id="topRightActions">
                    @auth
                            <div id="userButtons" class="user-control-group"
                                style="display: flex; align-items: center; gap: 8px;">
                                @php
                                    $registration = auth()->user()->registrations()->latest()->first();
                                    $status = $registration ? $registration->status : null;

                                    $statusLabel = 'Belum Daftar';
                                    $statusClass = 'bg-gray-100 text-gray-600';

                                    if ($status == 'MENUNGGU' || $status == 'pending') {
                                        $statusLabel = 'Menunggu';
                                        $statusClass = 'bg-yellow-100 text-yellow-700';
                                    } elseif ($status == 'DIVERIFIKASI' || $status == 'verifikasi') {
                                        $statusLabel = 'Diverifikasi';
                                        $statusClass = 'bg-blue-100 text-blue-700';
                                    } elseif ($status == 'DITERIMA' || $status == 'diterima') {
                                        $statusLabel = 'Diterima';
                                        $statusClass = 'bg-green-100 text-green-700';
                                    } elseif ($status == 'DITOLAK' || $status == 'ditolak') {
                                        $statusLabel = 'Ditolak';
                                        $statusClass = 'bg-red-100 text-red-700';
                                    }
                                @endphp

                                <span id="navStatus" style="padding:4px 10px; border-radius:8px; font-size:11px; font-weight:700; 
                                                    @if($status == 'DITERIMA' || $status == 'diterima') background: #10b981; color: white;
                                                    @elseif($status == 'MENUNGGU' || $status == 'pending') background: #f59e0b; color: white;
                                                    @elseif($status == 'DIVERIFIKASI' || $status == 'verifikasi') background: #3b82f6; color: white;
                                                    @elseif($status == 'DITOLAK' || $status == 'ditolak') background: #ef4444; color: white;
                                                    @else background: #6b7280; color: white; @endif">
                                    <span class="status-prefix">Status :</span> {{ ucfirst($statusLabel) }}
                                </span>

                                <!-- Notification Bell -->
                                <div class="notification-wrapper" style="position: relative;">
                                    <style>
                                        @keyframes notifPulse {
                                            0% {
                                                transform: scale(1);
                                                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.5);
                                            }

                                            70% {
                                                transform: scale(1.05);
                                                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
                                            }

                                            100% {
                                                transform: scale(1);
                                                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
                                            }
                                        }

                                        .notif-striking {
                                            background-color: #ef4444 !important;
                                            color: white !important;
                                            animation: notifPulse 2s infinite;
                                            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
                                        }
                                    </style>
                                    <button id="notifBtn"
                                        class="icon-btn @if($registration && $registration->catatan && !$registration->catatan_read) notif-striking @endif"
                                        onclick="showNotification()" title="Notifikasi"
                                        style="width:40px; height:40px; background-color:#f1f5f9; color:#475569; border-radius: 10px; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer; position: relative; transition: all 0.3s ease;">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                        </svg>
                                        @if($registration && $registration->catatan && !$registration->catatan_read)
                                            <span id="notifDot"
                                                style="position: absolute; top: -2px; right: -2px; width: 12px; height: 12px; background: #ffffff; border-radius: 50%; border: 3px solid #ef4444;"></span>
                                        @endif
                                    </button>
                                </div>

                                <span class="user-greeting" style="margin-right:4px;"><span class="greeting-text">Halo,
                                    </span><strong>{{ auth()->user()->name }}</strong></span>

                                <div class="action-group" style="display:flex; align-items:center; gap:8px;">
                                    @if(in_array($status, ['DITERIMA', 'diterima', 'DIVERIFIKASI', 'verifikasi', 'MENUNGGU', 'pending']))
                                        <!-- Tombol Data Anda dihilangkan, siswa bisa akses via tombol di Hero Section -->
                                    @else
                                        <a class="btn primary" href="{{ url('/pendaftaran') }}"
                                            style="padding: 8px 14px; background: #2563eb;">Daftar</a>
                                    @endif

                                    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:none">
                                        @csrf
                                    </form>
                                    <button class="icon-btn" onclick="confirmLogout()" title="Keluar"
                                        style="width:36px; height:36px; background-color:#ef4444; color:white; border-radius: 8px; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                            <polyline points="16,17 21,12 16,7" />
                                            <line x1="21" y1="12" x2="9" y2="12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <script>
                                window.USER_REGISTRATION = @json($registration);

                                async function showNotification() {
                                    const reg = window.USER_REGISTRATION;
                                    if (!reg) {
                                        Swal.fire({
                                            title: 'Tidak ada notifikasi',
                                            text: 'Belum ada pesan atau catatan dari admin saat ini.',
                                            icon: 'info',
                                            confirmButtonColor: '#2563eb'
                                        });
                                        return;
                                    }

                                    // Populate Modal Data
                                    document.getElementById('notif-nomor').textContent = reg.nomor_pendaftaran || '-';
                                    document.getElementById('notif-nama').textContent = reg.nama || '-';
                                    document.getElementById('notif-paket').textContent = (reg.paket === 'C' ? 'Paket C (SMA)' : 'Paket B (SMP)') || '-';
                                    document.getElementById('notif-status').textContent = reg.status || 'MENUNGGU';
                                    document.getElementById('notif-hp').textContent = reg.hp || '-';
                                    document.getElementById('notif-email').textContent = reg.email || '-';
                                    document.getElementById('notif-sekolah').textContent = reg.sekolah_asal || '-';

                                    // Format Date
                                    if (reg.created_at) {
                                        const d = new Date(reg.created_at);
                                        document.getElementById('notif-tgl').textContent = `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`;
                                    }

                                    // Show/Hide Izin Edit Box
                                    const izinBox = document.getElementById('notif-izin-box');
                                    if (reg.edit_allowed == 1 || reg.edit_allowed === true) {
                                        izinBox.style.display = 'block';
                                    } else {
                                        izinBox.style.display = 'none';
                                    }

                                    // Show/Hide Catatan Admin
                                    const catatanBox = document.getElementById('notif-catatan-box');
                                    if (reg.catatan) {
                                        catatanBox.style.display = 'block';
                                        document.getElementById('notif-catatan-content').textContent = reg.catatan;
                                    } else {
                                        catatanBox.style.display = 'none';
                                    }

                                    // Show Modal
                                    const modal = document.getElementById('customNotifModal');
                                    if (modal) modal.style.display = 'flex';

                                    // Show/Hide Request Edit Button Logic
                                    const requestBtn = document.getElementById('notif-request-edit-btn');
                                    const requestStatus = document.getElementById('notif-request-status');

                                    if (reg.status !== 'DITERIMA' && reg.status !== 'diterima' && !reg.edit_allowed) {
                                        if (reg.minta_izin_edit == 1 || reg.minta_izin_edit === true) {
                                            requestBtn.style.display = 'none';
                                            requestStatus.style.display = 'inline-block';
                                        } else {
                                            requestBtn.style.display = 'inline-block';
                                            requestStatus.style.display = 'none';
                                        }
                                    } else {
                                        requestBtn.style.display = 'none';
                                        requestStatus.style.display = 'none';
                                    }

                                    // Mark as Read if unread
                                    const dot = document.getElementById('notifDot');
                                    const btn = document.getElementById('notifBtn');
                                    if (dot) {
                                        try {
                                            const response = await fetch(`/api/registrations/${reg.id}/note-read`, {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Content-Type': 'application/json'
                                                }
                                            });
                                            if (response.ok) {
                                                dot.remove();
                                                if (btn) btn.classList.remove('notif-striking');
                                                window.USER_REGISTRATION.catatan_read = 1;
                                            }
                                        } catch (e) {
                                            console.error('Failed to mark note as read', e);
                                        }
                                    }
                                }

                                function closeNotifModal() {
                                    const modal = document.getElementById('customNotifModal');
                                    if (modal) modal.style.display = 'none';
                                }

                                async function requestEditPermission() {
                                    const reg = window.USER_REGISTRATION;
                                    if (!reg) return;

                                    const confirm = await Swal.fire({
                                        title: 'Minta Izin Edit?',
                                        text: 'Admin akan meninjau permintaan Anda agar Anda dapat memperbaiki data formulir.',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#2563eb',
                                        confirmButtonText: 'Ya, Kirim Permintaan',
                                        cancelButtonText: 'Batal'
                                    });

                                    if (!confirm.isConfirmed) return;

                                    try {
                                        const response = await fetch(`/api/registrations/${reg.id}/request-edit`, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Content-Type': 'application/json'
                                            }
                                        });
                                        const data = await response.json();
                                        if (data.success) {
                                            Swal.fire({
                                                title: 'Berhasil',
                                                text: 'Permintaan edit telah dikirim ke admin.',
                                                icon: 'success',
                                                confirmButtonColor: '#2563eb'
                                            });
                                            document.getElementById('notif-request-edit-btn').style.display = 'none';
                                            document.getElementById('notif-request-status').style.display = 'inline-block';
                                            window.USER_REGISTRATION.minta_izin_edit = true;
                                        } else {
                                            Swal.fire('Gagal', data.message, 'error');
                                        }
                                    } catch (e) {
                                        console.error('Failed to request edit', e);
                                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengirim permintaan.', 'error');
                                    }
                                }
                            </script>
                        </div>
                    @else
                    <div id="authButtons" class="auth-buttons" style="display:flex; gap:8px;">
                        <a class="btn outline" href="{{ url('/login') }}">Masuk</a>
                        <a class="btn primary" href="{{ url('/register') }}">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
        </div>
    </header>

    <div class="mobile-drawer">
        <div
            style="display:flex; justify-content:space-between; align-items:center; padding: 20px 25px; border-bottom: 1px solid #f1f5f9;">
            <div class="brand" style="flex: 1; min-width: 0; display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('assets/logo-pkbm.png') }}" alt="Logo"
                    style="width:32px; height:32px; flex-shrink: 0;">
                <div class="t1"
                    style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 800; color: #1e293b; font-size: 16px;">
                    PKBM Harmoni</div>
            </div>
            <button onclick="toggleMobileNav()"
                style="background:#f1f5f9; border:none; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor:pointer; margin-left: 15px; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="mobile-nav-links" style="display: flex; flex-direction: column; padding: 0 20px;">
            <a href="{{ url('/#homeSection') }}" onclick="toggleMobileNav()">Home</a>
            <a href="{{ url('/#profil-guru') }}" onclick="toggleMobileNav()">Profil Guru</a>
            <a href="{{ url('/#program') }}" onclick="toggleMobileNav()">Program</a>
            <a href="{{ url('/#alur') }}" onclick="toggleMobileNav()">Alur</a>
            <a href="{{ url('/#galeri') }}" onclick="toggleMobileNav()">Galeri</a>
            <a href="{{ url('/#faq') }}" onclick="toggleMobileNav()">FAQ</a>
            <a href="{{ url('/#kontak') }}" onclick="toggleMobileNav()">Kontak</a>
        </div>
    </div>
    <div class="drawer-overlay" onclick="toggleMobileNav()"></div>
    @show

    <main>
        @yield('content')
    </main>

    @section('footer')
    <footer style="background: #0f172a; color: #cbd5e1; padding: 60px 0 0; margin-top: 0;">
        <div class="container">
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-bottom: 40px;">
                <!-- Brand -->
                <div>
                    <div style="display: flex; align-items: center; margin-bottom: 24px;">
                        <img src="{{ asset('assets/logo-pkbm.png') }}" alt="Logo PKBM Harmoni"
                            style="height: 50px; margin-right: 15px;">
                        <div>
                            <div style="font-size: 18px; font-weight: 700; color: white; line-height: 1.2;">PKBM HARMONI
                            </div>
                            <div style="font-size: 13px; color: #94a3b8;">Penerimaan Peserta Didik Baru</div>
                        </div>
                    </div>
                    <p style="font-size: 14px; line-height: 1.6; color: #94a3b8;">
                        Lembaga pendidikan kesetaraan yang berkomitmen mencetak generasi berilmu, mandiri, dan berakhlak
                        mulia. Solusi pendidikan fleksibel untuk masa depan yang lebih baik.
                    </p>
                </div>

                <!-- Navigasi -->
                <div>
                    <h4 style="color: white; font-size: 16px; font-weight: 700; margin-bottom: 24px;">Navigasi</h4>
                    <ul
                        style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                        <li><a href="#homeSection"
                                style="text-decoration: none; color: #cbd5e1; font-size: 14px; transition: all 0.2s;">Beranda</a>
                        </li>
                        <li><a href="#profil-guru"
                                style="text-decoration: none; color: #cbd5e1; font-size: 14px; transition: all 0.2s;">Profil
                                Guru</a></li>
                        <li><a href="#program"
                                style="text-decoration: none; color: #cbd5e1; font-size: 14px; transition: all 0.2s;">Program</a>
                        </li>
                        <li><a href="#alur"
                                style="text-decoration: none; color: #cbd5e1; font-size: 14px; transition: all 0.2s;">Alur
                                Pendaftaran</a></li>
                        <li><a href="#faq"
                                style="text-decoration: none; color: #cbd5e1; font-size: 14px; transition: all 0.2s;">FAQ</a>
                        </li>
                        <li><a href="#kontak"
                                style="text-decoration: none; color: #cbd5e1; font-size: 14px; transition: all 0.2s;">Kontak</a>
                        </li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h4 style="color: white; font-size: 16px; font-weight: 700; margin-bottom: 24px;">Hubungi Kami</h4>
                    <ul
                        style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 15px;">
                        <li style="display: flex; gap: 12px; font-size: 14px; align-items: flex-start; color: #cbd5e1;">
                            <div style="margin-top: 4px; flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <span style="line-height: 1.6;">Kotayasa RT 006 RW 006, Kecamatan Sumbang, Kabupaten
                                Banyumas, Jawa Tengah</span>
                        </li>
                        <li style="display: flex; gap: 12px; font-size: 14px; align-items: center; color: #cbd5e1;">
                            <div style="flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l2.27-2.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>
                            </div>
                            <span>+62 858-7597-8865</span>
                        </li>
                        <li style="display: flex; gap: 12px; font-size: 14px; align-items: center; color: #cbd5e1;">
                            <div style="flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <span>pkbmharmoni116@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div
                style="border-top: 1px solid #1e293b; padding: 25px 0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
                <div style="font-size: 14px; color: #64748b;">
                    &copy; {{ date('Y') }} <b>PKBM Harmoni</b>. All rights reserved.
                </div>
                <div style="display: flex; gap: 15px;">
                    <a href="https://www.instagram.com/pkbmharmoniofficial/" target="_blank"
                        style="width: 36px; height: 36px; background: #1e293b; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; text-decoration: none; transition: background 0.2s;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/pkbm.harmoni.2025/" target="_blank"
                        style="width: 36px; height: 36px; background: #1e293b; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; text-decoration: none; transition: background 0.2s;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    @show

    @yield('modals')

    @yield('scripts')
    @auth
        <!-- Custom Notification Modal (Moved to bottom to avoid clipping) -->
        <div id="customNotifModal"
            style="display:none; position: fixed; z-index: 100000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); align-items: flex-start; justify-content: center; padding: 40px 20px; overflow-y: auto;">
            <div class="modal-content"
                style="background: white; width: 100%; max-width: 700px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4); position: relative; animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
                <!-- Header -->
                <div
                    style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #1e293b;">Notifikasi</h3>
                    <button onclick="closeNotifModal()"
                        style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="modal-body-scroll" style="padding: 25px;">
                    <!-- Izin Perubahan Data Box (Green) -->
                    <div id="notif-izin-box"
                        style="display:none; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                        <div style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px;">
                            <div style="color: #16a34a; margin-top: 2px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 style="margin: 0 0 5px 0; font-size: 15px; font-weight: 700; color: #166534;">Izin
                                    Perubahan Data Disetujui</h4>
                                <p style="margin: 0; font-size: 13px; color: #15803d; line-height: 1.6;">
                                    Permintaan Anda untuk memperbarui informasi pendaftaran telah <strong>disetujui</strong>
                                    oleh Administrator. Anda sekarang dapat mengakses kembali formulir pendaftaran untuk
                                    melakukan perbaikan data yang diperlukan.
                                </p>
                            </div>
                        </div>
                        <a href="{{ url('/pendaftaran') }}"
                            style="display: inline-block; background: #10b981; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700; transition: background 0.2s;">Edit
                            Data Sekarang</a>
                    </div>

                    <!-- Info Grid -->
                    <div class="modal-body-grid"
                        style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 25px;">
                        <!-- Column 1 -->
                        <div>
                            <h5 style="margin: 0 0 15px 0; font-size: 14px; font-weight: 700; color: #2563eb;">Informasi
                                Pribadi</h5>
                            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #64748b;">Nomor:</span>
                                    <span id="notif-nomor" style="font-weight: 700; color: #1e293b;">-</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #64748b;">Nama:</span>
                                    <span id="notif-nama" style="font-weight: 700; color: #1e293b;">-</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #64748b;">Paket:</span>
                                    <span id="notif-paket" style="font-weight: 700; color: #1e293b;">-</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: #64748b;">Status:</span>
                                    <span id="notif-status"
                                        style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 12px; font-weight: 700; font-size: 11px;">MENUNGGU</span>
                                </div>
                            </div>
                        </div>
                        <!-- Column 2 -->
                        <div>
                            <h5 style="margin: 0 0 15px 0; font-size: 14px; font-weight: 700; color: #2563eb;">Kontak &
                                Sekolah</h5>
                            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #64748b;">HP:</span>
                                    <span id="notif-hp" style="font-weight: 700; color: #1e293b;">-</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #64748b;">Email:</span>
                                    <span id="notif-email" style="font-weight: 700; color: #1e293b;">-</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #64748b;">Asal Sekolah:</span>
                                    <span id="notif-sekolah" style="font-weight: 700; color: #1e293b;">-</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #64748b;">Tgl Daftar:</span>
                                    <span id="notif-tgl" style="font-weight: 700; color: #1e293b;">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Admin (Orange) -->
                    <div id="notif-catatan-box"
                        style="display:none; background: #fffaf5; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px;">
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px; color: #c2410c;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <h4 style="margin: 0; font-size: 14px; font-weight: 700;">Catatan dari Admin</h4>
                        </div>
                        <p id="notif-catatan-content" style="margin: 0; font-size: 14px; color: #9a3412; line-height: 1.6;">
                            -</p>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    style="padding: 15px 25px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <button id="notif-request-edit-btn" onclick="requestEditPermission()"
                            style="display:none; background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s;">Minta
                            Izin Edit</button>
                        <span id="notif-request-status"
                            style="display:none; font-size: 13px; font-weight: 600; color: #f59e0b; align-items: center; gap: 6px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            Menunggu Izin Admin...
                        </span>
                    </div>
                    <button onclick="closeNotifModal()"
                        style="background: white; border: 1px solid #e2e8f0; color: #475569; padding: 10px 25px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s;">Tutup</button>
                </div>
            </div>
        </div>

        <style>
            @keyframes modalSlideUp {
                from {
                    transform: translateY(40px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            /* Ensure SweetAlert appears above the custom modal */
            .swal2-container {
                z-index: 110000 !important;
            }
        </style>
    @endauth

    <script>
        function toggleMobileNav() {
            const drawer = document.querySelector('.mobile-drawer');
            const overlay = document.querySelector('.drawer-overlay');
            drawer.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Keluar Akun?',
                text: "Anda perlu masuk kembali untuk mengakses fitur pendaftaran.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'logout-confirm-btn',
                    cancelButton: 'logout-cancel-btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }
    </script>
</body>

</html>