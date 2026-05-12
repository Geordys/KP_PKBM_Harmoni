const form = document.getElementById("statusForm");
const nikInput = document.getElementById("nik");

const resultBox = document.getElementById("result");
const resultIcon = document.getElementById("resultIcon");
const resultTitle = document.getElementById("resultTitle");
const resultMessage = document.getElementById("resultMessage");
const resultInfo = document.getElementById("resultInfo");
const resultActions = document.getElementById("resultActions");

// SESUAIKAN endpoint API kamu di sini:
const API_URL = "/api/registrations/check-status";
// Menggunakan endpoint Laravel yang benar
function badge(status){
  const s = (status || "").toUpperCase();
  if (s === "DITERIMA") return { label: "DITERIMA", color: "#16a34a", bg:"#eaf8ef" };
  if (s === "MENUNGGU" || s === "DIVERIFIKASI") return { label: "MENUNGGU", color: "#f59e0b", bg:"#fff6e6" };
  return { label: s || "TIDAK DITEMUKAN", color: "#64748b", bg:"#f1f5f9" };
}

function getStatusMessage(status) {
  const s = (status || "").toUpperCase();
  if (s === "DITERIMA") {
    return "Selamat! Anda telah diterima di PKBM Harmoni. Silakan hubungi admin untuk informasi selanjutnya.";
  } else if (s === "MENUNGGU") {
    return "Pendaftaran Anda sedang dalam proses verifikasi oleh admin. Silakan tunggu informasi selanjutnya.";
  } else if (s === "DITOLAK") {
    return "Maaf, pendaftaran Anda belum dapat diterima saat ini. Silakan hubungi admin untuk informasi lebih lanjut.";
  }
  return "Status pendaftaran Anda sedang diproses.";
}

function showResult({status, message, data}){
  const b = badge(status);

  resultBox.style.display = "block";
  resultIcon.innerHTML = `<span style="
    width:14px;height:14px;border-radius:999px;background:${b.color};
    display:inline-block; box-shadow:0 0 0 6px ${b.bg};
  "></span>`;

  resultTitle.textContent = `Status: ${b.label}`;
  resultMessage.textContent = message || "Berikut informasi status pendaftaran Anda.";

  // detail opsional
  resultInfo.innerHTML = "";
  if (data) {
    const items = [
      ["Nama", data.nama],
      ["Nomor Pendaftaran", data.nomor_pendaftaran],
      ["Paket", data.paket],
      ["Tanggal Daftar", data.tanggal_daftar],
    ].filter(x => x[1]);

    items.forEach(([k,v])=>{
      const el = document.createElement("div");
      el.style.padding = "10px 12px";
      el.style.border = "1px solid #e6edf7";
      el.style.borderRadius = "14px";
      el.style.background = "#f8fbff";
      el.innerHTML = `<b style="display:block;font-size:12px;color:#64748b">${k}</b>
                      <div style="font-weight:800;color:#0f172a">${v}</div>`;
      resultInfo.appendChild(el);
    });

    resultInfo.style.display = items.length ? "grid" : "none";
    resultInfo.style.gridTemplateColumns = "repeat(2, minmax(0,1fr))";
    resultInfo.style.gap = "10px";
  }

  // actions (opsional)
  resultActions.innerHTML = "";
}

async function cekStatus(nik){
  // tampilkan loading
  resultBox.style.display = "block";
  resultIcon.innerHTML = "";
  resultTitle.textContent = "Mengecek status...";
  resultMessage.textContent = "Mohon tunggu sebentar.";
  resultInfo.innerHTML = "";
  resultActions.innerHTML = "";

  // Simulasi delay untuk testing (hapus ini nanti)
  await new Promise(resolve => setTimeout(resolve, 1000));

  // MOCK DATA UNTUK TESTING - HAPUS INI SETELAH SERVER LARAVEL BERJALAN
  const mockData = {
    "1234567890123456": {
      found: true,
      status: "DITERIMA",
      nama: "Test Siswa",
      nomor_pendaftaran: "PKBM-20260120-0001",
      paket: "B",
      tanggal_pendaftaran: "2026-01-20T10:00:00.000000Z"
    },
    "1234567890123457": {
      found: true,
      status: "MENUNGGU",
      nama: "Siswa Menunggu",
      nomor_pendaftaran: "PKBM-20260120-0002",
      paket: "C",
      tanggal_pendaftaran: "2026-01-19T15:30:00.000000Z"
    },
    "1234567890123458": {
      found: true,
      status: "DITOLAK",
      nama: "Siswa Ditolak",
      nomor_pendaftaran: "PKBM-20260120-0003",
      paket: "B",
      tanggal_pendaftaran: "2026-01-18T09:15:00.000000Z"
    }
  };

  const testData = mockData[nik];

  if (testData) {
    // Gunakan mock data untuk testing
    showResult({
      status: testData.status,
      message: getStatusMessage(testData.status),
      data: {
        nama: testData.nama,
        nomor_pendaftaran: testData.nomor_pendaftaran,
        paket: testData.paket,
        tanggal_daftar: new Date(testData.tanggal_pendaftaran).toLocaleDateString('id-ID')
      }
    });
  } else {
    // Jika NIK tidak ada di mock data, coba API asli
    try {
      const res = await fetch(API_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify({ nik: nik })
      });

      if (!res.ok) {
        throw new Error(`Request gagal (${res.status}). Pastikan server Laravel berjalan.`);
      }

      const json = await res.json();

      if (json.found) {
        showResult({
          status: json.status,
          message: getStatusMessage(json.status),
          data: {
            nama: json.nama,
            nomor_pendaftaran: json.nomor_pendaftaran,
            paket: json.paket,
            tanggal_daftar: new Date(json.tanggal_pendaftaran).toLocaleDateString('id-ID')
          }
        });
      } else {
        showResult({
          status: "TIDAK DITEMUKAN",
          message: json.message || "Data pendaftaran tidak ditemukan dengan NIK tersebut.",
          data: null
        });
      }
    } catch (apiError) {
      // Jika API gagal, tampilkan pesan error
      showResult({
        status: "TIDAK DITEMUKAN",
        message: "Data pendaftaran tidak ditemukan dengan NIK tersebut. Pastikan NIK yang dimasukkan benar.",
        data: null
      });
    }
  }
}

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const nik = (nikInput.value || "").trim();
  if (!nik) return;

  try {
    await cekStatus(nik);
  } catch (err) {
    showResult({
      status: "TIDAK DITEMUKAN",
      message: err.message || "Terjadi kesalahan saat mengecek status. Coba lagi.",
      data: null
    });
  }
});
