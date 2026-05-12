// Fungsi otentikasi untuk login/daftar siswa
document.addEventListener('DOMContentLoaded', function () {
    // Periksa apakah pengguna sudah login
    let currentUser = null;
    try {
        currentUser = JSON.parse(localStorage.getItem('currentUser'));
    } catch (e) {
        localStorage.removeItem('currentUser');
    }

    // Pastikan tidak ada modal ngawur yang menutupi layar saat pertama dimuat
    document.body.style.overflow = '';
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(m => {
        m.style.display = '';
        m.classList.remove('show');
    });
    // Eager redirect removed to allow users to reach the login page even with local state
    /* 
    if (currentUser && window.location.pathname.includes('/login')) {
        window.location.href = '/';
        return;
    }
    */

    // Tangani form login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    // Tangani form daftar
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }

    // Validasi konfirmasi password
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword) {
        confirmPassword.addEventListener('input', validatePasswordMatch);
    }

    // Fungsi toggle password
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            const eyeIcon = this.querySelector('.eye-icon');
            const eyeOffIcon = this.querySelector('.eye-off-icon');

            if (type === 'text') {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        });
    }

    if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);

            const eyeIcon = this.querySelector('.eye-icon');
            const eyeOffIcon = this.querySelector('.eye-off-icon');

            if (type === 'text') {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        });
    }
});

async function handleLogin(e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const loginBtn = document.getElementById('loginBtn');

    // Tampilkan status loading
    loginBtn.classList.add('loading');
    loginBtn.disabled = true;
    loginBtn.innerHTML = '<span>Masuk...</span>';

    const token = document.querySelector('input[name="_token"]')?.value || '';

    try {
        const response = await fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (response.ok) {
            // Login berhasil
            const currentUser = {
                id: data.user.id,
                name: data.user.name || data.user.nama_lengkap,
                email: data.user.email,
                token: data.token,
                loginTime: new Date().toISOString()
            };

            localStorage.setItem('currentUser', JSON.stringify(currentUser));

            // Tampilkan Modal Selamat Datang
            const welcomeModal = document.getElementById('welcomeModal');
            const welcomeName = document.getElementById('welcomeName');

            if (welcomeModal && welcomeName) {
                welcomeName.textContent = `Selamat Datang, ${currentUser.name}!`;
                welcomeModal.classList.add('show');

                loginBtn.innerHTML = '<span>Berhasil!</span>';
                loginBtn.style.background = '#16a34a'; // Success green

                setTimeout(() => {
                    window.location.href = '/';
                }, 2000); 
            } else {
                showMessage('Login berhasil! Mengalihkan...', 'success');
                setTimeout(() => {
                    window.location.href = '/';
                }, 1000);
            }
        } else {
            // Login gagal
            showMessage(data.message || 'Email atau password salah!', 'error');
            loginBtn.classList.remove('loading');
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<span>Login</span>';
        }
    } catch (error) {
        showMessage('Terjadi kesalahan pada server. Coba lagi nanti.', 'error');
        loginBtn.classList.remove('loading');
        loginBtn.disabled = false;
        loginBtn.innerHTML = '<span>Login</span>';
    }
}

async function handleRegister(e) {
    e.preventDefault();

    const formData = {
        nama_lengkap: document.getElementById('nama_lengkap').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        confirm_password: document.getElementById('confirm_password').value
    };

    // Validasi kecocokan password
    if (formData.password !== formData.confirm_password) {
        showMessage('Password dan konfirmasi password tidak cocok!', 'error');
        return;
    }

    // NIK validation removed

    const registerBtn = document.getElementById('registerBtn');

    // Tampilkan status loading
    registerBtn.classList.add('loading');
    registerBtn.disabled = true;
    registerBtn.innerHTML = '<span>Mendaftarkan...</span>';

    try {
        const response = await fetch('/api/auth/register-siswa', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (response.ok) {
            showMessage('Pendaftaran berhasil! Silakan login.', 'success');
            registerBtn.classList.remove('loading');
            registerBtn.disabled = false;
            registerBtn.innerHTML = '<span>Daftar Akun</span>';

            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
        } else {
            showMessage(data.message || 'Gagal mendaftar!', 'error');
            registerBtn.classList.remove('loading');
            registerBtn.disabled = false;
            registerBtn.innerHTML = '<span>Daftar</span>';
        }
    } catch (error) {
        showMessage('Terjadi kesalahan koneksi.', 'error');
        registerBtn.classList.remove('loading');
        registerBtn.disabled = false;
        registerBtn.innerHTML = '<span>Daftar</span>';
    }
}

function validatePasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const confirmInput = document.getElementById('confirm_password');

    if (confirmPassword && password !== confirmPassword) {
        confirmInput.classList.add('error');
        const formGroup = confirmInput.closest('.form-group') || confirmInput.parentNode;
        let errorMsg = formGroup.querySelector('.error-message');
        if (!errorMsg) {
            errorMsg = document.createElement('span');
            errorMsg.className = 'error-message';
            formGroup.appendChild(errorMsg);
        }
        errorMsg.textContent = 'Password tidak cocok';
    } else {
        confirmInput.classList.remove('error');
        const formGroup = confirmInput.closest('.form-group') || confirmInput.parentNode;
        const errorMsg = formGroup.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.remove();
        }
    }
}

function showMessage(message, type) {
    // Hapus pesan yang ada
    const existingMsg = document.querySelector('.auth-message');
    if (existingMsg) {
        existingMsg.remove();
    }

    // Buat elemen pesan
    const messageEl = document.createElement('div');
    messageEl.className = `auth-message ${type}`;
    messageEl.textContent = message;

    // Masukkan setelah form
    const form = document.querySelector('.auth-form');
    form.parentNode.insertBefore(messageEl, form.nextSibling);

    // Hapus otomatis setelah 3 detik
    setTimeout(() => {
        if (messageEl.parentNode) {
            messageEl.remove();
        }
    }, 3000);
}

// Tambahkan CSS untuk pesan
const style = document.createElement('style');
style.textContent = `
.auth-message {
    padding: 12px 16px;
    margin: 16px 0;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
}
.auth-message.success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}
.auth-message.error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}
`;
document.head.appendChild(style);

// Fungsi Modal
function openTncModal() {
    const modal = document.getElementById('tncModal');
    if (modal) {
        modal.style.display = '';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Mencegah scroll background
    }
}

function closeTncModal() {
    const modal = document.getElementById('tncModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            // Tunggu animasi
        }, 300);
        document.body.style.overflow = '';
    }
}

function acceptTnc() {
    const checkbox = document.getElementById('agree');
    if (checkbox) {
        checkbox.checked = true;
    }
    closeTncModal();
}

// Tutup modal saat klik di luar
document.addEventListener('click', function (event) {
    const modal = document.getElementById('tncModal');
    if (modal && event.target === modal) {
        closeTncModal();
    }
});