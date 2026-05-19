document.addEventListener('DOMContentLoaded', () => {
    // Token manual check removed because RoleMiddleware handles session-based auth.

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
            grid.innerHTML = '<p style="text-align:center; width:100%;">Terjadi kesalahan saat memuat data.</p>';
        }
    }

    function renderGrid(data) {
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

    // Tangani submit form
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('guruId').value;
        const formData = new FormData(form);
        const url = id ? `/api/admin/guru/${id}` : '/api/admin/guru';

        // Jika cropper aktif, ambil blob
        if (cropper) {
            cropper.getCroppedCanvas({
                width: 500,
                height: 500
            }).toBlob((blob) => {
                // Remove the original file input to prevent sending double or wrong file
                formData.delete('foto');
                formData.append('foto', blob, 'profile.jpg');

                sendData(url, formData);
            }, 'image/jpeg', 0.8);
        } else {
            // No new photo selected/cropped, just send form data
            // If editing and no new file, 'foto' input is empty, Laravel ignores it (keeps old)
            sendData(url, formData);
        }
    });

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

                document.getElementById('modalTitle').textContent = 'Edit Data Guru';

                // Clear previous cropper if any
                resetCropper();

                // Kita tidak menampilkan foto lama di cropper, hanya memotong foto BARU.
                // Namun kita bisa menampilkan foto saat ini di tag img terpisah jika diminta.
                // Saat ini, sesuai permintaan pengguna "crop ... after choose photo", fokus pada unggahan baru.

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

    // Bantuan logout
    document.getElementById('navLogout').addEventListener('click', function (e) {
        e.preventDefault();
        if (confirm('Yakin ingin keluar?')) {
            sessionStorage.removeItem('token_admin');
            window.location.href = '/admin/signin';
        }
    });

    // Initial Load
    loadData();

    // Logika Foto Grup
    const groupPhotoForm = document.getElementById('groupPhotoForm');
    if (groupPhotoForm) {
        groupPhotoForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.textContent;

            btn.disabled = true;
            btn.textContent = 'Mengupload...';

            try {
                const response = await fetch('/api/admin/guru/upload-group', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    // Update preview with cache buster
                    document.getElementById('currentGroupPhoto').src = result.url;
                    this.reset();
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
        });
    }
});
