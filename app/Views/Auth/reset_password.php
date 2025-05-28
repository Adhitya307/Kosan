<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Atur Ulang Kata Sandi</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f8;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .form-container {
      background: #ffffff;
      padding: 30px 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    h2 {
      margin-bottom: 25px;
      color: #333;
    }

    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s;
    }

    input[type="password"]:focus {
      border-color: #007bff;
      outline: none;
    }

    #password-strength-bar {
      width: 100%;
      height: 8px;
      background-color: #ddd;
      border-radius: 4px;
      margin-bottom: 10px;
    }

    #password-strength-fill {
      height: 100%;
      width: 0%;
      border-radius: 4px;
      transition: width 0.3s;
    }

    .weak {
      background-color: red;
    }

    .medium {
      background-color: orange;
    }

    .strong {
      background-color: green;
    }

    ul#password-requirements {
      text-align: left;
      font-size: 14px;
      padding-left: 20px;
      margin-bottom: 20px;
      color: #555;
    }

    ul#password-requirements li.met {
      color: green;
      font-weight: bold;
    }

    button {
      background-color: #007bff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Atur Ulang Kata Sandi</h2>
    <form method="post" action="/reset-password" onsubmit="return validateForm()">
      <input type="hidden" name="token" value="<?= esc($token) ?>">
      
      <input type="password" id="password" name="password" placeholder="Password Baru" required>
      <input type="password" id="konfirmasi" name="konfirmasi" placeholder="Konfirmasi Password" required>

      <div id="password-strength-bar">
        <div id="password-strength-fill"></div>
      </div>

      <ul id="password-requirements">
        <li id="length">Minimal 8 karakter</li>
        <li id="uppercase">Minimal 1 huruf kapital (A-Z)</li>
        <li id="lowercase">Minimal 1 huruf kecil (a-z)</li>
        <li id="number">Minimal 1 angka (0-9)</li>
        <li id="symbol">Minimal 1 simbol (!@*%...) & tanpa #</li>
      </ul>

      <button type="submit">Reset Password</button>
    </form>
  </div>

  <!-- SweetAlert Notifikasi -->
  <?php if (session()->getFlashdata('error')): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '<?= session()->getFlashdata('error') ?>',
        confirmButtonColor: '#007bff'
      });
    </script>
  <?php endif; ?>

  <?php if (session()->getFlashdata('success')): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= session()->getFlashdata('success') ?>',
        confirmButtonColor: '#007bff'
      }).then(() => {
        window.location.href = "/login";
      });
    </script>
  <?php endif; ?>

  <script>
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

      const lengthValid = value.length >= 8;
      const uppercaseValid = /[A-Z]/.test(value);
      const lowercaseValid = /[a-z]/.test(value);
      const numberValid = /[0-9]/.test(value);
      const symbolValid = /[!@\$%^&*()_+{}\[\]:;"'<>?,./\\~`-]/.test(value) && !/#/.test(value);

      updateRequirement('length', lengthValid);
      updateRequirement('uppercase', uppercaseValid);
      updateRequirement('lowercase', lowercaseValid);
      updateRequirement('number', numberValid);
      updateRequirement('symbol', symbolValid);

      if (lengthValid) strength++;
      if (uppercaseValid) strength++;
      if (lowercaseValid) strength++;
      if (numberValid) strength++;
      if (symbolValid) strength++;

      strengthFill.style.width = (strength * 20) + '%';
      strengthFill.className = '';

      if (strength <= 2) {
        strengthFill.classList.add('weak');
      } else if (strength <= 4) {
        strengthFill.classList.add('medium');
      } else {
        strengthFill.classList.add('strong');
      }
    });

    function updateRequirement(id, isValid) {
      const element = document.getElementById(id);
      if (isValid) {
        element.classList.add('met');
      } else {
        element.classList.remove('met');
      }
    }

function validateForm() {
  const pass = document.getElementById('password').value;
  const confirm = document.getElementById('konfirmasi').value;

  const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@\$%^&*()_+{}\[\]:;"'<>,.?\/\\~`&-]).{8,}$/;

  if (!passRegex.test(pass)) {
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: 'Sandi tidak memenuhi syarat keamanan.',
      confirmButtonColor: '#007bff'
    });
    return false;
  }

  if (pass.includes('#')) {
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: 'Sandi tidak boleh mengandung simbol #.',
      confirmButtonColor: '#007bff'
    });
    return false;
  }

  if (pass !== confirm) {
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: 'Konfirmasi sandi tidak cocok.',
      confirmButtonColor: '#007bff'
    });
    return false;
  }

  return true;
}

  </script>
</body>
</html>
