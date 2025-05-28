<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Daftar Akun - Sistem Kosan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?= base_url('css/regis.css') ?>" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <style>
      /* Popup notifikasi */
      #popup-notif {
        display: none;
        position: fixed;
        inset: 0; /* top:0; left:0; right:0; bottom:0; */
        background-color: rgba(0,0,0,0.6);
        z-index: 9999;
        justify-content: center;
        align-items: center;
      }
      #popup-notif.active {
        display: flex;
      }
      #popup-content {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        font-family: 'Poppins', sans-serif;
      }
      #popup-content h3 {
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: #dc3545; /* merah */
        font-weight: 700;
      }
      #popup-content button {
        background-color: #dc3545;
        color: white;
        font-size: 1.1rem;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease;
      }
      #popup-content button:hover {
        background-color: #a71d2a;
      }
    </style>
</head>
<body>

<div class="register-container">
    <div class="logo">
        <img src="<?= base_url('icons/kosan.png') ?>" alt="Logo" style="max-width: 150px; height: auto; display: block; margin: 0 auto 20px;" />
    </div>

    <h2>Daftar Akun Sistem Kosan</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <p class="error"><i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?></p>
    <?php elseif (session()->getFlashdata('success')): ?>
        <p class="success"><i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <form method="post" action="/register" class="register-form" onsubmit="return validateForm()">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="nama" placeholder="Nama Lengkap" required />
        </div>

        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Alamat Email" required />
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Kata Sandi" required />
        </div>

        <div id="password-strength-bar">
            <div id="password-strength-fill"></div>
        </div>

        <ul id="password-requirements">
            <li id="length"><i class="far fa-circle"></i> Minimal 8 karakter</li>
            <li id="uppercase"><i class="far fa-circle"></i> Minimal 1 huruf kapital (A-Z)</li>
            <li id="lowercase"><i class="far fa-circle"></i> Minimal 1 huruf kecil (a-z)</li>
            <li id="number"><i class="far fa-circle"></i> Minimal 1 angka (0-9)</li>
            <li id="symbol"><i class="far fa-circle"></i> Minimal 1 simbol (cth: !@*%...)</li>
        </ul>

        <button type="submit"><i class="fas fa-user-plus"></i> Daftar Sekarang</button>

        <div class="register-options">
            <a href="/login"><i class="fas fa-sign-in-alt"></i> Sudah punya akun? Masuk di sini</a>
        </div>

        <p class="terms">
            Dengan mendaftar, Anda menyetujui <a href="/terms">Syarat dan Ketentuan</a> dan <a href="/privacy">Privasi</a>.
        </p>
    </form>
</div>

<!-- Popup notifikasi harus di luar form supaya overlay mencakup seluruh layar -->
<div id="popup-notif">
    <div id="popup-content">
        <h3 id="popup-message"></h3>
        <button onclick="closePopup()">Tutup</button>
    </div>
</div>

<script>
function validateForm() {
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|co\.id|net|org)$/;
    const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@\$%^&*()_+{}\[\]:;"'<>?,./\\~`-]).{8,}$/;

    if (!emailRegex.test(email)) {
        showPopup("Email harus mengandung @ dan domain valid seperti .com atau .co.id");
        return false;
    }

if (!passRegex.test(password) || password.includes('#')) {
    showPopup("Sandi harus minimal 8 karakter, mengandung huruf besar, kecil, angka, dan simbol (tidak boleh menggunakan #)");
    return false;
}

    return true;
}

function showPopup(message) {
  const popup = document.getElementById('popup-notif');
  const messageElem = document.getElementById('popup-message');
  messageElem.textContent = message;
  popup.classList.add('active');
}

function closePopup() {
  const popup = document.getElementById('popup-notif');
  popup.classList.remove('active');
}

const passwordInput = document.getElementById('password');
const strengthFill = document.getElementById('password-strength-fill');

const requirements = {
    length: document.getElementById('length'),
    uppercase: document.getElementById('uppercase'),
    lowercase: document.getElementById('lowercase'),
    number: document.getElementById('number'),
    symbol: document.getElementById('symbol'),
};

passwordInput.addEventListener('input', function () {
    const value = passwordInput.value;
    let strength = 0;

    // Validasi aturan password
    const lengthValid = value.length >= 8;
    const uppercaseValid = /[A-Z]/.test(value);
    const lowercaseValid = /[a-z]/.test(value);
    const numberValid = /[0-9]/.test(value);
    // Simbol harus ada, dan tidak mengandung '#'
    const symbolValid = /[!@\$%^&*()_+{}\[\]:;"'<>?,./\\~`-]/.test(value) && !/#/.test(value);

    updateRequirement('length', lengthValid);
    updateRequirement('uppercase', uppercaseValid);
    updateRequirement('lowercase', lowercaseValid);
    updateRequirement('number', numberValid);
    updateRequirement('symbol', symbolValid);

    // Hitung kekuatan password
    if (lengthValid) strength++;
    if (uppercaseValid) strength++;
    if (lowercaseValid) strength++;
    if (numberValid) strength++;
    if (symbolValid) strength++;

    // Update warna dan lebar bar kekuatan password
    strengthFill.style.width = (strength * 20) + '%';
    strengthFill.className = ''; // reset class

    if (strength <= 2) {
        strengthFill.classList.add('weak'); // merah
    } else if (strength <= 4) {
        strengthFill.classList.add('medium'); // kuning
    } else {
        strengthFill.classList.add('strong'); // hijau
    }
});

function updateRequirement(id, isValid) {
    const element = document.getElementById(id);
    const icon = element.querySelector('i');

    if (isValid) {
        element.classList.add('met');
        icon.classList.remove('fa-circle');
        icon.classList.add('fa-check-circle');
    } else {
        element.classList.remove('met');
        icon.classList.add('fa-circle');
        icon.classList.remove('fa-check-circle');
    }
}
</script>

</body>
</html>
