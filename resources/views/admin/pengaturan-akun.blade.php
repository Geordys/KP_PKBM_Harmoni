@extends('layouts.admin')

@section('title', 'Pengaturan Akun')

@section('styles')
<style>
    .settings-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 32px; }
    
    .profile-card { text-align: center; }
    .profile-img { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; }
    
    .form-section { margin-bottom: 32px; padding-bottom: 32px; border-bottom: 1px solid var(--line); }
    .form-section:last-child { border-bottom: none; }
    .form-section h3 { font-size: 16px; margin: 0 0 8px; font-weight: 700; color: #1e293b; }
    .form-section p { font-size: 13px; color: var(--muted); margin-bottom: 20px; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: #475569; }
    .form-control {
        width: 100%; padding: 12px 14px; border: 1.5px solid var(--line); border-radius: 12px;
        font-size: 14px; transition: all 0.2s; background: #fcfdfe;
    }
    .form-control:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px var(--primarySoft); outline: none; }
    
    @media (max-width: 900px) {
        .settings-grid { grid-template-columns: 1fr; }
    }
    
    .password-wrapper { position: relative; }
    .password-toggle { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b; }
    .password-toggle:hover { color: #2563eb; }
</style>
@endsection

@section('content')
<div class="page-header" style="margin-bottom: 32px;">
    <h1 style="font-size: 24px; margin: 0; letter-spacing: -0.02em;">Pengaturan Akun</h1>
    <p style="color: var(--muted); font-size: 14px; margin-top: 4px;">Kelola profil admin dan keamanan password Anda.</p>
</div>

<div style="max-width: 700px;">

    <div class="card">
        <div class="form-section">
            <h3>Informasi Profil</h3>
            <p>Perbarui username untuk login akun admin Anda.</p>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" id="profile-username" value="{{ auth()->user()->username }}">
            </div>
            <button class="btn primary" id="btn-save-profile">Simpan Profil</button>
        </div>

        <div class="form-section">
            <h3>Keamanan Akun</h3>
            <p>Ganti password secara berkala untuk menjaga keamanan akun.</p>
            <div class="form-group">
                <label>Password Saat Ini</label>
                <div class="password-wrapper">
                    <input type="password" id="current-password" class="form-control" placeholder="••••••••">
                    <svg class="password-toggle" onclick="togglePassword('current-password')" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
            </div>
            <div class="form-group">
                <label>Password Baru</label>
                <div class="password-wrapper">
                    <input type="password" id="new-password" class="form-control" placeholder="••••••••">
                    <svg class="password-toggle" onclick="togglePassword('new-password')" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm-password" class="form-control" placeholder="••••••••">
                    <svg class="password-toggle" onclick="togglePassword('confirm-password')" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
            </div>
            <button class="btn primary" id="btn-save-password">Perbarui Password</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling;
        if (input.type === "password") {
            input.type = "text";
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            input.type = "password";
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }

    document.getElementById('btn-save-profile').onclick = async () => {
        const username = document.getElementById('profile-username').value;
        if(!username) return Swal.fire('Error', 'Username tidak boleh kosong', 'error');

        try {
            const res = await fetch('{{ route("admin.pengaturan.profil") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ username })
            });
            const data = await res.json();
            if (res.ok) {
                Swal.fire('Berhasil', data.message, 'success');
            } else {
                Swal.fire('Gagal', data.message || 'Gagal memperbarui profil', 'error');
            }
        } catch(e) {
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        }
    };

    document.getElementById('btn-save-password').onclick = async () => {
        const current_password = document.getElementById('current-password').value;
        const new_password = document.getElementById('new-password').value;
        const confirm_password = document.getElementById('confirm-password').value;

        if(!current_password || !new_password || !confirm_password) {
            return Swal.fire('Error', 'Semua kolom password harus diisi', 'error');
        }

        try {
            const res = await fetch('{{ route("admin.pengaturan.password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    current_password: current_password,
                    new_password: new_password,
                    new_password_confirmation: confirm_password
                })
            });
            const data = await res.json();
            if (res.ok) {
                Swal.fire('Berhasil', data.message, 'success').then(() => {
                    document.getElementById('current-password').value = '';
                    document.getElementById('new-password').value = '';
                    document.getElementById('confirm-password').value = '';
                });
            } else {
                Swal.fire('Gagal', data.message || 'Gagal memperbarui password', 'error');
            }
        } catch(e) {
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        }
    };
</script>
@endsection
