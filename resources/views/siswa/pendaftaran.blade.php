@extends('layouts.app')

@section('title', 'Form Pendaftaran - PKBM Harmoni')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/siswa-pendaftaran.css?v=' . time()) }}">
    <style>
        header.topbar {
            display: none !important;
        }

        main.page {
            margin-top: 0;
            padding-top: 18px;
        }

        .head-actions #backToHomeBtn {
            padding: 8px 14px;
        }

        /* Forced Interactivity for Action Buttons */
        #btnSaveData,
        #btnEditKembali,
        #btnSubmitReview,
        #btnReset,
        #btnNext,
        #btnBack {
            cursor: pointer !important;
            pointer-events: auto !important;
            user-select: auto !important;
            z-index: 10000 !important;
        }

        .actions,
        .actions .left,
        .actions .right {
            pointer-events: auto !important;
        }

        /* Aggressively disable interaction for hidden common overlays */
        .drawer-overlay:not(.active),
        .mobile-drawer:not(.active),
        .modal:not(.show),
        #confirmExitModal[style*="display:none"] {
            pointer-events: none !important;
            z-index: -100 !important;
        }
    </style>
@endsection

@section('footer')
    <!-- Tidak ada footer di pendaftaran -->
@endsection


@section('head_scripts')
    <script>
        window.SERVER_AUTH_STATUS = @json(auth()->check());
        window.SERVER_USER_DATA = @json(auth()->user() ?? null);
        window.SERVER_REG_DATA = @json($registration ?? null);
        
        (function () {
            try {
                let foundStatus = null;
                let foundData = window.SERVER_REG_DATA;

                if (window.SERVER_AUTH_STATUS) {
                    if (foundData && foundData.status) {
                        const s = String(foundData.status).toUpperCase();
                        if (['MENUNGGU', 'DIVERIFIKASI', 'DITERIMA'].includes(s)) {
                            foundStatus = s;
                            window.PRELOADED_REG = foundData;
                        }
                    } else {
                        // User terautentikasi tapi TIDAK memiliki registrasi di server
                        // Kita biarkan draft tetap ada agar tidak hilang saat refresh
                    }
                } else {
                    // Fallback ke localStorage hanya jika belum login / belum ada data dari server
                    const userRaw = localStorage.getItem('currentUser');
                    if (userRaw) {
                        const user = JSON.parse(userRaw);
                        let suffix = '';
                        if (user.id) suffix += `_id${user.id}`;
                        if (user.email) suffix += `_em${user.email.replace(/[^a-zA-Z0-9]/g, '')}`;
                        const targetKey = `registration_data_pkbm_harmoni_v1${suffix}`;
                        const v = JSON.parse(localStorage.getItem(targetKey) || 'null');

                        if (v && v.status) {
                            const s = String(v.status).toUpperCase();
                            if (['MENUNGGU', 'DIVERIFIKASI', 'DITERIMA'].includes(s)) {
                                foundStatus = s;
                                foundData = v;
                                window.PRELOADED_REG = v;
                            }
                        }
                    }
                }

                if (foundStatus) {
                    console.log('[Preloader] Found status:', foundStatus);

                    const editAllowed = foundData && (foundData.edit_allowed == 1 || foundData.edit_allowed === true || foundData.edit_allowed === "1");

                    let activeIdx = 0;
                    if (editAllowed) {
                        activeIdx = 0;
                    } else {
                        if (foundStatus === 'MENUNGGU' || foundStatus === 'PENDING') activeIdx = 2;
                        else if (foundStatus === 'DIVERIFIKASI' || foundStatus === 'VERIFIKASI') activeIdx = 3;
                        else if (foundStatus === 'DITERIMA') activeIdx = 4;
                    }

                    const style = document.createElement('style');
                    style.id = 'preloaderHideStyle';
                    style.innerHTML = `
                        .section { display: none !important; }
                        .section[data-step="${activeIdx}"] { display: block !important; }
                        .step { pointer-events: none !important; opacity: 0.6; }
                        .step[data-step="${activeIdx}"] { opacity: 1 !important; border-bottom: 3px solid var(--primary) !important; }
                    `;
                    document.head.appendChild(style);
                    window.PRELOADED_STEP = activeIdx;
                    console.log('[Preloader] Applied lockout style for step:', activeIdx);
                } else {
                    console.log('[Preloader] No status found, showing default form.');
                }
            } catch (e) { 
                console.error('Preloader error', e);
                // On error, ensure we don't have a half-broken style
                const s = document.getElementById('preloaderHideStyle');
                if (s) s.remove();
            }
        })();
    </script>
@endsection

@section('header')
    <!-- Tidak ada top navbar sama sekali di halaman pendaftaran sesuai desain awal -->
@endsection

@section('content')

    <main class="page">
        <div class="container">
            <div class="head-card">
                <div class="head-title">
                    <h1>Pendaftaran Calon Siswa PKBM Harmoni</h1>
                    <p>Isi data dengan benar sesuai dokumen. Jika ada kolom yang belum punya datanya, kamu bisa isi “-”
                        dulu, tapi untuk kolom bertanda <b style="color:var(--danger)">Wajib*</b> mohon diisi.</p>
                </div>
            </div>

            <div class="form-wrap">
                <!-- View Tidak Register -->
                <div id="notRegisteredView" style="display: none; text-align: center; padding: 60px 20px;">
                    <div style="margin-bottom: 20px; color: #cbd5e1;">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <h3 style="margin-bottom: 12px; color: #334155; font-size: 1.5rem;">Belum Ada Pendaftaran</h3>
                    <p
                        style="color: #64748b; margin-bottom: 32px; max-width: 480px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                        Kami tidak menemukan data pendaftaran aktif untuk akun Anda.<br>
                        Silakan mulai pendaftaran baru untuk bergabung dengan PKBM Harmoni.
                    </p>
                    <button class="btn primary" onclick="goToRegistration()" style="padding: 12px 32px; font-size: 1.1rem;">
                        Mulai Pendaftaran Baru
                    </button>
                </div>

                <!-- Progress -->
                <div class="progress" id="progressWrap">
                    <div class="steps" id="steps">
                        <div class="step active" data-step="0">
                            <div class="num">1</div>
                            <div><b>Isi Formulir</b><small>Pendaftaran siswa baru</small></div>
                        </div>
                        <div class="step" data-step="1">
                            <div class="num">2</div>
                            <div><b>Review Data</b><small>Periksa dan konfirmasi data</small></div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="num">3</div>
                            <div><b>Menunggu</b><small>Verifikasi admin</small></div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="num">4</div>
                            <div><b>Diverifikasi</b><small>Data telah dicek</small></div>
                        </div>
                        <div class="step" data-step="4">
                            <div class="num">5</div>
                            <div><b>Diterima</b><small>Selamat bergabung!</small></div>
                        </div>
                    </div>
                </div>

                <div class="body" id="bodyWrap">
                    <div class="alert" id="alert"></div>

                    <!-- STEP 0: ISI FORMULIR -->
                    <div class="section active" data-step="0">
                        <div class="step-wizard-wrap">
                            <div class="wizard-header">
                                <div class="sub-progress-wrap">
                                    <div class="sub-steps-indicator">
                                        <div class="sub-step-item active" data-sub="0">
                                            <div class="dot"></div>
                                            <span>Siswa</span>
                                        </div>
                                        <div class="sub-step-item" data-sub="1">
                                            <div class="dot"></div>
                                            <span>Alamat</span>
                                        </div>
                                        <div class="sub-step-item" data-sub="2">
                                            <div class="dot"></div>
                                            <span>Sekolah</span>
                                        </div>
                                        <div class="sub-step-item" data-sub="3">
                                            <div class="dot"></div>
                                            <span>Orang Tua</span>
                                        </div>
                                    </div>
                                    <div class="progress-bar-bg">
                                        <div class="progress-bar-fill" id="subProgressBar" style="width: 25%;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="wizard-body">
                                <!-- Sub Step 0: Data Diri -->
                                <div class="sub-section active" data-sub="0">

                        <!-- Data Diri -->
                        <div class="form-section">
                            <h4>Data Diri</h4>
                            <div class="grid2">
                                <div class="field">
                                    <label for="nama">Nama Lengkap <span class="req">*</span></label>
                                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="jk">Jenis Kelamin <span class="req">*</span></label>
                                    <select id="jk" name="jk" required>
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="tempat_lahir">Tempat Lahir <span class="req">*</span></label>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir"
                                        placeholder="Masukkan tempat lahir" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="tanggal_lahir">Tanggal Lahir <span class="req">*</span></label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="agama">Agama <span class="req">*</span></label>
                                    <select id="agama" name="agama" required>
                                        <option value="">Pilih agama</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="paket">Paket yang Dipilih <span class="req">*</span></label>
                                    <select id="paket" name="paket" required>
                                        <option value="">Pilih paket</option>
                                        <option value="B">Paket B (Setara SMP)</option>
                                        <option value="C">Paket C (Setara SMA)</option>
                                    </select>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="nisn">NISN <span class="req">*</span></label>
                                    <input type="text" id="nisn" name="nisn"
                                        placeholder="Masukkan NISN" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="nik">NIK (Siswa) <span class="req">*</span></label>
                                    <input type="text" id="nik" name="nik" placeholder="Masukkan NIK" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </div>
                            </div>
                        </div>
                                </div>

                                <!-- Sub Step 1: Alamat -->
                                <div class="sub-section" data-sub="1">
                        <div class="form-section">
                            <h4>Alamat Lengkap</h4>
                            <div class="grid2">
                                <div class="field span-12" data-required="true">
                                    <label for="alamat">Alamat <span class="req">*</span></label>
                                    <textarea id="alamat" name="alamat" placeholder="Masukkan alamat lengkap"
                                        required></textarea>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="rt">RT <span class="req">*</span></label>
                                    <input type="text" id="rt" name="rt" placeholder="RT" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="rw">RW <span class="req">*</span></label>
                                    <input type="text" id="rw" name="rw" placeholder="RW" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="dusun">Dusun <span class="req">*</span></label>
                                    <input type="text" id="dusun" name="dusun" placeholder="Dusun" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="desa">Kelurahan/Desa <span class="req">*</span></label>
                                    <input type="text" id="desa" name="desa" placeholder="Kelurahan/Desa" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="kecamatan">Kecamatan <span class="req">*</span></label>
                                    <input type="text" id="kecamatan" name="kecamatan" placeholder="Kecamatan" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="kode_pos">Kode Pos <span class="req">*</span></label>
                                    <input type="text" id="kode_pos" name="kode_pos" placeholder="Kode Pos" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="jenis_tinggal">Jenis Tinggal <span class="req">*</span></label>
                                    <select id="jenis_tinggal" name="jenis_tinggal" required>
                                        <option value="">Pilih jenis tinggal</option>
                                        <option value="Bersama orang tua">Bersama orang tua</option>
                                        <option value="Wali">Wali</option>
                                        <option value="Kos">Kos</option>
                                        <option value="Asrama">Asrama</option>
                                        <option value="Panti asuhan">Panti asuhan</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="alat_transportasi">Alat Transportasi <span class="req">*</span></label>
                                    <select id="alat_transportasi" name="alat_transportasi" required>
                                        <option value="">Pilih alat transportasi</option>
                                        <option value="Jalan kaki">Jalan kaki</option>
                                        <option value="Sepeda">Sepeda</option>
                                        <option value="Sepeda motor">Sepeda motor</option>
                                        <option value="Mobil pribadi">Mobil pribadi</option>
                                        <option value="Angkutan umum">Angkutan umum</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="jenis_bank">Jenis Bank <span class="req">*</span></label>
                                    <select id="jenis_bank" name="jenis_bank" required onchange="window.toggleBankLainnya && window.toggleBankLainnya()">
                                        <option value="">Pilih jenis bank</option>
                                        <option value="BRI">BRI</option>
                                        <option value="BCA">BCA</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="BNI">BNI</option>
                                        <option value="CIMB Niaga">CIMB Niaga</option>
                                        <option value="Danamon">Danamon</option>
                                        <option value="Permata">Permata</option>
                                        <option value="BSI">BSI</option>
                                        <option value="BTN">BTN</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="field" id="field_jenis_bank_lainnya" style="display: none;">
                                    <label for="jenis_bank_lainnya">Ketik Nama Bank <span class="req">*</span></label>
                                    <input type="text" id="jenis_bank_lainnya" name="jenis_bank_lainnya" placeholder="Masukkan nama bank Anda">
                                </div>
                                <div class="field" data-required="true">
                                    <label for="no_rekening">No. Rekening <span class="req">*</span></label>
                                    <input type="text" id="no_rekening" name="no_rekening"
                                        placeholder="Masukkan nomor rekening" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="telepon">Telepon <span class="req">*</span></label>
                                    <input type="tel" id="telepon" name="telepon" placeholder="Nomor telepon rumah"
                                        required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="hp">HP/WhatsApp <span class="req">*</span></label>
                                    <input type="tel" id="hp" name="hp" placeholder="Masukkan nomor HP/WhatsApp" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="email">Email <span class="req">*</span></label>
                                    <input type="email" id="email" name="email" placeholder="Masukkan alamat email"
                                        value="{{ auth()->user()->email ?? '' }}"
                                        @auth readonly style="background-color: #f8fafc; cursor: not-allowed;" title="Email terkunci sesuai akun Anda" @endauth
                                        required>
                                </div>
                            </div>
                        </div>
                                </div>

                                <!-- Sub Step 2: Sekolah -->
                                <div class="sub-section" data-sub="2">
                        <div class="form-section">
                            <h4>Sekolah Asal & Bantuan</h4>
                            <div class="grid2">
                                <div class="field" data-required="true">
                                    <label for="sekolah_asal">Sekolah Asal <span class="req">*</span></label>
                                    <input type="text" id="sekolah_asal" name="sekolah_asal" placeholder="Nama sekolah asal"
                                        required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="skhun">SKHUN <span class="req">*</span></label>
                                    <input type="text" id="skhun" name="skhun" placeholder="Nomor SKHUN" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </div>
                                <div class="field">
                                    <label>Penerima KPS/KIP/PKH?</label>
                                    <div class="radio-row">
                                        <label><input type="radio" name="penerima_kps" value="Ya"> Ya</label>
                                        <label><input type="radio" name="penerima_kps" value="Tidak" checked> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                                </div>

                                <!-- Sub Step 3: Orang Tua -->
                                <div class="sub-section" data-sub="3">
                        <div class="form-section">
                            <h4>Data Orang Tua</h4>
                            <h5>Ayah</h5>
                            <div class="grid2">
                                <div class="field" data-required="true">
                                    <label for="nama_ayah">Nama Ayah <span class="req">*</span></label>
                                    <input type="text" id="nama_ayah" name="nama_ayah" placeholder="Nama lengkap ayah"
                                        required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="tanggal_lahir_ayah">Tahun Lahir Ayah <span class="req">*</span></label>
                                    <input type="number" id="tanggal_lahir_ayah" name="tanggal_lahir_ayah"
                                        placeholder="Tahun Lahir" min="1900" max="2030" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="nik_ayah">NIK Ayah <span class="req">*</span></label>
                                    <input type="text" id="nik_ayah" name="nik_ayah" placeholder="NIK ayah" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="pendidikan_ayah">Jenjang Pendidikan Ayah <span class="req">*</span></label>
                                    <select id="pendidikan_ayah" name="pendidikan_ayah" required>
                                        <option value="">Pilih pendidikan</option>
                                        <option value="Tidak sekolah">Tidak sekolah</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                        <option value="D1">D1</option>
                                        <option value="D2">D2</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="pekerjaan_ayah">Pekerjaan Ayah <span class="req">*</span></label>
                                    <input type="text" id="pekerjaan_ayah" name="pekerjaan_ayah"
                                        placeholder="Pekerjaan ayah" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="penghasilan_ayah">Penghasilan Ayah <span class="req">*</span></label>
                                    <select id="penghasilan_ayah" name="penghasilan_ayah" required>
                                        <option value="">Pilih penghasilan</option>
                                        <option value="<500000">&lt; Rp 500.000</option>
                                        <option value="500000-1000000">Rp 500.000 - Rp 1.000.000</option>
                                        <option value="1000000-2000000">Rp 1.000.000 - Rp 2.000.000</option>
                                        <option value="2000000-5000000">Rp 2.000.000 - Rp 5.000.000</option>
                                        <option value=">5000000">&gt; Rp 5.000.000</option>
                                    </select>
                                </div>
                                <div class="field span-12" data-required="true">
                                    <label for="ayah_alamat">Alamat Ayah <span class="req">*</span></label>
                                    <textarea id="ayah_alamat" name="ayah_alamat" placeholder="Masukkan alamat lengkap ayah"
                                        required></textarea>
                                </div>
                            </div>
                            <h5>Ibu</h5>
                            <div class="grid2">
                                <div class="field" data-required="true">
                                    <label for="nama_ibu">Nama Ibu <span class="req">*</span></label>
                                    <input type="text" id="nama_ibu" name="nama_ibu" placeholder="Nama lengkap ibu"
                                        required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="tanggal_lahir_ibu">Tahun Lahir Ibu <span class="req">*</span></label>
                                    <input type="number" id="tanggal_lahir_ibu" name="tanggal_lahir_ibu"
                                        placeholder="Tahun Lahir" min="1900" max="2030" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="nik_ibu">NIK Ibu <span class="req">*</span></label>
                                    <input type="text" id="nik_ibu" name="nik_ibu" placeholder="NIK ibu" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="pendidikan_ibu">Jenjang Pendidikan Ibu <span class="req">*</span></label>
                                    <select id="pendidikan_ibu" name="pendidikan_ibu" required>
                                        <option value="">Pilih pendidikan</option>
                                        <option value="Tidak sekolah">Tidak sekolah</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                        <option value="D1">D1</option>
                                        <option value="D2">D2</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="pekerjaan_ibu">Pekerjaan Ibu <span class="req">*</span></label>
                                    <input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" placeholder="Pekerjaan ibu"
                                        required>
                                </div>
                                <div class="field" data-required="true">
                                    <label for="penghasilan_ibu">Penghasilan Ibu <span class="req">*</span></label>
                                    <select id="penghasilan_ibu" name="penghasilan_ibu" required>
                                        <option value="">Pilih penghasilan</option>
                                        <option value="<500000">&lt; Rp 500.000</option>
                                        <option value="500000-1000000">Rp 500.000 - Rp 1.000.000</option>
                                        <option value="1000000-2000000">Rp 1.000.000 - Rp 2.000.000</option>
                                        <option value="2000000-5000000">Rp 2.000.000 - Rp 5.000.000</option>
                                        <option value=">5000000">&gt; Rp 5.000.000</option>
                                    </select>
                                </div>
                                <div class="field span-12" data-required="true">
                                    <label for="ibu_alamat">Alamat Ibu <span class="req">*</span></label>
                                    <textarea id="ibu_alamat" name="ibu_alamat" placeholder="Masukkan alamat lengkap ibu"
                                        required></textarea>
                                </div>
                            </div>
                                        <div class="field" data-required="true" style="margin-top: 30px; padding-top: 20px; border-top: 1px dashed var(--line);">
                                            <label style="font-weight: 600; display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                                                <input type="checkbox" id="agree" name="agree" required style="margin-top: 4px;">
                                                <span>Saya menyatakan bahwa data yang saya isi adalah benar dan saya bertanggung jawab atas kebenarannya. <span class="req">*</span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wizard-footer">
                                <div class="actions" style="margin-top: 20px; border-top: 1px solid var(--line); padding-top: 20px;">
                                    <div class="left">
                                        <button type="button" class="btn ghost" id="btnPrevSub" onclick="window.prevSubStep()" style="display: none;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                            Sebelumnya
                                        </button>
                                        <button id="backToHomeBtn" class="btn ghost" type="button">Kembali ke Beranda</button>
                                    </div>
                                    <div class="right">
                                        <button type="button" id="btnNextSub" class="btn primary" onclick="window.nextSubStep()">
                                            Selanjutnya
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                        </button>
                                        <button type="button" id="btnSaveData" class="btn primary" style="display: none;" onclick="window.handleSaveData();">Simpan & Lanjut Review</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Konfirmasi Data -->
                    <div class="section" data-step="1">
                        <h3>Review Data Pendaftaran</h3>
                        <p>Periksa kembali data yang telah Anda isi. Jika ada yang salah, klik "Kembali Edit" untuk kembali
                            ke formulir.</p>

                        <div class="review-summary" id="reviewSummary">
                            <!-- Data Diri -->
                            <div class="review-section">
                                <h4>Data Diri</h4>
                                <div class="review-grid">
                                    <div class="review-item"><strong class="review-label">Nama Lengkap</strong><span
                                            class="review-value" id="review-nama">-</span></div>
                                    <div class="review-item"><strong class="review-label">Jenis Kelamin</strong><span
                                            class="review-value" id="review-jk">-</span></div>
                                    <div class="review-item"><strong class="review-label">NIK</strong><span
                                            class="review-value" id="review-nik">-</span></div>
                                    <div class="review-item"><strong class="review-label">NISN</strong><span
                                            class="review-value" id="review-nisn">-</span></div>
                                    <div class="review-item"><strong class="review-label">Tempat Lahir</strong><span
                                            class="review-value" id="review-tempatLahir">-</span></div>
                                    <div class="review-item"><strong class="review-label">Tanggal Lahir</strong><span
                                            class="review-value" id="review-tglLahir">-</span></div>
                                    <div class="review-item"><strong class="review-label">Agama</strong><span
                                            class="review-value" id="review-agama">-</span></div>
                                    <div class="review-item"><strong class="review-label">Paket</strong><span
                                            class="review-value" id="review-paket">-</span></div>
                                </div>
                            </div>

                            <!-- Alamat Lengkap -->
                            <div class="review-section">
                                <h4>Alamat Lengkap</h4>
                                <div class="review-grid">
                                    <div class="review-item span-full"><strong class="review-label">Alamat</strong><span
                                            class="review-value" id="review-alamat">-</span></div>
                                    <div class="review-item"><strong class="review-label">RT</strong><span
                                            class="review-value" id="review-rt">-</span></div>
                                    <div class="review-item"><strong class="review-label">RW</strong><span
                                            class="review-value" id="review-rw">-</span></div>
                                    <div class="review-item"><strong class="review-label">Dusun</strong><span
                                            class="review-value" id="review-dusun">-</span></div>
                                    <div class="review-item"><strong class="review-label">Kelurahan/Desa</strong><span
                                            class="review-value" id="review-desa">-</span></div>
                                    <div class="review-item"><strong class="review-label">Kecamatan</strong><span
                                            class="review-value" id="review-kecamatan">-</span></div>
                                    <div class="review-item"><strong class="review-label">Kode Pos</strong><span
                                            class="review-value" id="review-kodePos">-</span></div>
                                    <div class="review-item"><strong class="review-label">Jenis Tinggal</strong><span
                                            class="review-value" id="review-jenisTinggal">-</span></div>
                                    <div class="review-item"><strong class="review-label">Alat Transportasi</strong><span
                                            class="review-value" id="review-alatTransportasi">-</span></div>
                                </div>
                            </div>

                            <!-- Kontak & Bank -->
                            <div class="review-section">
                                <h4>Kontak & Bank</h4>
                                <div class="review-grid">
                                    <div class="review-item"><strong class="review-label">HP/WhatsApp</strong><span
                                            class="review-value" id="review-hp">-</span></div>
                                    <div class="review-item"><strong class="review-label">Telepon</strong><span
                                            class="review-value" id="review-telepon">-</span></div>
                                    <div class="review-item"><strong class="review-label">Email</strong><span
                                            class="review-value" id="review-email">-</span></div>
                                    <div class="review-item"><strong class="review-label">Jenis Bank</strong><span
                                            class="review-value" id="review-jenisBank">-</span></div>
                                    <div class="review-item"><strong class="review-label">No. Rekening</strong><span
                                            class="review-value" id="review-noRekening">-</span></div>
                                </div>
                            </div>

                            <!-- Data Sekolah -->
                            <div class="review-section">
                                <h4>Data Sekolah & Bantuan</h4>
                                <div class="review-grid">
                                    <div class="review-item"><strong class="review-label">Paket</strong><span
                                            class="review-value" id="review-paketSekolah">-</span></div>
                                    <div class="review-item"><strong class="review-label">Sekolah Asal</strong><span
                                            class="review-value" id="review-asalSekolah">-</span></div>
                                    <div class="review-item"><strong class="review-label">NISN</strong><span
                                            class="review-value" id="review-nisnSekolah">-</span></div>
                                    <div class="review-item"><strong class="review-label">SKHUN</strong><span
                                            class="review-value" id="review-skhun">-</span></div>
                                    <div class="review-item"><strong class="review-label">Penerima KPS/KIP/PKH</strong><span
                                            class="review-value" id="review-penerimaKps">-</span></div>
                                </div>
                            </div>

                            <!-- Data Orang Tua - Ayah -->
                            <div class="review-section">
                                <h4>Data Orang Tua - Ayah</h4>
                                <div class="review-grid">
                                    <div class="review-item"><strong class="review-label">Nama Ayah</strong><span
                                            class="review-value" id="review-namaAyah">-</span></div>
                                    <div class="review-item"><strong class="review-label">Tahun Lahir</strong><span
                                            class="review-value" id="review-tahunLahirAyah">-</span></div>
                                    <div class="review-item"><strong class="review-label">NIK Ayah</strong><span
                                            class="review-value" id="review-nikAyah">-</span></div>
                                    <div class="review-item"><strong class="review-label">Pendidikan</strong><span
                                            class="review-value" id="review-pendidikanAyah">-</span></div>
                                    <div class="review-item"><strong class="review-label">Pekerjaan</strong><span
                                            class="review-value" id="review-pekerjaanAyah">-</span></div>
                                    <div class="review-item"><strong class="review-label">Penghasilan</strong><span
                                            class="review-value" id="review-penghasilanAyah">-</span></div>
                                    <div class="review-item span-full"><strong class="review-label">Alamat
                                            Ayah</strong><span class="review-value" id="review-alamatAyah">-</span></div>
                                </div>
                            </div>

                            <!-- Data Orang Tua - Ibu -->
                            <div class="review-section">
                                <h4>Data Orang Tua - Ibu</h4>
                                <div class="review-grid">
                                    <div class="review-item"><strong class="review-label">Nama Ibu</strong><span
                                            class="review-value" id="review-namaIbu">-</span></div>
                                    <div class="review-item"><strong class="review-label">Tahun Lahir</strong><span
                                            class="review-value" id="review-tahunLahirIbu">-</span></div>
                                    <div class="review-item"><strong class="review-label">NIK Ibu</strong><span
                                            class="review-value" id="review-nikIbu">-</span></div>
                                    <div class="review-item"><strong class="review-label">Pendidikan</strong><span
                                            class="review-value" id="review-pendidikanIbu">-</span></div>
                                    <div class="review-item"><strong class="review-label">Pekerjaan</strong><span
                                            class="review-value" id="review-pekerjaanIbu">-</span></div>
                                    <div class="review-item"><strong class="review-label">Penghasilan</strong><span
                                            class="review-value" id="review-penghasilanIbu">-</span></div>
                                    <div class="review-item span-full"><strong class="review-label">Alamat Ibu</strong><span
                                            class="review-value" id="review-alamatIbu">-</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions for Step 1: Review Data -->
                        <div class="actions" style="margin-top: 20px;">
                            <div class="left">
                                <button class="btn ghost" id="btnEditKembali" type="button" onclick="confirmBackToEdit();">Kembali
                                    Edit</button>
                            </div>
                            <div class="right">
                                <button class="btn primary" id="btnSubmitReview" type="button"
                                    onclick="window.doDirectSubmit();">Submit</button>
                            </div>
                        </div>
                    </div>

                    <div class="section" data-step="2">
                        <div class="status-card waiting">
                            <div class="status-icon">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                            </div>
                            <h3>Menunggu Verifikasi</h3>
                            <p>Pendaftaran Anda telah berhasil dikirim dan sedang menunggu verifikasi dari admin PKBM
                                Harmoni.</p>
                            <div class="status-info">
                                <p><strong>Status:</strong> <span class="status-badge waiting">MENUNGGU</span></p>
                                <p><strong>Waktu Submit:</strong> <span id="submitTime">-</span></p>
                                <p><strong>Estimasi Proses:</strong> 1-3 hari kerja</p>

                                <!-- Catatan Admin -->
                                <div id="adminNoteWrap" class="hidden" role="status" aria-live="polite"
                                    style="margin-top:12px; padding:12px; background:#fff6f6; border:1px solid #fee2e2; border-radius:8px;">
                                    <h4 style="margin:0 0 8px 0; color:#b91c1c;">Catatan dari Admin</h4>
                                    <p id="adminNoteContent" style="margin:0 0 8px 0; color:#7f1d1d;">-</p>
                                </div>
                            </div>
                            <div class="status-actions">
                                <button class="btn primary" onclick="window.location.href='{{ url('/') }}'">Kembali ke
                                    Beranda</button>
                            </div>
                        </div>
                    </div>

                    <div class="section" data-step="3">
                        <div class="status-card verified">
                            <div class="status-icon">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22,4 12,14.01 9,11.01"></polyline>
                                </svg>
                            </div>
                            <h3>Data Telah Diverifikasi</h3>
                            <p>Selamat! Data pendaftaran Anda telah diverifikasi oleh admin dan dinyatakan lengkap.</p>
                            <div class="status-info">
                                <p><strong>Status:</strong> <span class="status-badge verified">DIVERIFIKASI</span></p>
                                <p><strong>Waktu Verifikasi:</strong> <span id="verifiedTime">-</span></p>
                                <p><strong>Admin:</strong> <span id="verifiedBy">-</span></p>
                            </div>
                            <div class="status-actions">
                                <button class="btn primary" onclick="window.location.href='{{ url('/') }}'">Kembali ke
                                    Beranda</button>
                            </div>
                        </div>
                    </div>

                    <div class="section" data-step="4">
                        <div class="status-card accepted">
                            <div class="status-icon">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22,4 12,14.01 9,11.01"></polyline>
                                </svg>
                            </div>
                            <h3>Selamat Diterima!</h3>
                            <p>Selamat! Anda telah diterima sebagai siswa PKBM Harmoni. Selamat bergabung dengan kami!</p>
                            <div class="status-info">
                                <p><strong>Status:</strong> <span class="status-badge accepted">DITERIMA</span></p>
                                <p><strong>Waktu Penerimaan:</strong> <span id="acceptedTime">-</span></p>
                            </div>
                            <div class="status-actions">
                                <button class="btn ghost" onclick="window.location.href='{{ url('/') }}'">Kembali ke
                                    Beranda</button>
                                <button class="btn primary blue" onclick="downloadAcceptanceLetter()">Unduh Surat</button>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </main>

@endsection

@section('modals')
    <!-- Modal Bantuan Pendaftaran -->
    <div class="modal" id="modalHelp" aria-hidden="true">
        <div class="modal-card">
            <div class="modal-head">
                <b>Bantuan Pendaftaran</b>
                <button class="icon-btn" onclick="document.getElementById('modalHelp').classList.remove('show')"
                    title="Tutup">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <p>Jika Anda mengalami kesulitan, silakan hubungi admin melalui WhatsApp atau email yang tertera di halaman
                    kontak.</p>
                <p>Pastikan data yang Anda masukkan sesuai dengan dokumen asli (KK/KTP/Ijazah).</p>
            </div>
            <div class="modal-actions">
                <button class="btn primary"
                    onclick="document.getElementById('modalHelp').classList.remove('show')">Tutup</button>
            </div>
        </div>
    </div>

    <div class="modal" id="modalCheckAgain" aria-hidden="true">
        <div class="modal-card">
            <div class="modal-head">
                <b>Periksa Kembali</b>
                <button class="icon-btn" id="closeCheckAgain" title="Tutup">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin kembali mengedit data? Pastikan Anda menyimpan perubahan setelah selesai mengedit.
            </div>
            <div class="modal-actions">
                <button class="btn ghost" id="btnCancelCheck">Batal</button>
                <button class="btn primary" id="btnYesCheck">Ya, Edit Data</button>
            </div>
        </div>
    </div>

    <div class="modal" id="modalSaveConfirm" aria-hidden="true">
        <div class="modal-card">
            <div class="modal-head">
                <b>Konfirmasi</b>
                <button class="icon-btn" id="closeSaveConfirm" title="Tutup">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                Formulir sudah diisi dan akan lanjut ke review data.
            </div>
            <div class="modal-actions">
                <button class="btn ghost" id="btnCancelSave">Batal, Periksa Kembali</button>
                <button class="btn primary" id="btnConfirmSave">Lanjut ke Review Data</button>
            </div>
        </div>
    </div>

    <div class="modal" id="modalConfirm" aria-hidden="true">
        <div class="modal-card">
            <div class="modal-head">
                <b>Konfirmasi Submit</b>
                <button class="icon-btn" id="closeConfirm" title="Tutup" type="button" aria-label="Tutup">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">Apakah Anda yakin ingin mengirim pendaftaran? Setelah dikirim, data akan masuk ke proses
                verifikasi admin.</div>
            <div class="modal-actions">
                <button class="btn ghost" id="btnCancelSubmit" type="button">Batal</button>
                <button class="btn primary" id="btnYesSubmit" type="button">Ya, kirim</button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('assets/js/siswa-pendaftaran.js?v=' . time()) }}"></script>
    <script>
        (function () {
            const backBtn = document.getElementById('backToHomeBtn');
            const headerBackLink = document.getElementById('linkHome');
            function openExitModal(e) {
                if (e) e.preventDefault();
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Yakin ingin keluar?',
                        text: 'Jika Anda keluar sekarang, perubahan yang belum disimpan mungkin hilang. Apakah Anda yakin ingin kembali ke beranda?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Keluar',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ url('/') }}";
                        }
                    });
                } else {
                    if (confirm('Jika Anda keluar sekarang, perubahan yang belum disimpan mungkin hilang. Apakah Anda yakin ingin kembali ke beranda?')) {
                        window.location.href = "{{ url('/') }}";
                    }
                }
            }

            backBtn?.addEventListener('click', openExitModal);
            headerBackLink?.addEventListener('click', openExitModal);

            let formTouched = false;
            document.addEventListener('input', function () { formTouched = true; }, { capture: true });

            window.addEventListener('beforeunload', function (e) {
                if (!formTouched) return undefined;
                const confirmationMessage = 'Perubahan Anda mungkin hilang jika Anda meninggalkan halaman ini.';
                (e || window.event).returnValue = confirmationMessage;
                return confirmationMessage;
            });

            // Help button handler
            document.getElementById('btnHelp')?.addEventListener('click', function () {
                const h = document.getElementById('modalHelp');
                if (h) h.classList.add('show');
            });
        })();
    </script>
@endsection