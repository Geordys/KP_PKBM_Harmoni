// ==============================
// KONFIG API & UTILITY
// ==============================
const getApiBase = () => {
  const origin = window.location.origin;
  if (origin.includes(":8000")) return `${origin}/api`;
  return `${origin}/pkbm_laravel/public/api`;
};

const API_BASE = getApiBase();
const REGISTER_URL = `${API_BASE}/registrations`;
const STATUS_URL = `${API_BASE}/registrations/status`;

// Global State
let idx = 0;
let currentRegistration = null;
let sections = [];
let steps = [];

// Global Elements
let btnBack, btnNext, btnSubmit, btnReset, btnSaveData, btnEditKembali, btnSubmitReview;
let alertEl, stepsEl, reviewEl;

window.handleSaveData = () => {
  try {
    if (typeof validateSection === 'function') {
      const isValid = validateSection(0);
      if (isValid) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Konfirmasi Data',
            text: 'Yakin sudah mengisi data dengan benar? Jika belum maka klik Batal untuk kembali edit.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Lanjut',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              saveDraft(getFormData(), 1);
              renderReview();
              setStep(1);
            }
          });
        } else {
          if (!confirm('Yakin sudah mengisi data dengan benar? Jika belum maka kembali edit.')) {
            return;
          }
          saveDraft(getFormData(), 1);
          renderReview();
          setStep(1);
        }
      }
    }
  } catch (e) {
    console.error('Flow Error:', e);
  }
};

// Direct Submit: uses SweetAlert2 then sends data to server
window.doDirectSubmit = async () => {
  if (typeof Swal === 'undefined') {
    if (!confirm('Apakah Anda yakin ingin mengirim data pendaftaran ini?')) return;
  } else {
    const result = await Swal.fire({
      title: 'Konfirmasi Pendaftaran',
      text: 'Apakah Anda yakin ingin mengirim data pendaftaran ini? Setelah dikirim, data akan masuk ke proses verifikasi admin.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#2563eb',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'Ya, Kirim',
      cancelButtonText: 'Batal'
    });
    if (!result.isConfirmed) return;
  }

  const submitBtn = document.getElementById('btnSubmitReview');
  if (submitBtn) { 
    submitBtn.disabled = true; 
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...'; 
  }

  const payload = getFormData();
  const user = JSON.parse(localStorage.getItem('currentUser') || '{}');
  const token = user.token || '';

  try {
    const res = await fetch(REGISTER_URL, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": token ? `Bearer ${token}` : ''
      },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (res.ok) {
      saveRegistrationData(data);
      if (typeof Swal !== 'undefined') {
        await Swal.fire({
          title: 'Berhasil!',
          text: 'Data pendaftaran Anda telah berhasil dikirim dan sedang menunggu verifikasi admin.',
          icon: 'success',
          confirmButtonColor: '#2563eb'
        });
      }
      setStep(getStepFromStatus(data.status));
    } else {
      if (typeof Swal !== 'undefined') {
        Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
      } else {
        alert('Gagal mengirim: ' + (data.message || 'Terjadi kesalahan.'));
      }
    }
  } catch (e) {
    if (typeof Swal !== 'undefined') {
      Swal.fire('Error!', 'Koneksi ke server gagal.', 'error');
    } else {
      alert('Koneksi ke server gagal. Pastikan server berjalan.');
    }
    console.error('Submit error:', e);
  } finally {
    if (submitBtn) { 
      submitBtn.disabled = false; 
      submitBtn.textContent = "Submit"; 
    }
  }
};

// Support functions defined Globally
function getStorageKeys() {
  const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
  let suffix = '';
  if (user) {
    if (user.id) suffix += `_id${user.id}`;
    if (user.email) suffix += `_em${user.email.replace(/[^a-zA-Z0-9]/g, '')}`;
  }
  return {
    DRAFT: `draft_pendaftaran_pkbm_harmoni_v1${suffix}`,
    REGISTRATION: `registration_data_pkbm_harmoni_v1${suffix}`
  };
}

function formatWIB(v) {
  if (!v) return "-";
  try {
    let s = String(v);
    // If string looks like a DB timestamp without timezone info (e.g. "2026-02-09 08:00:16")
    // we must treat it as UTC to get the correct WIB (+7) conversion.
    if (s.includes('-') && !s.includes('Z') && !s.includes('+')) {
      s = s.replace(' ', 'T') + 'Z';
    }
    const dt = new Date(s);
    if (isNaN(dt.getTime())) return v;
    return dt.toLocaleString("id-ID", {
      year: "numeric", month: "numeric", day: "numeric",
      hour: "2-digit", minute: "2-digit", second: "2-digit",
      timeZone: "Asia/Jakarta",
      hour12: false
    }).replace(/\./g, ':') + " WIB";
  } catch (e) { return v; }
}

window.setStep = setStep;
function setStep(i) {
  // Remove preloader styles
  const pStyle = document.getElementById('preloaderHideStyle');
  if (pStyle) { try { pStyle.remove(); } catch (e) { } }

  const statusRaw = String((currentRegistration && currentRegistration.status) || '').toUpperCase();
  const editAllowed = currentRegistration && (currentRegistration.edit_allowed == 1 || currentRegistration.edit_allowed === true);
  
  if (['MENUNGGU', 'DIVERIFIKASI', 'DITERIMA'].includes(statusRaw) && !editAllowed) {
    const lockedStep = getStepFromStatus(statusRaw);
    if (i !== lockedStep) i = lockedStep;
  }

  if (!sections.length) {
    sections = [...document.querySelectorAll(".section")];
    steps = [...document.querySelectorAll(".step")];
  }

  idx = Math.max(0, Math.min(i, sections.length - 1));

  sections.forEach((s, n) => {
    s.style.display = (n === idx) ? 'block' : 'none';
    s.classList.toggle("active", n === idx);
  });

  steps.forEach((st, n) => {
    st.classList.toggle("active", n === idx);
    st.classList.toggle("done", n < idx);
  });

  if (idx === 0 || idx === 1) renderReview();
  if (idx === 2) updateWaitingStatus();
  if (idx === 3) updateVerifiedStatus();
  if (idx === 4) updateAcceptedStatus();

  hideErr();
  
  // Selalu scroll ke atas halaman saat berpindah step
  const aggressiveScroll = () => {
    window.scrollTo(0, 0);
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;
    // Scroll progress wrap into view as a fallback
    document.getElementById('progressWrap')?.scrollIntoView({ behavior: 'instant', block: 'start' });
  };

  aggressiveScroll();
  requestAnimationFrame(aggressiveScroll);
  setTimeout(aggressiveScroll, 50);
}

window.toggleBankLainnya = function() {
    const select = document.getElementById('jenis_bank');
    const field = document.getElementById('field_jenis_bank_lainnya');
    const input = document.getElementById('jenis_bank_lainnya');
    if (select && field && input) {
        if (select.value === 'Lainnya') {
            field.style.display = 'block';
            field.setAttribute('data-required', 'true');
            input.setAttribute('required', 'required');
        } else {
            field.style.display = 'none';
            field.removeAttribute('data-required');
            input.removeAttribute('required');
            input.value = '';
        }
    }
};

function getFormData() {
  const data = {};
  document.querySelectorAll("[name]").forEach(el => {
    const name = el.getAttribute("name");
    if (!name || name in data) return;
    if (el.type === "radio") {
      const picked = [...document.querySelectorAll(`input[name="${name}"]`)].find(r => r.checked);
      data[name] = picked ? picked.value : "";
    } else if (el.type === "checkbox") {
      const boxes = document.querySelectorAll(`input[name="${name}"]`);
      if (boxes.length > 1) data[name] = [...boxes].filter(c => c.checked).map(c => c.value);
      else data[name] = !!el.checked;
    } else {
      data[name] = el.value ? String(el.value).trim() : "";
    }
  });

  if (data.jenis_bank === 'Lainnya' && data.jenis_bank_lainnya) {
    data.jenis_bank = data.jenis_bank_lainnya;
  }
  delete data.jenis_bank_lainnya;

  return data;
}

function setFormData(data) {
  if (!data) return;
  const handled = new Set();

  // Custom Mappings for server data -> form fields
  const getMappedValue = (fieldName) => {
    // PRIORITAS: Jika data sudah memiliki field tersebut (format Draft/Form), langsung gunakan.
    // Ini mencegah data hilang saat refresh karena mapping server-side yang kosong.
    if (data[fieldName] !== undefined && data[fieldName] !== null) return data[fieldName];

    if (fieldName === 'rt' || fieldName === 'rw') {
      const rt_rw = data.rt_rw || "";
      const parts = rt_rw.split('/');
      return fieldName === 'rt' ? (parts[0] || "") : (parts[1] || "");
    }
    if (fieldName === 'alat_transportasi') return data.transportasi || "";
    if (fieldName === 'penerima_kps') return data.penerima_kps_kip_pkh || "Tidak";
    if (fieldName === 'desa') return data.kelurahan_desa || data.desa || "";
    if (fieldName === 'tanggal_lahir_ayah') return data.ayah_tahun_lahir || "";
    if (fieldName === 'tanggal_lahir_ibu') return data.ibu_tahun_lahir || "";

    // Mappings for parent info (server to form)
    if (fieldName === 'nama_ayah') return data.ayah_nama || "";
    if (fieldName === 'nik_ayah') return data.ayah_nik || "";
    if (fieldName === 'pendidikan_ayah') return data.ayah_pendidikan || "";
    if (fieldName === 'pekerjaan_ayah') return data.ayah_pekerjaan || "";
    if (fieldName === 'penghasilan_ayah') return data.ayah_penghasilan || "";

    if (fieldName === 'nama_ibu') return data.ibu_nama || "";
    if (fieldName === 'nik_ibu') return data.ibu_nik || "";
    if (fieldName === 'pendidikan_ibu') return data.ibu_pendidikan || "";
    if (fieldName === 'pekerjaan_ibu') return data.ibu_pekerjaan || "";
    if (fieldName === 'penghasilan_ibu') return data.ibu_penghasilan || "";

    return data[fieldName];
  };

  // Special handling for Bank Lainnya
  let currentJenisBank = getMappedValue('jenis_bank');
  if (currentJenisBank && currentJenisBank !== "") {
    const bankSelect = document.getElementById('jenis_bank');
    if (bankSelect) {
      const options = Array.from(bankSelect.options).map(opt => opt.value);
      if (!options.includes(currentJenisBank) && currentJenisBank !== "Lainnya") {
        data.jenis_bank = "Lainnya";
        data.jenis_bank_lainnya = currentJenisBank;
      }
    }
  }

  document.querySelectorAll("[name]").forEach(el => {
    const name = el.getAttribute("name");
    if (!name || handled.has(name)) return;

    let val = getMappedValue(name);

    if (name === 'jenis_bank') {
      const select = document.getElementById('jenis_bank');
      if (select && val) {
        const options = Array.from(select.options).map(o => o.value);
        if (val !== 'Lainnya' && !options.includes(val)) {
          const customInput = document.getElementById('jenis_bank_lainnya');
          if (customInput) customInput.value = val;
          val = 'Lainnya';
          if (typeof window.toggleBankLainnya === 'function') window.toggleBankLainnya();
        }
      }
    }

    if (val === undefined || val === null) return;

    if (el.type === "radio") {
      document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.checked = (String(r.value) === String(val)));
    } else if (el.type === "checkbox") {
      const boxes = document.querySelectorAll(`input[name="${name}"]`);
      if (Array.isArray(val)) boxes.forEach(c => c.checked = val.includes(c.value));
      else { boxes.forEach(c => c.checked = !!val); }
    } else {
      el.value = val ?? "";
    }
    handled.add(name);
  });
}

function validateSection(secIdx) {
  hideErr();
  let ok = true;
  let missingFields = [];

  if (!sections || sections.length === 0) {
    sections = [...document.querySelectorAll(".section")];
  }
  const sec = sections[secIdx];
  if (!sec) return false;

  sec.querySelectorAll(".field").forEach(f => {
    f.classList.remove("invalid");
    const label = f.querySelector("label");
    const labelText = label ? label.innerText.split('*')[0].trim() : "Kolom";
    
    // Check for explicit data-required or internal [required] attribute
    const isRequired = f.dataset.required === "true" || !!f.querySelector('[required]');
    const input = f.querySelector("input, select, textarea");
    
    if (!input) return;

    let fieldOk = true;

    if (isRequired) {
      if (input.type === "radio") {
        if (![...f.querySelectorAll('input[type="radio"]')].some(r => r.checked)) fieldOk = false;
      }
      else if (input.type === "checkbox") {
        if (!input.checked) fieldOk = false;
      }
      else {
        if (!input.value || input.value.trim() === "") fieldOk = false;
      }
    }

    // Email format validation
    if (input.name === "email" && input.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
      fieldOk = false;
    }

    if (!fieldOk) {
      ok = false;
      f.classList.add("invalid");
      missingFields.push(labelText);
    }
  });

  if (!ok) {
    const firstInvalid = sec.querySelector(".field.invalid");
    if (firstInvalid) {
      firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    const errorMsg = `DATA BELUM LENGKAP:\n\n${missingFields.slice(0, 5).join(", ")}${missingFields.length > 5 ? " dan lainnya" : ""} wajib diisi.`;
    
    // Show in UI alert
    showErr(errorMsg);

    // Hard alert for clear blocking feedback
    alert(`PENDAFTARAN GAGAL DILANJUTKAN:\n\nMohon lengkapi kolom berikut:\n- ${missingFields.join("\n- ")}\n\nPastikan semua data bertanda bintang (*) telah terisi.`);
  }
  
  return ok;
}

function renderReview() {
  const d = getFormData();
  const fmt = (v) => (v && v !== "" ? v : "-");
  const fmtDate = (v) => {
    if (!v) return "-";
    try {
      const dt = new Date(v);
      if (isNaN(dt.getTime())) return v;
      return dt.toLocaleDateString("id-ID", { year: "numeric", month: "long", day: "numeric" });
    } catch { return v; }
  };
  const fmtPaket = (v) => { if (v === "B") return "Paket B (Setara SMP)"; if (v === "C") return "Paket C (Setara SMA)"; return fmt(v); };
  const fmtJK = (v) => { if (v === "L") return "Laki-laki"; if (v === "P") return "Perempuan"; return fmt(v); };
  const fmtPenghasilan = (v) => {
    if (!v || v === "") return "-";
    if (v === "<500000") return "< Rp 500.000";
    if (v === "500000-1000000") return "Rp 500.000 - Rp 1.000.000";
    if (v === "1000000-2000000") return "Rp 1.000.000 - Rp 2.000.000";
    if (v === "2000000-5000000") return "Rp 2.000.000 - Rp 5.000.000";
    if (v === ">5000000") return "> Rp 5.000.000";
    return v;
  };
  const setIf = (id, value) => { try { const el = document.getElementById(id); if (el) el.textContent = value; } catch (e) { } };

  setIf('review-nama', fmt(d.nama));
  setIf('review-jk', fmtJK(d.jk));
  setIf('review-nik', fmt(d.nik));
  setIf('review-nisn', fmt(d.nisn));
  setIf('review-tempatLahir', fmt(d.tempat_lahir));
  setIf('review-tglLahir', fmtDate(d.tanggal_lahir));
  setIf('review-agama', fmt(d.agama));
  setIf('review-paket', fmtPaket(d.paket));
  setIf('review-alamat', fmt(d.alamat));
  setIf('review-rt', fmt(d.rt));
  setIf('review-rw', fmt(d.rw));
  setIf('review-dusun', fmt(d.dusun));
  setIf('review-desa', fmt(d.desa));
  setIf('review-kecamatan', fmt(d.kecamatan));
  setIf('review-kodePos', fmt(d.kode_pos));
  setIf('review-jenisTinggal', fmt(d.jenis_tinggal));
  setIf('review-alatTransportasi', fmt(d.alat_transportasi));
  setIf('review-hp', fmt(d.hp));
  setIf('review-telepon', fmt(d.telepon));
  setIf('review-email', fmt(d.email));
  setIf('review-jenisBank', fmt(d.jenis_bank));
  setIf('review-noRekening', fmt(d.no_rekening));
  setIf('review-paketSekolah', fmtPaket(d.paket));
  setIf('review-asalSekolah', fmt(d.sekolah_asal));
  setIf('review-nisnSekolah', fmt(d.nisn));
  setIf('review-skhun', fmt(d.skhun));
  setIf('review-penerimaKps', fmt(d.penerima_kps || "Tidak"));
  setIf('review-namaAyah', fmt(d.nama_ayah));
  setIf('review-tahunLahirAyah', fmt(d.tanggal_lahir_ayah));
  setIf('review-nikAyah', fmt(d.nik_ayah));
  setIf('review-pendidikanAyah', fmt(d.pendidikan_ayah));
  setIf('review-pekerjaanAyah', fmt(d.pekerjaan_ayah));
  setIf('review-penghasilanAyah', fmtPenghasilan(d.penghasilan_ayah));
  setIf('review-alamatAyah', fmt(d.ayah_alamat));
  setIf('review-namaIbu', fmt(d.nama_ibu));
  setIf('review-tahunLahirIbu', fmt(d.tanggal_lahir_ibu));
  setIf('review-nikIbu', fmt(d.nik_ibu));
  setIf('review-pendidikanIbu', fmt(d.pendidikan_ibu));
  setIf('review-pekerjaanIbu', fmt(d.pekerjaan_ibu));
  setIf('review-penghasilanIbu', fmtPenghasilan(d.penghasilan_ibu));
  setIf('review-alamatIbu', fmt(d.ibu_alamat));
}

function updateWaitingStatus() {
  if (!currentRegistration) return;

  const res = currentRegistration;
  const statusRaw = String(res.status).toUpperCase();
  const el = document.getElementById("submitTime");
  const rawTime = res.submittedAt || res.submitted_at || res.created_at;
  if (el) el.textContent = formatWIB(rawTime);

  // Show Admin Note if present
  const noteWrap = document.getElementById("adminNoteWrap");
  const noteContent = document.getElementById("adminNoteContent");
  const noteHeading = noteWrap ? noteWrap.querySelector("h4") : null;

  if (noteWrap && noteContent) {
    const rawNote = res.catatan || res.catatan_admin || res.note || res.admin_note || "";
    const note = typeof rawNote === 'string' ? rawNote.trim() : "";
    const hasAdminNote = (note && note !== "-" && note !== "");

    if (hasAdminNote) {
      noteContent.textContent = note;
      noteWrap.classList.remove("hidden");
      noteWrap.style.display = "block";
      // Ensure colors are reset to "active/admin" theme (reddish)
      noteWrap.style.borderColor = "#fee2e2";
      noteWrap.style.background = "#fff6f6";
      if (noteHeading) noteHeading.style.color = "#b91c1c";
      noteContent.style.color = "#7f1d1d";
    } else {
      // No note from admin: show a milder, informational box
      noteContent.textContent = "Belum ada catatan dari admin saat ini.";
      noteWrap.classList.remove("hidden");
      noteWrap.style.display = "block";
      // Use neutral/slate theme for empty notes
      noteWrap.style.borderColor = "#e2e8f0";
      noteWrap.style.background = "#f8fafc";
      if (noteHeading) noteHeading.style.color = "#64748b";
      noteContent.style.color = "#64748b";
    }
  }

  // Handle Edit Permission Button (btnRequestEditTop)
  const btn = document.getElementById("btnRequestEditTop");
  if (btn) {
    if (res.status === 'MENUNGGU') {
      if (res.edit_allowed) {
        btn.style.display = 'inline-flex';
        btn.textContent = "Edit Data Sekarang";
        btn.className = "btn primary"; // Make it look active
        btn.onclick = () => {
          setFormData(currentRegistration);
          setStep(0);
        };
      } else {
        btn.style.display = 'none'; // HIDDEN: Moved to notification modal
      }
    } else {
      btn.style.display = 'none';
    }
  }
}

function updateVerifiedStatus() {
  if (!currentRegistration) return;
  const timeEl = document.getElementById("verifiedTime");
  const byEl = document.getElementById("verifiedBy");
  const rawTime = currentRegistration.verified_at || currentRegistration.updated_at;
  if (timeEl) timeEl.textContent = formatWIB(rawTime);
  if (byEl) byEl.textContent = currentRegistration.verifiedBy || currentRegistration.verified_by || "Admin PKBM Harmoni";
}

function updateAcceptedStatus() {
  if (!currentRegistration) return;
  const timeEl = document.getElementById("acceptedTime");
  const rawTime = currentRegistration.accepted_at || currentRegistration.updated_at;
  if (timeEl) timeEl.textContent = formatWIB(rawTime);
}

function saveRegistrationData(data) {
  const reg = { ...data, status: String(data.status || 'MENUNGGU').toUpperCase() };
  localStorage.setItem(getStorageKeys().REGISTRATION, JSON.stringify(reg));
  currentRegistration = reg;
  return reg;
}

function loadRegistrationData() {
  const raw = localStorage.getItem(getStorageKeys().REGISTRATION);
  if (raw) { currentRegistration = JSON.parse(raw); return currentRegistration; }
  return null;
}

function getStepFromStatus(objOrStatus) {
  let s = '';
  let editAllowed = false;

  if (typeof objOrStatus === 'string') {
    s = objOrStatus.toUpperCase();
  } else if (objOrStatus) {
    s = String(objOrStatus.status || '').toUpperCase();
    editAllowed = !!objOrStatus.edit_allowed;
  }

  if (editAllowed) return 0;

  if (s === 'MENUNGGU') return 2;
  if (s === 'DIVERIFIKASI') return 3;
  if (s === 'DITERIMA') return 4;
  return 0;
}

function checkStatus() {
  if (!currentRegistration) return;
  const id = currentRegistration.id || currentRegistration.registrationId;
  const user = JSON.parse(localStorage.getItem('currentUser') || '{}');
  const token = user.token || '';

  fetch(`${STATUS_URL}?registration_id=${id}`, {
    headers: {
      "Authorization": token ? `Bearer ${token}` : ''
    }
  })
    .then(r => {
      if (r.ok) return r.json();
      if (r.status === 404) {
        const keys = getStorageKeys();
        localStorage.removeItem(keys.REGISTRATION);
        localStorage.removeItem(keys.DRAFT);
        location.reload();
      }
      return null;
    })
    .then(data => {
      if (data) {
        saveRegistrationData(data);
        const targetStep = getStepFromStatus(data);
        
        // Bug Fix: If targetStep is 0 (Means we are in New/Edit phase), 
        // we should allow the user to be at idx 0 (form) OR idx 1 (review).
        // Only force setStep if the target is significantly different (e.g. status locked).
        const inDraftPhase = (targetStep === 0 && (idx === 0 || idx === 1));
        
        if (targetStep !== idx && !inDraftPhase) {
           console.log('[Status] Forcing step change from', idx, 'to', targetStep);
           setStep(targetStep);
        } else if (idx === 2) {
           updateWaitingStatus();
        }
      }
    });
}

function showErr(msg) {
  if (!alertEl) alertEl = document.getElementById("alert");
  if (alertEl) { alertEl.textContent = msg; alertEl.classList.add("show"); }
}

function hideErr() { if (alertEl) alertEl.classList.remove("show"); }

// ==============================
// INITIALIZATION
// ==============================
function initSiswaPendaftaran() {
  console.log('[init] starting...');
  alertEl = document.getElementById("alert");
  stepsEl = document.getElementById("steps");

  sections = [...document.querySelectorAll(".section")];
  steps = [...document.querySelectorAll(".step")];

  (async () => {
    try {
      const mode = new URLSearchParams(window.location.search).get('mode');
      let reg = null;

      if (window.SERVER_AUTH_STATUS) {
        if (window.SERVER_REG_DATA && window.SERVER_REG_DATA.status) {
          reg = saveRegistrationData(window.SERVER_REG_DATA);
        } else {
          // Terautentikasi tapi tanpa registrasi di server: biarkan draft lokal tetap ada
          reg = null;
          currentRegistration = null;
        }
      } else {
        // Fallback untuk Guest (belum login)
        reg = loadRegistrationData();
        const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
        if (user) {
          const token = user.token || '';
          const res = await fetch(`${API_BASE}/registrations/by-email?email=${encodeURIComponent(user.email)}`, {
            headers: { "Authorization": token ? `Bearer ${token}` : '' }
          });
          if (res.ok) {
            const data = await res.json();
            if (data.status) reg = saveRegistrationData(data);
          } else if (res.status === 404) {
            const keys = getStorageKeys();
            localStorage.removeItem(keys.REGISTRATION);
            localStorage.removeItem(keys.DRAFT);
            reg = null;
            currentRegistration = null;
          }
        }
      }

      if (reg) {
        // If edit is allowed, we might have a local draft that is newer/more relevant
        const editAllowed = reg.edit_allowed == 1 || reg.edit_allowed === true || reg.edit_allowed === "1";
        const draft = loadDraft();
        
        if (editAllowed && draft) {
           // We have both server data and a local draft.
           // For now, let's load the draft as it's likely what the user was working on.
           setFormData(draft.form);
           setStep(0);
           console.log('[Draft] Resuming from local draft during edit mode.');
        } else {
           // No draft or edit not allowed: load from server
           setFormData(reg);
           setStep(getStepFromStatus(reg));
        }
        
        if (reg.status === 'MENUNGGU') setInterval(checkStatus, 10000);
      } else {
        if (mode === 'cek_status') showNotRegisteredView();
        else {
          const draft = loadDraft();
          if (draft) { 
            setFormData(draft.form); 
            setStep(draft.step || 0); 
            console.log('[Draft] Resumed initial draft.');
          }
          else setStep(0);
        }
      }
    } catch (e) { console.error('init error', e); setStep(0); }
    finally {
      const loader = document.getElementById('startupLoader');
      if (loader) loader.remove();
      if (document.getElementById('bodyWrap')) document.getElementById('bodyWrap').style.display = 'block';
    }
  })();

  // Autosave pada setiap input agar data tidak hilang saat refresh
  document.addEventListener('input', (e) => {
    if (e.target.closest('form')) {
      autosaveCurrentDraft();
    }
  });

  // Handlers
  window.handleConfirmSave = () => {
    closeModal('modalSaveConfirm');
    if (validateSection(0)) { saveDraft(getFormData(), 1); renderReview(); setStep(1); }
  };
  window.submitForm = () => { openModal('modalConfirm'); };
  window.doSubmitNow = async () => {
    closeModal('modalConfirm');
    const agreeBox = document.getElementById("agree");
    if (agreeBox && !agreeBox.checked) {
      if (typeof Swal !== 'undefined') {
        Swal.fire('Perhatian', 'Harap setujui pernyataan tanggung jawab terlebih dahulu.', 'warning');
      } else {
        showErr("Setujui pernyataan dulu.");
      }
      return;
    }

    const payload = getFormData();
    const submitBtn = document.getElementById('btnSubmitReview');
    if (submitBtn) { 
      submitBtn.disabled = true; 
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...'; 
    }

    const user = JSON.parse(localStorage.getItem('currentUser') || '{}');
    const token = user.token || '';

    try {
      const res = await fetch(REGISTER_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Authorization": token ? `Bearer ${token}` : ''
        },
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      if (res.ok) {
        saveRegistrationData(data);
        if (typeof Swal !== 'undefined') {
          await Swal.fire({
            title: 'Pendaftaran Berhasil!',
            text: 'Data Anda telah kami terima. Mohon tunggu proses verifikasi oleh tim kami.',
            icon: 'success',
            confirmButtonColor: '#2563eb'
          });
        }
        setStep(getStepFromStatus(data.status));
      } else {
        if (typeof Swal !== 'undefined') {
          Swal.fire('Gagal!', data.message || "Gagal mengirim data.", 'error');
        } else {
          showErr(data.message || "Gagal mengirim data.");
        }
      }
    } catch (e) {
      if (typeof Swal !== 'undefined') {
        Swal.fire('Error!', 'Terjadi kesalahan koneksi.', 'error');
      } else {
        showErr("Koneksi gagal.");
      }
    }
    finally {
      if (submitBtn) { 
        submitBtn.disabled = false; 
        submitBtn.textContent = "Submit"; 
      }
    }
  };

  // Modal Submit Confirmation
  document.getElementById('btnYesSubmit')?.addEventListener('click', window.doSubmitNow);
  document.getElementById('btnCancelSubmit')?.addEventListener('click', () => closeModal('modalConfirm'));
  document.getElementById('closeConfirm')?.addEventListener('click', () => closeModal('modalConfirm'));
}

// ==============================
// HELPERS & EVENTS
// ==============================
function saveDraft(form, step) {
  localStorage.setItem(getStorageKeys().DRAFT, JSON.stringify({ form, step, at: new Date().toISOString() }));
}

function loadDraft() {
  const raw = localStorage.getItem(getStorageKeys().DRAFT);
  return raw ? JSON.parse(raw) : null;
}

function autosaveCurrentDraft() { saveDraft(getFormData(), idx === 1 ? 1 : 0); }

function showNotRegisteredView() {
  if (document.getElementById('progressWrap')) document.getElementById('progressWrap').style.display = 'none';
  if (document.getElementById('bodyWrap')) document.getElementById('bodyWrap').style.display = 'none';
  if (document.getElementById('notRegisteredView')) document.getElementById('notRegisteredView').style.display = 'block';
}

// Delegated Modal Logic
window.openModal = (id) => { const el = document.getElementById(id); if (el) { el.style.display = 'flex'; el.setAttribute('aria-hidden', 'false'); } };
window.closeModal = (id) => { const el = document.getElementById(id); if (el) { el.style.display = 'none'; el.setAttribute('aria-hidden', 'true'); } };

// Input autosave
document.addEventListener('input', (e) => { if (e.target.matches('input, select, textarea')) autosaveCurrentDraft(); });

document.addEventListener('input', (e) => { if (e.target.matches('input, select, textarea')) autosaveCurrentDraft(); });
window.addEventListener('beforeunload', autosaveCurrentDraft);

async function downloadAcceptanceLetter() {
  const d = currentRegistration;
  if (!d || String(d.status).toUpperCase() !== 'DITERIMA') {
    if (typeof Swal !== 'undefined') {
      Swal.fire('Perhatian', 'Data tidak ditemukan atau belum diterima.', 'warning');
    } else {
      alert("Data tidak ditemukan atau belum diterima.");
    }
    return;
  }

  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  // Helper functions
  const getPaketName = (p) => {
    if (p === 'B') return 'Paket B (Setara SMP)';
    if (p === 'C') return 'Paket C (Setara SMA)';
    return p || '-';
  };
  const fmt = (v) => v || '-';
  const fmtDate = (v) => v ? new Date(v).toLocaleDateString("id-ID", { day: 'numeric', month: 'long', year: 'numeric' }) : '-';

  // --- HEADER / KOP ---
  // Background for Header
  doc.setFillColor(248, 250, 252);
  doc.rect(0, 0, 210, 40, 'F');
  
  doc.setFontSize(24);
  doc.setTextColor(37, 99, 235); // Blue
  doc.setFont(undefined, 'bold');
  doc.text("PKBM HARMONI", 105, 20, { align: 'center' });
  
  doc.setFontSize(10);
  doc.setTextColor(100, 116, 139);
  doc.setFont(undefined, 'normal');
  doc.text("PUSAT KEGIATAN BELAJAR MASYARAKAT", 105, 27, { align: 'center' });
  doc.text("Kotayasa, Kec. Sumbang, Kab. Banyumas, Jawa Tengah", 105, 32, { align: 'center' });
  
  doc.setDrawColor(226, 232, 240);
  doc.line(20, 40, 190, 40);

  // --- TITLE ---
  doc.setFontSize(16);
  doc.setTextColor(30, 41, 59);
  doc.setFont(undefined, 'bold');
  doc.text("SURAT KETERANGAN PENERIMAAN", 105, 55, { align: 'center' });
  
  doc.setFontSize(10);
  doc.setFont(undefined, 'normal');
  doc.text(`Nomor: ${d.nomor_pendaftaran || d.id || '-'} / PPDB / ${new Date().getFullYear()}`, 105, 62, { align: 'center' });

  // --- CONTENT ---
  doc.setFontSize(11);
  doc.text("Berdasarkan hasil verifikasi data pendaftaran yang telah dilakukan, dengan ini Panitia Penerimaan", 20, 75);
  doc.text("Peserta Didik Baru (PPDB) PKBM Harmoni menyatakan bahwa:", 20, 81);

  // Data Box
  doc.setDrawColor(241, 245, 249);
  doc.setFillColor(252, 253, 254);
  doc.roundedRect(20, 88, 170, 70, 3, 3, 'FD');

  const startY = 100;
  const lineGap = 10;
  const labelX = 30;
  const valueX = 75;

  doc.setFont(undefined, 'bold');
  doc.text("Nama Lengkap", labelX, startY);
  doc.text("NIK", labelX, startY + lineGap);
  doc.text("Tempat, Tgl Lahir", labelX, startY + lineGap * 2);
  doc.text("Paket Pilihan", labelX, startY + lineGap * 3);
  doc.text("Status", labelX, startY + lineGap * 4);

  doc.setFont(undefined, 'normal');
  doc.text(`:  ${fmt(d.nama)}`, valueX, startY);
  doc.text(`:  ${fmt(d.nik)}`, valueX, startY + lineGap);
  doc.text(`:  ${fmt(d.tempat_lahir)}, ${fmtDate(d.tanggal_lahir)}`, valueX, startY + lineGap * 2);
  doc.text(`:  ${getPaketName(d.paket)}`, valueX, startY + lineGap * 3);
  
  doc.setTextColor(16, 185, 129); // Green
  doc.setFont(undefined, 'bold');
  doc.text(":  DITERIMA", valueX, startY + lineGap * 4);
  
  doc.setTextColor(30, 41, 59);
  doc.setFont(undefined, 'normal');
  doc.setFontSize(11);
  doc.text("Dinyatakan lulus seleksi administrasi dan diterima sebagai siswa baru PKBM Harmoni.", 20, 175);
  doc.text("Mohon simpan surat ini sebagai bukti pendaftaran yang sah untuk proses daftar ulang.", 20, 181);

  // --- FOOTER ---
  const footerY = 220;
  doc.setFontSize(10);
  doc.text(`Banyumas, ${fmtDate(new Date())}`, 140, footerY);
  doc.setFont(undefined, 'bold');
  doc.text("Panitia PPDB PKBM Harmoni", 140, footerY + 7);
  
  doc.setTextColor(148, 163, 184);
  doc.setFont(undefined, 'normal');
  doc.setFontSize(8);
  doc.text("Surat ini diterbitkan secara elektronik dan sah tanpa tanda tangan basah.", 105, 280, { align: 'center' });

  // Save/Download
  const fileName = `Surat_Penerimaan_${String(d.nama).replace(/\s+/g, '_')}.pdf`;
  doc.save(fileName);
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initSiswaPendaftaran);
else initSiswaPendaftaran();
