<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - PKBM Harmoni</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('head')

    <style>
        :root {
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --primary: #2563eb;
            --primary2: #1d4ed8;
            --primarySoft: rgba(37, 99, 235, .08);
            --success: #16a34a;
            --warning: #f59e0b;
            --radius: 16px;
            --shadow: 0 2px 8px rgba(15, 23, 42, .04);
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

        .layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background: #fff;
            border-right: 1px solid var(--line);
            padding: 24px 20px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .brand {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 12px;
            border-radius: 12px;
            background: rgba(37, 99, 235, .04);
            border: none;
            text-decoration: none;
            color: inherit;
        }

        .brand img {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: #fff;
            object-fit: cover;
        }

        .brand .t1 {
            font-weight: 900;
            letter-spacing: .14em;
            color: var(--primary);
            font-size: 12px;
        }

        .brand .t2 {
            font-weight: 900;
            font-size: 16px;
            margin-top: 2px;
        }

        .brand .t3 {
            color: var(--muted);
            font-size: 12px;
            margin-top: 2px;
        }

        .nav {
            margin-top: 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav a {
            text-decoration: none;
            color: var(--text);
            padding: 10px 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .nav a:hover {
            background: rgba(37, 99, 235, .06);
        }

        .nav a.active {
            background: rgba(37, 99, 235, .1);
            color: var(--primary);
        }

        .nav .ico {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(37, 99, 235, .06);
            color: var(--primary);
            flex: 0 0 auto;
        }

        .nav a.active .ico {
            background: rgba(37, 99, 235, .12);
        }

        .logout-bottom {
            position: absolute;
            left: 16px;
            right: 16px;
            bottom: 16px;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
            transition: all 0.3s ease;
            width: 100%;
            justify-content: center;
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.35);
        }

        .main {
            padding: 32px 40px 60px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
        }

        .btn-hamburger {
            display: none;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid var(--line);
            border-radius: 8px;
            cursor: pointer;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 900px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                left: -280px;
                width: 280px;
                z-index: 1001;
                transition: left 0.3s;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            .btn-hamburger {
                display: flex;
            }

            .main {
                padding: 20px;
            }
        }

        /* Generic Styles From Dashboard */
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 24px;
            transition: all 0.2s ease;
        }

        .btn {
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn.primary {
            background: var(--primary);
            color: #fff;
        }

        .btn.primary:hover {
            background: var(--primary2);
        }

        .btn.ghost {
            background: #fff;
            border: 1px solid var(--line);
            color: var(--text);
        }

        /* Animation for SweetAlert */
        .animated-popup {
            border-radius: 20px !important;
        }

        .logout-confirm-btn {
            border-radius: 12px !important;
            padding: 12px 24px !important;
        }

        .logout-cancel-btn {
            border-radius: 12px !important;
            padding: 12px 24px !important;
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="layout">
        <aside class="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="brand">
                <img src="{{ asset('assets/logo-pkbm.png') }}" alt="Logo PKBM Harmoni">
                <div>
                    <div class="t1">ADMIN PANEL</div>
                    <div class="t2">PKBM Harmoni</div>
                    <div class="t3">@yield('sidebar_subtitle', 'Manajemen Internal')</div>
                </div>
            </a>

            <nav class="nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ Request::is('admin') ? 'active' : '' }}">
                    <div class="ico">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 13h8V3H3v10zM13 21h8V11h-8v10zM13 3h8v6h-8V3zM3 21h8v-6H3v6z" />
                        </svg>
                    </div>
                    Dashboard
                </a>

                <a href="{{ route('admin.pendaftaran') }}"
                    class="{{ Request::is('admin/pendaftaran*') ? 'active' : '' }}">
                    <div class="ico">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 6h18M3 12h18M3 18h18" />
                        </svg>
                    </div>
                    Kelola Pendaftaran
                </a>

                <a href="{{ route('admin.beranda') }}" class="{{ Request::is('admin/beranda*') ? 'active' : '' }}">
                    <div class="ico">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M4 16l4.586-4.586a2 2 0 0 1 2.828 0L16 16m-2-2l1.586-1.586a2 2 0 0 1 2.828 0L20 14m-6-6h.01M6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                        </svg>
                    </div>
                    Kelola Beranda
                </a>

                <a href="{{ route('admin.pengaturan') }}"
                    class="{{ Request::is('admin/pengaturan*') ? 'active' : '' }}">
                    <div class="ico">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.74v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                            </path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </div>
                    Pengaturan Akun
                </a>
            </nav>

            <div class="logout-bottom">
                <a href="#" id="navLogout" class="btn-logout">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <path d="M16 17l5-5-5-5" />
                        <path d="M21 12H9" />
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <main class="main">
            <div class="topbar">
                <button class="btn-hamburger" onclick="toggleSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div id="topbar_content"
                    style="display: flex; align-items: center; justify-content: space-between; flex: 1; gap: 12px;">
                    @yield('topbar_actions')
                </div>
            </div>

            @yield('content')

            <footer
                style="margin-top: 60px; padding: 24px 0; border-top: 1px solid var(--line); color: var(--muted); font-size: 13px; text-align: center;">
                &copy; {{ date('Y') }} PKBM Harmoni. All rights reserved.
            </footer>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        document.getElementById("navLogout").onclick = (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'animated-popup' }
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem("token_admin");
                    sessionStorage.removeItem("token_admin");
                    // Submit form logout for session invalidation
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("logout") }}';
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        };
    </script>
    @yield('scripts')
</body>

</html>