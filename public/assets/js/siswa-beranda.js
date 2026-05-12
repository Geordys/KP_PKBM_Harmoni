// ===== Slider sederhana =====
const slides = [...document.querySelectorAll(".slide")];
const dotsWrap = document.getElementById("dots");

let idx = 0;
let timer = null;

function renderDots() {
    if (!dotsWrap) return;
    dotsWrap.innerHTML = slides.map((_, i) => (
        `<span class="dot ${i === idx ? 'active' : ''}" data-i="${i}" role="button" aria-label="Slide ${i + 1}"></span>`
    )).join("");
    dotsWrap.querySelectorAll(".dot").forEach(d => {
        d.onclick = () => go(+d.dataset.i);
    });
}

function go(i) {
    if (slides.length === 0) return;
    idx = (i + slides.length) % slides.length;
    slides.forEach((s, n) => s.classList.toggle("active", n === idx));
    renderDots();
    restart();
}

function restart() {
    if (timer) clearInterval(timer);
    timer = setInterval(() => go(idx + 1), 5500);
}

// Fungsionalitas swipe
const slider = document.querySelector(".slider");
if (slider) {
    let startX = 0;
    slider.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
    });

    slider.addEventListener('touchend', (e) => {
        const endX = e.changedTouches[0].clientX;
        const diff = startX - endX;
        if (Math.abs(diff) > 50) { // ambang swipe
            if (diff > 0) {
                go(idx + 1); // geser kiri, slide berikutnya
            } else {
                go(idx - 1); // geser kanan, slide sebelumnya
            }
        }
    });
}

renderDots();
restart();

// ===== Accordion FAQ =====
document.querySelectorAll(".faq-item").forEach(item => {
    const btn = item.querySelector(".faq-q");
    if (btn) {
        btn.addEventListener("click", () => {
            item.classList.toggle("open");
        });
    }
});

// ===== Inisialisasi utama =====
document.addEventListener('DOMContentLoaded', function () {
    // 1. Periksa status login
    checkLoginState();

    // 2. Pengguliran halus
    setupSmoothScroll();

    // 3. Sorot navigasi aktif
    setupScrollSpy();

    // 4. Modal gambar
    setupImageModal();

    // 5. Paksa refresh poster (perbaikan saat poster terunggah tidak langsung berubah)
    bustPosterCache();
});

// Logika status login
function checkLoginState() {
    let currentUser = null;
    try {
        currentUser = JSON.parse(localStorage.getItem('currentUser'));
    } catch (e) {
        console.error("Error parsing user data", e);
    }

    // Cadangan: Periksa 'user_name' di parameter URL (dari pengalihan login Google)
    if (!currentUser) {
        const urlParams = new URLSearchParams(window.location.search);
        const userNameFromUrl = urlParams.get('user_name');

        if (userNameFromUrl) {
            currentUser = { nama_lengkap: userNameFromUrl, google: true };
            // Simpan ke localStorage untuk mempertahankan sesi
            localStorage.setItem('currentUser', JSON.stringify(currentUser));

            // Bersihkan URL tanpa parameter
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    }

    const authButtons = document.getElementById('authButtons');
    const userButtons = document.getElementById('userButtons');
    const userGreeting = document.getElementById('userGreeting');
    const logoutBtn = document.getElementById('btnLogout');
    const heroDaftarBtn = document.getElementById('heroDaftarBtn');

    // Elemen mobile
    const mobileAuthButtons = document.getElementById('mobileAuthButtons');
    const mobileUserButtons = document.getElementById('mobileUserButtons');
    const mobileUserGreeting = document.getElementById('mobileUserGreeting');
    const btnMobileLogout = document.getElementById('btnMobileLogout');

    if (currentUser && currentUser.nama_lengkap) {
        // Pengguna sudah masuk
        if (authButtons) authButtons.style.display = 'none';
        if (userButtons) userButtons.style.display = 'flex';

        // Penanganan mobile
        if (mobileAuthButtons) mobileAuthButtons.style.display = 'none';
        if (mobileUserButtons) mobileUserButtons.style.display = 'flex';
        if (mobileUserGreeting) mobileUserGreeting.innerHTML = `Halo, <strong>${currentUser.nama_lengkap}</strong>`;

        if (userGreeting) {
            userGreeting.innerHTML = `Halo, <strong>${currentUser.nama_lengkap}</strong>`;
        }

        // Render badge status kecil di nav (opsional)
        try { renderNavStatus(currentUser); } catch (e) { /* ignore */ }

        // Perbarui tombol Hero agar menuju Form Pendaftaran
        if (heroDaftarBtn) {
            // If user already has registration stored locally and it's in a locked status, turn button into "Cek Status"
            try {
                const user = JSON.parse(localStorage.getItem('currentUser') || 'null');
                const suffix = user && user.id ? `_${user.id}` : '';
                const regRaw = localStorage.getItem(`registration_data_pkbm_harmoni_v1${suffix}`);
                if (regRaw) {
                    const reg = JSON.parse(regRaw);
                    if (['MENUNGGU','DIVERIFIKASI','DITERIMA'].includes(reg.status)) {
                        heroDaftarBtn.href = 'pendaftaran.html?mode=cek_status';
                        heroDaftarBtn.textContent = 'Cek Status';
                    } else {
                        heroDaftarBtn.href = 'pendaftaran.html';
                        heroDaftarBtn.textContent = 'Daftar Sekarang';
                    }
                } else {
                    heroDaftarBtn.href = 'pendaftaran.html';
                    heroDaftarBtn.textContent = 'Daftar Sekarang';
                }
            } catch (e) {
                heroDaftarBtn.href = 'pendaftaran.html';
            }
        }

        // Penangan Logout (Desktop)
        if (logoutBtn) {
            logoutBtn.onclick = function () {
                if (confirm('Apakah Anda yakin ingin keluar?')) {
                    localStorage.removeItem('currentUser');
                    location.reload();
                }
            };
        }

        // Penangan Logout (Mobile)
        if (btnMobileLogout) {
            btnMobileLogout.onclick = function () {
                if (confirm('Apakah Anda yakin ingin keluar?')) {
                    localStorage.removeItem('currentUser');
                    location.reload();
                }
            };
        }
    } else {
        // Pengguna BELUM masuk
        if (authButtons) authButtons.style.display = 'flex';
        if (userButtons) userButtons.style.display = 'none';

        // Penanganan Mobile
        if (mobileAuthButtons) mobileAuthButtons.style.display = 'block'; // pembungkus mobile adalah element div
        if (mobileUserButtons) mobileUserButtons.style.display = 'none';

        // Perbarui tombol Hero agar menuju Halaman Login
        if (heroDaftarBtn) {
            heroDaftarBtn.href = "login_siswa.html";
        }
    }
}

function setupSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || href.includes('.html') || href.includes('.php')) return;

            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const headerOffset = 100;
                const elementPosition = target.offsetTop;
                const offsetPosition = elementPosition - headerOffset;
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

function setupScrollSpy() {
    function highlightActiveNav() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav > a[href^="#"]');
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop - 150;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    }
    window.addEventListener('scroll', highlightActiveNav);
    highlightActiveNav();
}

function setupImageModal() {
    const modal = document.getElementById('imageModal');
    if (!modal) return;

    const modalImg = document.getElementById('modalImage');
    const captionText = document.getElementById("modalCaption");
    const closeEle = document.querySelector(".modal-close");

    if (!modalImg) return;

    const galleryImages = document.querySelectorAll('#galeri img');
    galleryImages.forEach(img => {
        img.addEventListener('click', function () {
            modal.style.display = 'block';
            modalImg.src = this.src;
            if (captionText) captionText.innerHTML = this.alt;
            document.body.style.overflow = 'hidden';

            // Efek fade in
            modalImg.style.opacity = 0;
            setTimeout(() => modalImg.style.opacity = 1, 50);
        });
    });

    function closeMe() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    if (closeEle) closeEle.onclick = closeMe;
    modal.onclick = (e) => {
        if (e.target === modal) closeMe();
    };

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            closeMe();
        }
    });
}

// Render badge status kecil di navbar berdasarkan data pendaftaran lokal
async function renderNavStatus(user) {
    const navStatus = document.getElementById('navStatus');
    const mobileNavStatus = document.getElementById('mobileNavStatus');

    if (!navStatus && !mobileNavStatus) return;

    const suffix = user && user.id ? `_${user.id}` : '';
    const key = `registration_data_pkbm_harmoni_v1${suffix}`;
    let reg = null;
    try { reg = JSON.parse(localStorage.getItem(key) || 'null'); } catch (e) { reg = null; }

    const regId = reg && (reg.id || reg.registrationId || reg.registration_id) ? (reg.id || reg.registrationId || reg.registration_id) : null;
    const localStatus = reg && reg.status ? String(reg.status).toUpperCase() : null;

    let fetchedStatus = null;
    try {
        const base = window.location.origin + '/api/registrations/status';
        let url = base;
        if (regId) url = `${base}?registration_id=${encodeURIComponent(regId)}`;
        else if (user && user.id) url = `${base}?user_id=${encodeURIComponent(user.id)}`;

        const res = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } });
        if (res.ok) {
            const data = await res.json().catch(() => null);
            if (data && data.status) {
                fetchedStatus = String(data.status).toUpperCase();
                // Show notification bell if there is an unread admin note
                try {
                    if (data.catatan && data.catatan_read === false) {
                        renderNotificationBell(data);
                    }
                } catch (e) { /* ignore */ }
            }
        }
    } catch (e) { /* ignore */ }

    const status = fetchedStatus || localStatus;

    if (!status) {
        if (navStatus) navStatus.style.display = 'none';
        if (mobileNavStatus) mobileNavStatus.style.display = 'none';
        return;
    }

    let label = status;
    let bg = '#6b7280';
    if (status === 'MENUNGGU') { label = 'Menunggu'; bg = '#fbbf24'; }
    else if (status === 'DIVERIFIKASI') { label = 'Diverifikasi'; bg = '#2563eb'; }
    else if (status === 'DITERIMA') { label = 'Diterima'; bg = '#16a34a'; }

    // Perbarui Desktop
    if (navStatus) {
        navStatus.textContent = `Status : ${label}`;
        navStatus.style.background = bg;
        navStatus.style.color = '#fff';
        navStatus.style.display = 'inline-block';
    }

    // Perbarui Mobile
    if (mobileNavStatus) {
        mobileNavStatus.textContent = `Status : ${label}`;
        mobileNavStatus.style.background = bg;
        mobileNavStatus.style.color = '#fff';
        mobileNavStatus.style.display = 'block';
    }
}

function bustPosterCache() {
    // Target gambar poster tertentu berdasarkan ID
    const poster = document.getElementById('posterImage');
    if (poster) {
        // Append current timestamp to src to force reload
        const src = poster.src.split('?')[0];
        poster.src = `${src}?v=${new Date().getTime()}`;
    }
}

// Render a notification bell in the top-right when there's an unread admin note
function renderNotificationBell(data) {
    try {
        const container = document.getElementById('topRightActions') || document.getElementById('userButtons');
        if (!container) return;
        // Avoid adding twice
        if (document.getElementById('btnNotif')) return;

        const btn = document.createElement('button');
        btn.id = 'btnNotif';
        btn.className = 'icon-btn';
        btn.title = 'Notifikasi Catatan Admin';
        btn.setAttribute('aria-label', 'Notifikasi Catatan Admin');
        btn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="notif-dot" aria-hidden="true"></span>
        `;

        // Add a short pulse to draw attention
        const pulse = document.createElement('span');
        pulse.className = 'notif-pulse';
        btn.appendChild(pulse);
        // add a short bounce too
        btn.classList.add('notif-bounce');
        // remove bounce and pulse after animation
        setTimeout(() => { if (pulse && pulse.parentNode) pulse.parentNode.removeChild(pulse); try { btn.classList.remove('notif-bounce'); } catch(e){} }, 900);

        btn.onclick = function (e) {
            e.preventDefault();
            const note = data.catatan || "";
            if (!note) return; // nothing to show

            // open modal we added on beranda
            const modal = document.getElementById('modalNote');
            const modalContent = document.getElementById('modalNoteContent');
            const btnClose = document.getElementById('modalNoteClose');
            const btnCancel = document.getElementById('modalNoteCancel');
            const btnMark = document.getElementById('modalNoteMarkRead');

            if (modal && modalContent) {
                modalContent.textContent = note;
                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');

                const closeModal = () => { modal.classList.remove('show'); modal.setAttribute('aria-hidden','true'); };

                btnClose && (btnClose.onclick = closeModal);
                btnCancel && (btnCancel.onclick = closeModal);

                btnMark && (btnMark.onclick = function () {
                    btnMark.disabled = true;
                    const API_ROOT = window.location.origin.includes(':8000') ? window.location.origin : window.location.origin + '/pkbm_laravel/public';
                    fetch(`${API_ROOT}/api/registrations/${data.id}/note-read`, {
                        method: 'POST', headers: { 'Content-Type': 'application/json' }
                    }).then(r => r.json()).then(j => {
                        if (j.success) {
                            // hide dot and disable button
                            const dot = btn.querySelector('.notif-dot');
                            if (dot) dot.style.display = 'none';
                            btn.classList.add('disabled');
                            btn.setAttribute('aria-disabled', 'true');
                            closeModal();
                        } else {
                            alert('Gagal menandai catatan: ' + (j.message || ''));
                        }
                    }).catch(err => {
                        alert('Gagal menandai catatan: ' + err.message);
                    }).finally(() => { btnMark.disabled = false; });
                });

            }
        };

        // Insert into the container's action-group if available
        const actionGroup = container.querySelector('.action-group');
        if (actionGroup) {
            actionGroup.insertBefore(btn, actionGroup.firstChild);
        } else {
            container.appendChild(btn);
        }
    } catch (e) { console.warn('renderNotificationBell error', e); }
}

// Toggle navigasi mobile
function toggleMobileNav() {
    const drawer = document.querySelector('.mobile-drawer');
    const overlay = document.querySelector('.drawer-overlay');
    if (drawer && overlay) {
        drawer.classList.toggle('active');
        overlay.classList.toggle('active');

        // Cegah scroll halaman saat drawer terbuka
        if (drawer.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
}
