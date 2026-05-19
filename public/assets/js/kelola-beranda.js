// --- Kelola Beranda Logic ---

function getFilterState(prefix) {
  const b = document.getElementById(prefix + 'Bright');
  const c = document.getElementById(prefix + 'Contrast');
  const s = document.getElementById(prefix + 'Saturate');
  const h = document.getElementById(prefix + 'Hue');
  const bl = document.getElementById(prefix + 'Blur');
  return {
    brightness: b ? parseInt(b.value, 10) : 100,
    contrast: c ? parseInt(c.value, 10) : 100,
    saturate: s ? parseInt(s.value, 10) : 100,
    hue: h ? parseInt(h.value, 10) : 0,
    blur: bl ? parseInt(bl.value, 10) : 0,
  };
}

function filterString(state) {
  return `brightness(${state.brightness}%) contrast(${state.contrast}%) saturate(${state.saturate}%) hue-rotate(${state.hue}deg) blur(${state.blur}px)`;
}

function applyPreset(prefix, preset) {
  const map = {
    natural: { brightness: 100, contrast: 100, saturate: 110, hue: 0, blur: 0 },
    boost: { brightness: 102, contrast: 110, saturate: 125, hue: 0, blur: 0 },
    warm: { brightness: 100, contrast: 102, saturate: 115, hue: -8, blur: 0 },
    cool: { brightness: 100, contrast: 102, saturate: 110, hue: 8, blur: 0 },
    contrast: { brightness: 100, contrast: 120, saturate: 110, hue: 0, blur: 0 },
    soft: { brightness: 104, contrast: 95, saturate: 105, hue: 0, blur: 0 },
  };
  const v = map[preset] || map.natural;
  ['Bright', 'Contrast', 'Saturate', 'Hue', 'Blur'].forEach((k) => {
    const el = document.getElementById(prefix + k);
    if (!el) return;
    const key = k.toLowerCase();
    el.value = v[key] !== undefined ? v[key] : el.value;
    const valEl = document.getElementById(prefix + k + 'Val');
    if (valEl) {
      const unit = k === 'Hue' ? '°' : (k === 'Blur' ? 'px' : '%');
      valEl.textContent = el.value + unit;
    }
  });
}

function attachFilterControls(prefix, imgEl) {
  const update = () => {
    const st = getFilterState(prefix);
    if (imgEl) imgEl.style.filter = filterString(st);
  };
  ['Bright', 'Contrast', 'Saturate', 'Hue', 'Blur'].forEach((k) => {
    const el = document.getElementById(prefix + k);
    if (!el) return;
    el.oninput = () => {
      const valEl = document.getElementById(prefix + k + 'Val');
      if (valEl) {
        const unit = k === 'Hue' ? '°' : (k === 'Blur' ? 'px' : '%');
        valEl.textContent = el.value + unit;
      }
      update();
    };
  });
  const presetEl = document.getElementById(prefix + 'Preset');
  if (presetEl) presetEl.onchange = () => { applyPreset(prefix, presetEl.value); update(); };
  const resetEl = document.getElementById(prefix + 'Reset');
  if (resetEl) resetEl.onclick = () => { applyPreset(prefix, 'natural'); update(); };
  applyPreset(prefix, 'natural');
  update();
}

function filteredBlobFromCropper(cropper, prefix, width, height, type = 'image/jpeg', quality = 0.9) {
  return new Promise((resolve) => {
    const st = getFilterState(prefix);
    const srcCanvas = cropper.getCroppedCanvas(width && height ? { width, height } : undefined);
    const out = document.createElement('canvas');
    out.width = srcCanvas.width;
    out.height = srcCanvas.height;
    const ctx = out.getContext('2d');
    ctx.filter = filterString(st);
    ctx.drawImage(srcCanvas, 0, 0, out.width, out.height);
    out.toBlob((blob) => resolve(blob), type, quality);
  });
}

function filteredBlobFromFile(file, prefix, type = 'image/jpeg', quality = 0.9) {
  return new Promise((resolve) => {
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = new Image();
      img.onload = function () {
        const st = getFilterState(prefix);
        const canvas = document.createElement('canvas');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        const ctx = canvas.getContext('2d');
        ctx.filter = filterString(st);
        ctx.drawImage(img, 0, 0);
        canvas.toBlob((blob) => resolve(blob), type, quality);
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
}

// Tangani submit form poster
// Tangani submit form poster dengan Cropper
const posterForm = document.getElementById('posterForm');
const posterFile = document.getElementById('posterFile');
const posterCropperImage = document.getElementById('posterCropperImage');
const posterCropperWrapper = document.getElementById('posterCropperWrapper');
let posterCropper = null;

if (posterFile) {
  posterFile.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        if (posterCropper) {
          posterCropper.destroy();
        }
        posterCropperImage.src = e.target.result;
        posterCropperWrapper.style.display = 'block';

        // Inisialisasi Cropper untuk poster (crop bebas atau rasio tertentu jika perlu)
        posterCropper = new Cropper(posterCropperImage, {
          aspectRatio: NaN, // Crop bebas
          viewMode: 1,
          autoCropArea: 1,
        });
      }
      reader.readAsDataURL(file);
    }
  });
}

if (posterForm) {
  posterForm.addEventListener('submit', function (event) {
    event.preventDefault();

    // Periksa apakah ada file atau instance cropper
    if (!posterCropper && !posterFile.files[0]) return;

    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Mengupload...';

    const token = sessionStorage.getItem('token_admin');
    const formData = new FormData(this);

    const submitUpload = async (dataToSend) => {
      try {
        const response = await fetch('/api/admin/upload-poster', {
          method: 'POST',
          headers: {
            'Authorization': 'Bearer ' + token,
            // 'Accept': 'application/json' // Let browser set boundary for multipart
          },
          body: dataToSend
        });

        const data = await response.json();

        if (data.success) {
          alert(data.message);
          this.reset();

          if (posterCropper) {
            posterCropper.destroy();
            posterCropper = null;
          }
          posterCropperWrapper.style.display = 'none';
          posterCropperImage.src = '';

          // Perbarui preview segera
          const currentPoster = document.getElementById('currentPoster');
          if (currentPoster) {
            currentPoster.src = '/assets/poster-pkbm-new.jfif?t=' + new Date().getTime();
          }
        } else {
          alert('Kesalahan: ' + data.message);
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Gagal mengunggah: ' + error.message);
      } finally {
        btn.disabled = false;
        btn.textContent = originalText;
      }
    };

    if (posterCropper) {
      posterCropper.getCroppedCanvas().toBlob((blob) => {
        formData.delete('poster');
        formData.append('poster', blob, 'poster.jpg');
        submitUpload(formData);
      }, 'image/jpeg', 0.9);
    } else {
      submitUpload(formData);
    }
  });
}

// Tangani submit form dokumentasi
// Tangani submit form dokumentasi
const dokumentasiForm = document.getElementById('dokumentasiForm');
const dokumentasiNumber = document.getElementById('dokumentasiNumber');
const dokumentasiFile = document.getElementById('dokumentasiFile');
const currentActivityPhoto = document.getElementById('currentActivityPhoto');
const previewLabel = document.getElementById('previewLabel');
const dokumentasiCropperImage = document.getElementById('dokumentasiCropperImage');
const dokumentasiCropperWrapper = document.getElementById('dokumentasiCropperWrapper');
let dokumentasiCropper = null;

// Perbarui preview saat nomor berubah
if (dokumentasiNumber) {
  dokumentasiNumber.addEventListener('input', function () {
    const val = parseInt(this.value);
    if (val >= 1 && val <= 25) {
      currentActivityPhoto.src = `/assets/foto-pkbm${val}.jfif?t=` + new Date().getTime();
      previewLabel.textContent = `Preview Foto No: ${val}`;

      // Tangani 404/Error untuk preview - fallback
      currentActivityPhoto.onerror = function () {
        this.src = 'https://placehold.co/300x200?text=Foto+Tidak+Ditemukan';
      }
    } else {
      currentActivityPhoto.src = 'https://placehold.co/300x200?text=Pilih+Nomor';
      previewLabel.textContent = 'Preview Foto No: -';
    }
  });
}

// Tangani perubahan gambar & inisialisasi Cropper
if (dokumentasiFile) {
  dokumentasiFile.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        if (dokumentasiCropper) {
          dokumentasiCropper.destroy();
        }
        dokumentasiCropperImage.src = e.target.result;
        dokumentasiCropperWrapper.style.display = 'block';

        dokumentasiCropper = new Cropper(dokumentasiCropperImage, {
          aspectRatio: NaN, // Free crop
          viewMode: 1,
          autoCropArea: 1,
        });
      }
      reader.readAsDataURL(file);
    }
  });
}

if (dokumentasiForm) {
  dokumentasiForm.addEventListener('submit', function (event) {
    event.preventDefault();
    const number = dokumentasiNumber.value;

    // Periksa apakah ada file atau instance cropper
    if ((!dokumentasiCropper && !dokumentasiFile.files[0]) || !number) return;

    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Mengupload...';

    const formData = new FormData();
    formData.append('number', number);

    const token = sessionStorage.getItem('token_admin');

    const submitUpload = async (dataToSend) => {
      try {
        const response = await fetch('/api/admin/upload-dokumentasi', {
          method: 'POST',
          headers: {
            'Authorization': 'Bearer ' + token,
            // 'Accept': 'application/json'
          },
          body: dataToSend
        });

        const data = await response.json();

        if (data.success) {
          alert(data.message);
          dokumentasiForm.reset();

          if (dokumentasiCropper) {
            dokumentasiCropper.destroy();
            dokumentasiCropper = null;
          }
          dokumentasiCropperWrapper.style.display = 'none';
          dokumentasiCropperImage.src = '';

          // Update preview immediately if number is still valid (or reset logic)
          // After reset, input is empty, so we reset preview too
          currentActivityPhoto.src = 'https://placehold.co/300x200?text=Pilih+Nomor';
          previewLabel.textContent = 'Preview Foto No: -';
        } else {
          alert('Kesalahan: ' + data.message);
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Gagal mengunggah: ' + error.message);
      } finally {
        btn.disabled = false;
        btn.textContent = originalText;
      }
    };

    if (dokumentasiCropper) {
      dokumentasiCropper.getCroppedCanvas().toBlob((blob) => {
        formData.delete('dokumentasi');
        formData.append('dokumentasi', blob, 'dokumentasi.jpg');
        submitUpload(formData);
      }, 'image/jpeg', 0.9);
    } else {
      // Cadangan atau upload langsung (meskipun input file biasanya diperlukan)
      formData.append('dokumentasi', dokumentasiFile.files[0]);
      submitUpload(formData);
    }
  });
}

// Keluar - handled by layout, skip if not found
const logoutBtn = document.getElementById('navLogout');
if (logoutBtn && !logoutBtn._listenerAttached) {
  // Logout sudah ditangani oleh layout admin blade
  logoutBtn._listenerAttached = true;
}



// --- Kelola Data Guru Logic (Merged from kelola-guru.js) ---

document.addEventListener('DOMContentLoaded', () => {
  // Token manual check removed because RoleMiddleware handles session-based auth.
  // Bearer token will be used if available, otherwise session auth will take over.

  const modal = document.getElementById('guruModal');
  const form = document.getElementById('guruForm');
  const grid = document.getElementById('guruGrid');
  const fotoInput = document.getElementById('foto');
  const cropperImage = document.getElementById('cropperImage');
  const cropperWrapper = document.getElementById('cropperWrapper');
  let cropper = null;

  // Ambil dan tampilkan data
  async function loadData() {
    try {
      const response = await fetch('/api/admin/guru', {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });
      const result = await response.json();

      if (result.success) {
        renderGrid(result.data);
      } else {
        alert('Gagal memuat data: ' + result.message);
      }
    } catch (error) {
      console.error('Error:', error);
      if (grid) grid.innerHTML = '<p style="text-align:center; width:100%;">Terjadi kesalahan saat memuat data.</p>';
    }
  }

  function renderGrid(data) {
    if (!grid) return;
    grid.innerHTML = '';
    if (data.length === 0) {
      grid.innerHTML = '<p style="text-align:center; width:100%; color:#64748b;">Belum ada data guru.</p>';
      return;
    }

    data.forEach(item => {
      const card = document.createElement('div');
      card.className = 'guru-card';

      const avatarHtml = item.foto_url
        ? `<img src="${item.foto_url}" alt="${item.nama}">`
        : getInitials(item.nama);

      const categoryClass = item.kategori === 'pimpinan' ? 'cat-pimpinan' : 'cat-pendidik';

      card.innerHTML = `
                <div class="guru-avatar">
                    ${avatarHtml}
                </div>
                <div class="guru-name">${item.nama}</div>
                <div class="guru-role">${item.jabatan}</div>
                ${item.mapel ? `<div style="font-size: 0.8rem; color: #2563eb; margin-bottom: 8px; font-weight: 500;">Mapel: ${item.mapel}</div>` : ''}
                <div class="guru-category ${categoryClass}">${item.kategori}</div>
                
                <div class="guru-actions">
                    <button class="btn-edit" onclick="editGuru(${item.id})">Edit</button>
                    <button class="btn-delete" onclick="deleteGuru(${item.id})">Hapus</button>
                </div>
            `;
      grid.appendChild(card);
    });
  }

  function getInitials(name) {
    return name
      .split(' ')
      .map(n => n[0])
      .slice(0, 2)
      .join('')
      .toUpperCase();
  }

  // Fungsi modal
  window.openModal = function (editMode = false) {
    modal.classList.add('active');
    if (!editMode) {
      document.getElementById('modalTitle').textContent = 'Tambah Guru Baru';
      form.reset();
      document.getElementById('guruId').value = '';
      resetCropper();
    }
  }

  window.closeModal = function () {
    modal.classList.remove('active');
    resetCropper();
  }

  function resetCropper() {
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
    cropperImage.src = '';
    cropperWrapper.style.display = 'none';
    fotoInput.value = '';
  }

  // Tangani preview gambar & inisialisasi Cropper
  if (fotoInput) {
    fotoInput.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          if (cropper) {
            cropper.destroy();
          }
          cropperImage.src = e.target.result;
          cropperWrapper.style.display = 'block';

          cropper = new Cropper(cropperImage, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
          });
        }
        reader.readAsDataURL(file);
      }
    });
  }

  // Tangani submit form
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const id = document.getElementById('guruId').value;
      const formData = new FormData(form);
      const url = id ? `/api/admin/guru/${id}` : '/api/admin/guru';

      // If cropper is active, get the blob
      if (cropper) {
        cropper.getCroppedCanvas({
          width: 500,
          height: 500
        }).toBlob((blob) => {
          formData.delete('foto');
          formData.append('foto', blob, 'profile.jpg');
          sendData(url, formData);
        }, 'image/jpeg', 0.8);
      } else {
        sendData(url, formData);
      }
    });
  }

  async function sendData(url, formData) {
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`
        },
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        closeModal();
        loadData();
      } else {
        alert('Gagal: ' + result.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Terjadi kesalahan sistem.');
    }
  }

  // Fungsi edit
  window.editGuru = async function (id) {
    try {
      const response = await fetch(`/api/admin/guru/${id}`, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });
      const result = await response.json();

      if (result.success) {
        const data = result.data;
        document.getElementById('guruId').value = data.id;
        document.getElementById('nama').value = data.nama;
        document.getElementById('jabatan').value = data.jabatan;
        document.getElementById('kategori').value = data.kategori;
        document.getElementById('mapel').value = data.mapel || '';

        document.getElementById('modalTitle').textContent = 'Edit Data Guru';
        resetCropper();
        openModal(true);
      }
    } catch (error) {
      console.error('Error fetching details:', error);
      alert('Gagal mengambil data guru.');
    }
  }

  // Fungsi hapus
  window.deleteGuru = async function (id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data guru ini?')) return;

    try {
      const response = await fetch(`/api/admin/guru/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });
      const result = await response.json();

      if (result.success) {
        loadData();
      } else {
        alert('Gagal menghapus: ' + result.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Terjadi kesalahan saat menghapus.');
    }
  }



  // Pemuatan awal data guru
  if (grid) {
    loadData();
  }

  // Tab switching is handled by the Blade inline script (display:none/block)
  // Do not add scroll-based tab switching here to avoid conflicts

  // --- Logika Foto Grup ---
  const groupPhotoForm = document.getElementById('groupPhotoForm');
  const groupFotoInput = document.getElementById('foto_group');
  const groupCropperImage = document.getElementById('groupCropperImage');
  const groupCropperWrapper = document.getElementById('groupCropperWrapper');
  let groupCropper = null;

  if (groupFotoInput) {
    groupFotoInput.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          if (groupCropper) {
            groupCropper.destroy();
          }
          groupCropperImage.src = e.target.result;
          groupCropperWrapper.style.display = 'block';

          // Inisialisasi Cropper
          groupCropper = new Cropper(groupCropperImage, {
            aspectRatio: NaN, // Free crop
            viewMode: 1,
            autoCropArea: 1,
          });
        }
        reader.readAsDataURL(file);
      }
    });
  }

  if (groupPhotoForm) {
    groupPhotoForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      const btn = this.querySelector('button[type="submit"]');
      const originalText = btn.textContent;

      btn.disabled = true;
      btn.textContent = 'Mengupload...';

      const formData = new FormData(this);

      const submitUpload = async (dataToSend) => {
        try {
          const response = await fetch('/api/admin/guru/upload-group', {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${token}`
            },
            body: dataToSend
          });

          const result = await response.json();

          if (result.success) {
            alert(result.message);
            // Update preview
            document.getElementById('currentGroupPhoto').src = result.url;
            this.reset();

            if (groupCropper) {
              groupCropper.destroy();
              groupCropper = null;
            }
            groupCropperWrapper.style.display = 'none';
            groupCropperImage.src = '';
          } else {
            alert('Gagal: ' + result.message);
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat upload.');
        } finally {
          btn.disabled = false;
          btn.textContent = originalText;
        }
      };

      if (groupCropper) {
        groupCropper.getCroppedCanvas().toBlob((blob) => {
          formData.delete('foto_group');
          formData.append('foto_group', blob, 'group-photo.jpg');
          submitUpload(formData);
        }, 'image/jpeg', 0.9);
      } else {
        submitUpload(formData);
      }
    });
  }

  // --- NEW: Kelola Konten Tekstual Homepage ---
  const homepageContentForm = document.getElementById('homepageContentForm');
  const heroBgForm = document.getElementById('heroBgForm');
  const heroBgFile = document.getElementById('hero_bg_file');
  const heroCropperImage = document.getElementById('heroCropperImage');
  const heroCropperWrapper = document.getElementById('heroCropperWrapper');
  let heroCropper = null;

  if (heroBgFile) {
    heroBgFile.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          if (heroCropper) {
            heroCropper.destroy();
          }
          heroCropperImage.src = e.target.result;
          heroCropperWrapper.style.display = 'block';

          // Inisialisasi Cropper untuk Hero (Widescreen ratio disarankan)
          heroCropper = new Cropper(heroCropperImage, {
            aspectRatio: 16 / 9, // Rasio widescreen untuk hero
            viewMode: 1,
            autoCropArea: 1,
          });
        }
        reader.readAsDataURL(file);
      }
    });
  }

  // --- FAQ DYNAMIC MANAGEMENT ---
  function renderFaqItemsAdmin(faqItems) {
    const container = document.getElementById('faqListContainer');
    if (!container) return;
    container.innerHTML = '';

    const items = typeof faqItems === 'string' ? JSON.parse(faqItems) : (faqItems || []);

    items.forEach((item, index) => {
      const itemHtml = `
            <div class="faq-admin-item" style="background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; position: relative; margin-bottom: 10px;">
                <div style="display: grid; gap: 10px;">
                    <div style="display: grid; grid-template-columns: 1fr 200px; gap: 10px;">
                        <div class="form-group">
                            <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 4px; display: block;">Pertanyaan ${index + 1}</label>
                            <input type="text" class="faq-q-input" value="${item.q || ''}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px;">
                        </div>
                        <div class="form-group">
                            <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 4px; display: block;">Ikon</label>
                            <select class="faq-icon-select" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px;">
                                <option value="help-circle" ${item.icon === 'help-circle' ? 'selected' : ''}>Help (Default)</option>
                                <option value="user-plus" ${item.icon === 'user-plus' ? 'selected' : ''}>Pendaftaran (User+)</option>
                                <option value="book" ${item.icon === 'book' ? 'selected' : ''}>Program (Buku)</option>
                                <option value="phone" ${item.icon === 'phone' ? 'selected' : ''}>Kontak (Telepon)</option>
                                <option value="info" ${item.icon === 'info' ? 'selected' : ''}>Informasi (Info)</option>
                                <option value="calendar" ${item.icon === 'calendar' ? 'selected' : ''}>Jadwal (Kalender)</option>
                                <option value="award" ${item.icon === 'award' ? 'selected' : ''}>Prestasi (Medali)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 4px; display: block;">Jawaban</label>
                        <textarea class="faq-a-input" rows="2" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px;">${item.a || ''}</textarea>
                    </div>
                </div>
            </div>
        `;
      container.insertAdjacentHTML('beforeend', itemHtml);
    });

  }

  // --- ALUR DYNAMIC MANAGEMENT ---
  function renderAlurStepsAdmin(alurItems) {
    const container = document.getElementById('alurStepsContainer');
    if (!container) return;
    container.innerHTML = '';

    const items = typeof alurItems === 'string' ? JSON.parse(alurItems) : (alurItems || []);

    items.forEach((item, index) => {
      const itemHtml = `
            <div class="alur-admin-item" style="background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; position: relative; margin-bottom: 10px;">
                <div style="display: grid; gap: 10px;">
                    <div class="form-group">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 4px; display: block;">Langkah ${index + 1}: Judul</label>
                        <input type="text" class="alur-title-input" value="${item.title || ''}" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 4px; display: block;">Keterangan / Deskripsi</label>
                        <textarea class="alur-desc-input" rows="2" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px;">${item.desc || ''}</textarea>
                    </div>
                </div>
            </div>
        `;
      container.insertAdjacentHTML('beforeend', itemHtml);
    });

  }

  async function loadHomepageContentAdmin() {
    const token = sessionStorage.getItem('token_admin');
    try {
      const response = await fetch('/api/admin/content', {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const result = await response.json();
      if (result.success && result.data) {
        const data = result.data;
        const forms = document.querySelectorAll('.homepage-form');
        forms.forEach(form => {
          for (const key in data) {
            const input = form.elements[key];
            if (input) input.value = data[key];
          }
        });

        // Spefisik FAQ dynamic rendering
        if (data.faq_items) {
          renderFaqItemsAdmin(data.faq_items);
        }

        // Spesifik Alur dynamic rendering
        if (data.alur_items) {
          renderAlurStepsAdmin(data.alur_items);
        }

        // Update hero bg preview
        if (data.hero_bg) {
          const bgPreview = document.getElementById('currentHeroBg');
          if (bgPreview) {
            const bgUrl = data.hero_bg.includes('?') ? data.hero_bg : data.hero_bg + '?t=' + new Date().getTime();
            bgPreview.style.backgroundImage = `url('${bgUrl}')`;
          }
        }
      }
    } catch (err) {
      console.error('Error loading content:', err);
    }
  }

  // Delegasi event listener untuk semua form dengan class .homepage-form
  document.addEventListener('submit', async function (e) {
    if (e.target.classList.contains('homepage-form')) {
      e.preventDefault();
      const form = e.target;
      const btn = form.querySelector('button[type="submit"]');
      const originalText = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'Menyimpan...';

      const token = sessionStorage.getItem('token_admin');
      const formData = new FormData(form);

      // Jika ini form FAQ, susun JSONnya dulu
      if (form.id === 'faqForm') {
        const items = [];
        form.querySelectorAll('.faq-admin-item').forEach(el => {
          items.push({
            q: el.querySelector('.faq-q-input').value,
            a: el.querySelector('.faq-a-input').value,
            icon: el.querySelector('.faq-icon-select').value
          });
        });
        const jsonStr = JSON.stringify(items);
        const faqInput = document.getElementById('faqItemsInput');
        if (faqInput) faqInput.value = jsonStr;
        formData.set('faq_items', jsonStr);
      }

      // Jika ini form Alur, susun JSONnya dulu
      if (form.id === 'alurForm') {
        const items = [];
        form.querySelectorAll('.alur-admin-item').forEach(el => {
          items.push({
            title: el.querySelector('.alur-title-input').value,
            desc: el.querySelector('.alur-desc-input').value
          });
        });
        const jsonStr = JSON.stringify(items);
        const alurInput = document.getElementById('alurItemsInput');
        if (alurInput) alurInput.value = jsonStr;
        formData.set('alur_items', jsonStr);
      }

      const jsonData = {};
      formData.forEach((value, key) => jsonData[key] = value);

      try {
        const response = await fetch('/api/admin/content', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(jsonData)
        });
        const result = await response.json();
        if (result.success) {
          Swal.fire({
            title: 'Berhasil',
            text: result.message,
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
          });
          // Update all forms with new data (just in case there are overlapping keys, though unlikely here)
          loadHomepageContentAdmin();
        } else {
          Swal.fire('Gagal', result.message, 'error');
        }
      } catch (error) {
        console.error(error);
        Swal.fire('Error', 'Gagal menyimpan perubahan.', 'error');
      } finally {
        btn.disabled = false;
        btn.textContent = originalText;
      }
    }
  });

  if (heroBgForm) {
    heroBgForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      // Periksa apakah ada file atau cropper
      if (!heroCropper && !heroBgFile.files[0]) return;

      const btn = this.querySelector('button[type="submit"]');
      const originalText = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'Mengupload...';

      const token = sessionStorage.getItem('token_admin');
      const formData = new FormData(this);

      const submitUpload = async (dataToSend) => {
        try {
          const response = await fetch('/api/admin/upload-hero-bg', {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` },
            body: dataToSend
          });
          const result = await response.json();
          if (result.success) {
            Swal.fire({
              title: 'Berhasil',
              text: result.message,
              icon: 'success',
              timer: 2000
            });

            // Reset cropper
            if (heroCropper) {
              heroCropper.destroy();
              heroCropper = null;
            }
            heroCropperWrapper.style.display = 'none';
            heroCropperImage.src = '';
            heroBgForm.reset();

            loadHomepageContentAdmin(); // Refresh preview
          } else {
            Swal.fire('Gagal', result.message, 'error');
          }
        } catch (error) {
          console.error(error);
          Swal.fire('Error', 'Pesan kesalahan: ' + error.message, 'error');
        } finally {
          btn.disabled = false;
          btn.textContent = originalText;
        }
      };

      if (heroCropper) {
        heroCropper.getCroppedCanvas({
          width: 1920, // High quality full HD width
          height: 1080
        }).toBlob((blob) => {
          formData.delete('file');
          formData.append('file', blob, 'hero_bg.jpg');
          submitUpload(formData);
        }, 'image/jpeg', 0.9);
      } else {
        submitUpload(formData);
      }
    });
  }

  // Helper untuk menyimpan sebagian konten (FAQ/Alur) segera
  async function persistPartialContent(payload, successText) {
    try {
      const response = await fetch('/api/admin/content', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${sessionStorage.getItem('token_admin')}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      });
      const result = await response.json();
      if (result.success) {
        Swal.fire({
          title: 'Berhasil',
          text: successText || result.message,
          icon: 'success',
          timer: 1200,
          showConfirmButton: false
        });
        loadHomepageContentAdmin();
      } else {
        Swal.fire('Gagal', result.message, 'error');
      }
    } catch (err) {
      console.error(err);
      Swal.fire('Error', 'Gagal menyimpan perubahan.', 'error');
    }
  }

  // Tambah FAQ Button
  document.addEventListener('click', function (e) {
    if (e.target.closest('#addFaqBtn')) {
      const container = document.getElementById('faqListContainer');
      const items = [];
      container.querySelectorAll('.faq-admin-item').forEach(el => {
        items.push({
          q: el.querySelector('.faq-q-input').value,
          a: el.querySelector('.faq-a-input').value,
          icon: el.querySelector('.faq-icon-select').value
        });
      });
      items.push({ q: '', a: '', icon: 'help-circle' });
      renderFaqItemsAdmin(items);
    }

    if (e.target.closest('#deleteFaqBtn')) {
      const container = document.getElementById('faqListContainer');
      const items = [];
      container.querySelectorAll('.faq-admin-item').forEach(el => {
        items.push({
          q: el.querySelector('.faq-q-input').value,
          a: el.querySelector('.faq-a-input').value,
          icon: el.querySelector('.faq-icon-select').value
        });
      });
      const count = items.length;
      if (count === 0) {
        Swal.fire('Info', 'Tidak ada item FAQ untuk dihapus.', 'info');
        return;
      }
      Swal.fire({
        title: 'Hapus Item FAQ',
        html: `<p style="color:#64748b;font-size:14px;margin-bottom:10px;">Masukkan nomor item (1-${count}) yang ingin dihapus</p>`,
        input: 'number',
        inputAttributes: { min: 1, max: count, step: 1 },
        inputValue: 1,
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        confirmButtonColor: '#dc2626',
        cancelButtonText: 'Batal'
      }).then((res) => {
        if (res.isConfirmed) {
          const idx = (parseInt(res.value, 10) || 1) - 1;
          if (idx >= 0 && idx < items.length) {
            items.splice(idx, 1);
            const jsonStr = JSON.stringify(items);
            const faqInput = document.getElementById('faqItemsInput');
            if (faqInput) faqInput.value = jsonStr;
            renderFaqItemsAdmin(items);
            persistPartialContent({ faq_items: jsonStr }, 'Item FAQ dihapus dan disimpan.');
          }
        }
      });
    }

    if (e.target.closest('#addAlurBtn')) {
      const container = document.getElementById('alurStepsContainer');
      const items = [];
      container.querySelectorAll('.alur-admin-item').forEach(el => {
        items.push({
          title: el.querySelector('.alur-title-input').value,
          desc: el.querySelector('.alur-desc-input').value
        });
      });
      items.push({ title: '', desc: '' });
      renderAlurStepsAdmin(items);
    }

    if (e.target.closest('#deleteAlurBtn')) {
      const container = document.getElementById('alurStepsContainer');
      const items = [];
      container.querySelectorAll('.alur-admin-item').forEach(el => {
        items.push({
          title: el.querySelector('.alur-title-input').value,
          desc: el.querySelector('.alur-desc-input').value
        });
      });
      const count = items.length;
      if (count === 0) {
        Swal.fire('Info', 'Tidak ada langkah alur untuk dihapus.', 'info');
        return;
      }
      Swal.fire({
        title: 'Hapus Item Alur',
        html: `<p style="color:#64748b;font-size:14px;margin-bottom:10px;">Masukkan nomor langkah (1-${count}) yang ingin dihapus</p>`,
        input: 'number',
        inputAttributes: { min: 1, max: count, step: 1 },
        inputValue: 1,
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        confirmButtonColor: '#dc2626',
        cancelButtonText: 'Batal'
      }).then((res) => {
        if (res.isConfirmed) {
          const idx = (parseInt(res.value, 10) || 1) - 1;
          if (idx >= 0 && idx < items.length) {
            items.splice(idx, 1);
            const jsonStr = JSON.stringify(items);
            const alurInput = document.getElementById('alurItemsInput');
            if (alurInput) alurInput.value = jsonStr;
            renderAlurStepsAdmin(items);
            persistPartialContent({ alur_items: jsonStr }, 'Langkah alur dihapus dan disimpan.');
          }
        }
      });
    }
  });

  // Panggil load awal
  loadHomepageContentAdmin();
});


// Fungsi untuk menampilkan/menyembunyikan sidebar
function toggleSidebar() {
  const sidebar = document.querySelector('.sidebar');
  const overlay = document.querySelector('.sidebar-overlay');
  if (sidebar && overlay) {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
  }
}
