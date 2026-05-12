# Background Images untuk Hero Section

## Petunjuk Penggunaan

Untuk menambahkan background image pada hero section halaman beranda siswa:

### 1. **Menyimpan Gambar**
   - Simpan foto sekolah Anda dengan nama: `hero-bg.jpg`
   - Letakkan di folder ini: `/public/assets/images/`
   - Format yang disarankan: JPG atau PNG
   - Ukuran optimal: 1920x1080px atau lebih besar
   - Ukuran file: Jangan lebih dari 500KB

### 2. **Format dan Rekomendasi**
   - **Resolusi**: Minimal 1920x1080px (Full HD)
   - **Aspek Ratio**: 16:9 (landscape)
   - **Format File**: JPG (lebih ringan) atau PNG
   - **Kompresi**: Gunakan tool compress online jika perlu mengecilkan ukuran

### 3. **Customization**
   Jika ingin mengubah tingkat overlay (transparansi) atau warna overlay, edit file:
   - Lokasi: `/public/assets/css/siswa-beranda.css`
   - Cari: `background: linear-gradient(135deg, rgba(246,248,252,0.85)...`
   
   **Penjelasan nilai:**
   - `0.85` = tingkat opacity overlay (semakin tinggi = semakin gelap)
   - `rgba(246,248,252,...)` = warna putih yang di-overlay

### 4. **Contoh Pengubahan Overlay**
   ```css
   /* Untuk overlay yang lebih gelap */
   background: linear-gradient(135deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.3) 100%);
   
   /* Untuk overlay yang lebih terang */
   background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.90) 100%);
   ```

### 5. **Menggunakan Multiple Background**
   Jika Anda ingin menggunakan gambar berbeda, Anda bisa:
   - Membuat class CSS baru
   - Mengubah URL di file CSS
   - Atau menggunakan inline style

---

**File yang sudah dimodifikasi:**
- ✅ `/public/assets/css/siswa-beranda.css` - Ditambahkan background support
- ✅ `/public/assets/images/` - Folder untuk menyimpan gambar

**Catatan:** File HTML halaman tidak diubah, hanya CSS yang ditambahkan, jadi tidak akan merusak tampilan yang sudah ada.
