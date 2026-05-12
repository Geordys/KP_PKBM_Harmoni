@extends('layouts.admin')

@section('title', 'Kelola Pendaftaran')
@section('sidebar_subtitle', 'Manajemen Pendaftaran')

@section('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endsection

@section('styles')
<style>
    /* Mengembalikan gaya asli dari kelola_pendaftaran.html */
    .row {
      display: grid;
      grid-template-columns: 220px 220px 1fr 220px;
      gap: 12px;
      align-items: end;
    }

    label {
      font-size: 12px;
      color: var(--muted);
      font-weight: 950;
      letter-spacing: .10em;
      text-transform: uppercase;
      display: block;
      margin-bottom: 6px;
    }

    select,
    input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid var(--line);
      border-radius: 14px;
      outline: none;
      background: #fff;
      font-size: 14px;
    }

    select:focus,
    input:focus {
      border-color: rgba(37, 99, 235, .35);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }

    .btn.warn { background: #0ea5e9; }
    .btn.warn:hover { background: #0284c7; }
    .btn.edit { background: var(--warning); color: #fff; position: relative; }
    .btn.edit:hover { background: #d97706; }

    .admin-request-dot {
      position: absolute;
      top: 6px;
      right: 6px;
      width: 8px;
      height: 8px;
      background: #ef4444;
      border-radius: 50%;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
      pointer-events: none;
    }

    .btn.danger { background: #ef4444; color: #fff; }
    .btn.danger:hover { background: #dc2626; }

    /* New Action Button Styles */
    .actions-cell {
      display: flex;
      gap: 8px;
      justify-content: center;
      align-items: center;
    }

    .btn-action {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid transparent;
      cursor: pointer;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      background: #fff;
      color: var(--muted);
      position: relative;
    }

    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .btn-action svg {
      width: 18px;
      height: 18px;
      stroke-width: 2.2px;
    }

    /* Action Variants - Premium Soft Colors */
    .btn-action.detail { color: #2563eb; background: rgba(37, 99, 235, 0.08); }
    .btn-action.detail:hover { background: #2563eb; color: #fff; }

    .btn-action.edit { color: #f59e0b; background: rgba(245, 158, 11, 0.08); }
    .btn-action.edit:hover { background: #f59e0b; color: #fff; }

    .btn-action.delete { color: #ef4444; background: rgba(239, 68, 68, 0.08); }
    .btn-action.delete:hover { background: #ef4444; color: #fff; }

    .btn-action.verify { color: #0ea5e9; background: rgba(14, 165, 233, 0.08); }
    .btn-action.verify:hover { background: #0ea5e9; color: #fff; }

    .btn-action.accept { color: #10b981; background: rgba(16, 185, 129, 0.08); }
    .btn-action.accept:hover { background: #10b981; color: #fff; }

    .btn-action .dot {
      position: absolute;
      top: -2px;
      right: -2px;
      width: 10px;
      height: 10px;
      background: #ef4444;
      border: 2px solid #fff;
      border-radius: 50%;
    }

    /* Detail Modal Modern Styling */
    .detail-container {
      padding: 10px 5px;
    }
    
    .detail-section {
      margin-bottom: 30px;
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 20px;
    }
    
    .detail-section:last-child {
      border-bottom: none;
    }
    
    .detail-section-title {
      font-size: 14px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .detail-section-title::after {
      content: "";
      flex: 1;
      height: 1px;
      background: #f1f5f9;
    }
    
    .detail-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }
    
    .detail-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    
    .detail-label {
      font-size: 11px;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
    }
    
    .detail-value {
      font-size: 14px;
      font-weight: 500;
      color: #1e293b;
      word-break: break-word;
    }
    
    .detail-value.important {
      color: var(--primary);
      font-weight: 700;
      font-size: 16px;
    }

    .table-wrap {
      margin-top: 12px;
      border: 1px solid var(--line);
      border-radius: 16px;
      overflow: hidden;
      background: #fff;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      min-width: 800px;
    }

    th, td {
      padding: 10px 10px;
      border-bottom: 1px solid var(--line);
      text-align: center;
      vertical-align: middle;
      font-size: 13px;
      white-space: nowrap;
    }

    th {
      color: var(--muted);
      letter-spacing: .10em;
      font-weight: 950;
      font-size: 11px;
      text-transform: uppercase;
      background: #f8fafc;
    }

    .pill {
      display: inline-block;
      padding: 6px 10px;
      border-radius: 999px;
      font-weight: 900;
      font-size: 12px;
      border: 1px solid var(--line);
      background: #fff;
    }

    .pill.wait { color: #b45309; border-color: rgba(245, 158, 11, .30); background: rgba(245, 158, 11, .10); }
    .pill.ver { color: var(--primary2); border-color: rgba(37, 99, 235, .25); background: rgba(37, 99, 235, .06); }
    .pill.acc { color: #15803d; border-color: rgba(22, 163, 74, .30); background: rgba(22, 163, 74, .10); }

    .edit-modal-wrap {
      display: none;
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(15, 23, 42, 0.4);
      backdrop-filter: blur(4px);
      z-index: 1001;
      padding: 20px;
      box-sizing: border-box;
      animation: fadeIn 0.3s ease;
      overflow-y: auto;
    }

    .edit-modal-card {
      background: #fff;
      border-radius: 20px;
      max-width: 520px; width: 100%;
      margin: 40px auto;
      padding: 32px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
      position: relative;
      border: 1px solid var(--line);
    }

    .form-control.disabled { background: #f8fafc; color: #94a3b8; cursor: not-allowed; border-style: dashed; }

    .request-edit-banner { background: #fefce8; border: 1px solid #fef08a; border-radius: 14px; padding: 16px; margin: 20px 0; }
    .banner-title { display: flex; align-items: center; gap: 8px; color: #854d0e; font-weight: 700; font-size: 14px; margin-bottom: 12px; }
    .banner-options { display: flex; gap: 20px; background: #fff; padding: 12px; border-radius: 10px; border: 1px solid #fef08a; }
    .banner-label { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection

@section('topbar_actions')
<div class="title">
    <h1 style="margin: 0; font-size: 22px; letter-spacing: -0.02em;">Kelola Pendaftaran</h1>
    <p style="margin: 4px 0 0; color: var(--muted); font-size: 13px;">Filter Paket B/C dan status, lalu verifikasi/terima pendaftar.</p>
</div>
@endsection

@section('content')
<div class="card">
    <div class="row">
        <div>
            <label>Paket</label>
            <select id="filterPaket">
                <option value="">Semua</option>
                <option value="B">Paket B (SMP)</option>
                <option value="C">Paket C (SMA)</option>
                <option value="REQUEST_EDIT">Permintaan Edit</option>
            </select>
        </div>

        <div>
            <label>Nama / No. Daftar</label>
            <input id="q" placeholder="misal: Budi" />
        </div>

        <div style="display: flex; gap: 8px;">
            <button class="btn" id="btnApply">Terapkan Filter</button>
        </div>
    </div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>Nama</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <tr>
                        <td colspan="6">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 20px; display: flex; justify-content: flex-start;">
        <button class="btn primary" id="btnDownloadExcel">Download Excel</button>
    </div>

    <div id="msg" style="margin-top: 10px; font-size: 13px; color: var(--muted);"></div>
</div>

<!-- MODAL DETAIL PENDAFTARAN -->
<div id="modalDetail" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; padding: 20px; box-sizing: border-box;">
  <div style="background: #fff; border-radius: var(--radius); max-width: 800px; margin: 0 auto; max-height: 90vh; overflow-y: auto; padding: 20px; box-shadow: var(--shadow);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2 style="margin: 0;">Detail Pendaftaran</h2>
      <button id="btnCloseModal" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
    </div>
    <div id="detailContent"></div>
  </div>
</div>

<!-- MODAL EDIT DATA -->
<div id="modalEdit" class="edit-modal-wrap">
  <div class="edit-modal-card">
    <h2>Edit Data Siswa</h2>
    <p style="color: var(--muted); font-size: 14px; margin-bottom: 24px;">Gunakan form ini untuk memberikan catatan atau mengelola izin edit siswa.</p>

    <form id="formEdit">
      <input type="hidden" id="editNomor">
      <div class="form-group" style="margin-bottom: 20px;">
        <label>Nama Lengkap</label>
        <input type="text" id="editNama" class="form-control disabled" required readonly />
      </div>
      <div class="form-group" style="margin-bottom: 20px;">
        <label>Jenis Kelamin</label>
        <input type="text" id="editJK" class="form-control disabled" readonly />
      </div>
      <div class="form-group" style="margin-bottom: 20px;">
        <label>Paket</label>
        <select id="editPaket" class="form-control disabled" disabled>
          <option value="B">Paket B (SMP)</option>
          <option value="C">Paket C (SMA)</option>
        </select>
      </div>
      <div class="form-group" style="margin-bottom: 20px;">
        <label>Catatan Admin (opsional)</label>
        <textarea id="editCatatan" class="form-control" rows="4" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--line);" placeholder="Tambahkan catatan untuk siswa"></textarea>
      </div>

      <div id="editRequestRow" class="request-edit-banner" style="display:none;">
        <div class="banner-title">Siswa Meminta Izin Edit</div>
        <div class="banner-options">
          <label class="banner-label" style="color:#16a34a;"><input type="checkbox" id="editAllowCheckbox"> Setujui</label>
          <label class="banner-label" style="color:#ef4444;"><input type="checkbox" id="editRejectCheckbox"> Tolak</label>
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 20px; border-top: 1px solid var(--line);">
        <button type="button" class="btn ghost" id="btnCloseEdit">Batal</button>
        <button type="submit" class="btn primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
    const API_BASE = "{{ url('/') }}";
    const getToken = () => localStorage.getItem("token_admin") || "";
    const el = (id) => document.getElementById(id);

    const fmtDateTime = (s) => {
      if (!s) return "-";
      try {
        let dateStr = s.replace(" ", "T");
        if (!s.includes("Z") && !s.includes("+")) dateStr += "Z";
        return new Date(dateStr).toLocaleString("id-ID");
      } catch (e) { return s; }
    };

    function pill(status) {
      if (status === "MENUNGGU") return `<span class="pill wait">MENUNGGU</span>`;
      if (status === "DIVERIFIKASI") return `<span class="pill ver">DIVERIFIKASI</span>`;
      if (status === "DITERIMA") return `<span class="pill acc">DITERIMA</span>`;
      return `<span class="pill">${status || "-"}</span>`;
    }

    async function loadList() {
      const paket = el("filterPaket").value;
      const qLower = el("q").value.trim().toLowerCase();
      const tbody = el("tbody");
      const msg = el("msg");
      msg.textContent = "Memuat data...";

      try {
        const res = await fetch(`${API_BASE}/api/admin/registrations`, {
          headers: { "Authorization": "Bearer " + getToken() }
        });
        if (!res.ok) throw new Error("Gagal memuat data");
        const data = await res.json();
        let rows = data.registrations || [];

        // Filter
        if (paket === "REQUEST_EDIT") rows = rows.filter(r => r.minta_izin_edit);
        else if (paket) rows = rows.filter(r => r.paket === paket);
        if (qLower) rows = rows.filter(r => (r.nama||'').toLowerCase().includes(qLower) || (r.nomor_pendaftaran||'').toLowerCase().includes(qLower));

        window.currentRows = rows;
        if (rows.length === 0) {
          tbody.innerHTML = `<tr><td colspan="6">Tidak ada data.</td></tr>`;
          msg.textContent = "";
          return;
        }

        tbody.innerHTML = rows.map(r => `
          <tr>
            <td><b>${r.nomor_pendaftaran}</b></td>
            <td>${r.nama}</td>
            <td>${r.paket === 'B' ? 'B (SMP)' : 'C (SMA)'}</td>
            <td>${pill(r.status)}</td>
            <td>${fmtDateTime(r.created_at)}</td>
            <td>
              <div class="actions-cell">
                <button class="btn-action detail" title="Detail Pendaftaran" onclick="showDetail('${r.nomor_pendaftaran}')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
                <button class="btn-action edit" title="Edit & Catatan" onclick="editData('${r.nomor_pendaftaran}')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  ${r.minta_izin_edit ? '<span class="dot"></span>' : ''}
                </button>
                <button class="btn-action delete" title="Hapus Data" onclick="deleteData('${r.nomor_pendaftaran}')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
                
                ${r.status === "MENUNGGU" ? `
                  <button class="btn-action verify" title="Verifikasi Pendaftaran" onclick="setStatus('${r.nomor_pendaftaran}','DIVERIFIKASI')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                  </button>
                ` : ''}

                ${(r.status === "MENUNGGU" || r.status === "DIVERIFIKASI") ? `
                   <button class="btn-action accept" title="Terima Siswa" onclick="setStatus('${r.nomor_pendaftaran}','DITERIMA')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                  </button>
                ` : ''}
              </div>
            </td>
          </tr>
        `).join("");
        msg.textContent = "";
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6">Gagal memuat: ${err.message}</td></tr>`;
      }
    }

    async function setStatus(nomor, status) {
      if (!confirm(`Ubah status ${nomor} menjadi ${status}?`)) return;
      try {
        const res = await fetch(`${API_BASE}/api/admin/registrations/status`, {
          method: "PATCH",
          headers: { "Content-Type": "application/json", "Authorization": "Bearer " + getToken() },
          body: JSON.stringify({ nomor_pendaftaran: nomor, status })
        });
        if (res.ok) loadList();
      } catch (e) { alert(e.message); }
    }

    function showDetail(nomor) {
      const reg = window.currentRows?.find(r => r.nomor_pendaftaran == nomor);
      if (!reg) return;

      const fmt = (val) => val || '-';
      const item = (label, val, important = false) => `
        <div class="detail-item">
          <div class="detail-label">${label}</div>
          <div class="detail-value ${important ? 'important' : ''}">${fmt(val)}</div>
        </div>
      `;

      const html = `
        <div class="detail-container">
          <div class="detail-section">
            <div class="detail-section-title">Informasi Pendaftaran</div>
            <div class="detail-grid">
              ${item('Nomor Pendaftaran', reg.nomor_pendaftaran, true)}
              ${item('Status', pill(reg.status))}
              ${item('Paket Program', reg.paket === 'B' ? 'Paket B (SMP)' : 'Paket C (SMA)')}
              ${item('Tanggal Daftar', fmtDateTime(reg.created_at))}
            </div>
          </div>

          <div class="detail-section">
            <div class="detail-section-title">Data Diri Siswa</div>
            <div class="detail-grid">
              ${item('Nama Lengkap', reg.nama, true)}
              ${item('Jenis Kelamin', reg.jk === 'L' ? 'Laki-laki' : 'Perempuan')}
              ${item('NISN', reg.nisn)}
              ${item('NIK', reg.nik)}
              ${item('Tempat Lahir', reg.tempat_lahir)}
              ${item('Tanggal Lahir', reg.tanggal_lahir)}
              ${item('Agama', reg.agama)}
              ${item('No. HP / WA', reg.hp)}
              ${item('Email', reg.email)}
            </div>
          </div>

          <div class="detail-section">
            <div class="detail-section-title">Alamat Tinggal</div>
            <div class="detail-grid">
              ${item('Alamat', reg.alamat)}
              ${item('RT / RW', reg.rt_rw)}
              ${item('Dusun', reg.dusun)}
              ${item('Kelurahan / Desa', reg.kelurahan_desa)}
              ${item('Kecamatan', reg.kecamatan)}
              ${item('Kode Pos', reg.kode_pos)}
            </div>
          </div>

          <div class="detail-section">
            <div class="detail-section-title">Asal Sekolah & Bantuan</div>
            <div class="detail-grid">
              ${item('Asal Sekolah', reg.sekolah_asal)}
              ${item('SKHUN', reg.skhun)}
              ${item('Penerima KPS/KIP/PKH', reg.penerima_kps_kip_pkh)}
            </div>
          </div>

          <div class="detail-section">
            <div class="detail-section-title">Data Orang Tua (Ayah)</div>
            <div class="detail-grid">
              ${item('Nama Ayah', reg.ayah_nama, true)}
              ${item('NIK Ayah', reg.ayah_nik)}
              ${item('Tahun Lahir', reg.ayah_tahun_lahir)}
              ${item('Pendidikan', reg.ayah_pendidikan)}
              ${item('Pekerjaan', reg.ayah_pekerjaan)}
              ${item('Penghasilan', reg.ayah_penghasilan)}
              <div class="detail-item" style="grid-column: span 2;">
                <div class="detail-label">Alamat Ayah</div>
                <div class="detail-value">${reg.ayah_alamat || '-'}</div>
              </div>
            </div>
          </div>

          <div class="detail-section">
            <div class="detail-section-title">Data Orang Tua (Ibu)</div>
            <div class="detail-grid">
              ${item('Nama Ibu', reg.ibu_nama, true)}
              ${item('NIK Ibu', reg.ibu_nik)}
              ${item('Tahun Lahir', reg.ibu_tahun_lahir)}
              ${item('Pendidikan', reg.ibu_pendidikan)}
              ${item('Pekerjaan', reg.ibu_pekerjaan)}
              ${item('Penghasilan', reg.ibu_penghasilan)}
              <div class="detail-item" style="grid-column: span 2;">
                <div class="detail-label">Alamat Ibu</div>
                <div class="detail-value">${reg.ibu_alamat || '-'}</div>
              </div>
            </div>
          </div>
        </div>
      `;

      el("detailContent").innerHTML = html;
      el("modalDetail").style.display = "block";
    }

    function editData(nomor) {
        const reg = window.currentRows?.find(r => r.nomor_pendaftaran == nomor);
        if(!reg) return;
        el("editNomor").value = nomor;
        el("editNama").value = reg.nama || "";
        el("editJK").value = reg.jk === 'L' ? 'Laki-laki' : 'Perempuan';
        el("editPaket").value = reg.paket || 'C';
        el("editCatatan").value = reg.catatan || "";
        el("editRequestRow").style.display = reg.minta_izin_edit ? 'block' : 'none';
        el("modalEdit").style.display = "block";
    }

    el("formEdit").onsubmit = async (e) => {
      e.preventDefault();
      const nomor = el("editNomor").value;
      const payload = { 
          catatan: el("editCatatan").value,
          edit_allowed: el("editAllowCheckbox").checked,
          minta_izin_edit: el("editRejectCheckbox").checked ? false : undefined
      };
      try {
        const res = await fetch(`${API_BASE}/api/admin/registrations/${nomor}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json", "Authorization": "Bearer " + getToken() },
          body: JSON.stringify(payload)
        });
        if(res.ok) { el("modalEdit").style.display="none"; loadList(); }
      } catch(e) { alert(e.message); }
    };

    async function deleteData(nomor) {
      if(!confirm(`Hapus data ${nomor}?`)) return;
      try {
        const res = await fetch(`${API_BASE}/api/admin/registrations/${nomor}`, {
          method: "DELETE",
          headers: { "Authorization": "Bearer " + getToken() }
        });
        if(res.ok) loadList();
      } catch(e) { alert(e.message); }
    }

    el("btnApply").onclick = loadList;
    el("btnCloseModal").onclick = () => el("modalDetail").style.display = "none";
    el("btnCloseEdit").onclick = () => el("modalEdit").style.display = "none";
    
    el("btnDownloadExcel").onclick = async () => {
        const res = await fetch(`${API_BASE}/api/admin/registrations/excel`, {
            headers: { "Authorization": "Bearer " + getToken() }
        });
        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url; a.download = 'Data_Pendaftaran.xlsx'; a.click();
    };

    loadList();
</script>
@endsection
