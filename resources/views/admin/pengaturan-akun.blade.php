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
            <p>Perbarui informasi identitas akun admin Anda.</p>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" value="Admin PKBM Harmoni">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" value="admin" readonly style="background: #f8fafc; cursor: not-allowed;">
            </div>
            <button class="btn primary">Simpan Profil</button>
        </div>

        <div class="form-section">
            <h3>Keamanan Akun</h3>
            <p>Ganti password secara berkala untuk menjaga keamanan akun.</p>
            <div class="form-group">
                <label>Password Saat Ini</label>
                <input type="password" class="form-control" placeholder="••••••••">
            </div>
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" class="form-control" placeholder="••••••••">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" class="form-control" placeholder="••••••••">
            </div>
            <button class="btn primary">Perbarui Password</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Placeholder logic for settings
    document.querySelectorAll('button.primary').forEach(btn => {
        btn.onclick = () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Pengaturan Anda telah diperbarui (simulasi).',
                confirmButtonColor: '#2563eb'
            });
        };
    });
</script>
@endsection
