<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Kosan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            transition: all 0.3s ease;
        }
        
        .login-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }
        
        .login-container h2 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }
        
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
        }
        
        .login-form input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        
        .login-form input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }
        
        .login-form button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .login-form button:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }
        
        .login-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: center;
            margin-top: 20px;
        }
        
        .login-options a {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .login-options a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        .login-options hr {
            border: none;
            border-top: 1px solid #e9ecef;
            margin: 10px 0;
        }
        
        .google-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background-color: white;
            border: 1px solid #e9ecef;
            padding: 12px;
            border-radius: 8px;
            color: var(--dark);
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .google-login:hover {
            background-color: #f8f9fa;
            color: var(--dark);
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo img {
            height: 50px;
        }
        
        @media (max-width: 576px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="logo">
        <img src="<?= base_url('icons/kosan.png') ?>"
    </div>
    
    <h2>Masuk ke Sistem Kosan</h2>

    <form method="post" action="/login" class="login-form">
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Alamat Email" required>
        </div>
        
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Kata Sandi" required>
        </div>

        <button type="submit">
            <i class="fas fa-sign-in-alt"></i> Masuk
        </button>

        <div class="login-options">
            <a href="/register">Belum punya akun? Daftar sekarang</a>
            <a href="/forgot-password">Lupa sandi?</a>
            <hr>
            <a href="/google-login" class="google-login">
                <i class="fab fa-google" style="color: #DB4437;"></i> Lanjutkan dengan Google
            </a>
        </div>
    </form>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#3085d6',
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= session()->getFlashdata('success') ?>',
            confirmButtonColor: '#3085d6',
        });
    <?php endif; ?>
</script>

</body>
</html>