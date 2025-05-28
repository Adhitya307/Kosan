<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CustomerModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        return view('auth/register');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function process_register()
    {
        $userModel = new UserModel();
        $customerModel = new CustomerModel();

        $nama = $this->request->getPost('nama');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            session()->setFlashdata('error', 'Email tidak valid.');
            return redirect()->back()->withInput();
        }

        // Validasi password (minimal 8 karakter, huruf besar, kecil, angka, simbol, tanpa #)
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@\$%^&*()_+\{}\[\]:;"\'<>?,.\/\\\\~`-]).{8,}$/';

        if (!preg_match($pattern, $password) || strpos($password, '#') !== false) {
            session()->setFlashdata('error', 'Sandi harus minimal 8 karakter, mengandung huruf besar, kecil, angka, dan simbol (tidak boleh menggunakan #)');
            return redirect()->back()->withInput();
        }

        // Cek email sudah terdaftar atau belum
        $existingUser = $userModel->where('email', $email)->first();
        if ($existingUser) {
            session()->setFlashdata('error', 'Email sudah terdaftar, gunakan email lain.');
            return redirect()->back()->withInput();
        }

        // Data user untuk disimpan
        $userData = [
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'customer',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($userModel->save($userData)) {
            $userId = $userModel->getInsertID();

            // Data customer terkait user
            $customerData = [
                'user_id' => $userId,
                'nama' => $nama,
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $customerModel->save($customerData);

            session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
            return redirect()->to('/login');
        } else {
            session()->setFlashdata('error', 'Terjadi kesalahan saat registrasi, coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function process_login()
    {
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('error', 'Email tidak ditemukan.');
            return redirect()->back()->withInput();
        }

        if (!password_verify($password, $user['password'])) {
            session()->setFlashdata('error', 'Password salah.');
            return redirect()->back()->withInput();
        }

        // Login sukses, simpan data user di session
        session()->set('user', $user);
        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function setPassword()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Opsional: Cek apakah password sudah di-set sebelumnya, kalau iya redirect ke dashboard
        if (!empty($user['password'])) {
            return redirect()->to('/dashboard')->with('info', 'Password sudah diatur.');
        }

        return view('auth/set_password', ['user' => $user]);
    }

    public function savePassword()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $password = $this->request->getPost('password');
        $konfirmasi = $this->request->getPost('konfirmasi');

        if ($password !== $konfirmasi) {
            session()->setFlashdata('error', 'Konfirmasi sandi tidak cocok.');
            return redirect()->back()->withInput();
        }

        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@\$%^&*()_+\{}\[\]:;"\'<>?,.\/\\\\~`-]).{8,}$/';

        if (!preg_match($pattern, $password) || strpos($password, '#') !== false) {
            session()->setFlashdata('error', 'Sandi harus minimal 8 karakter, mengandung huruf besar, kecil, angka, dan simbol (tidak boleh menggunakan #)');
            return redirect()->back()->withInput();
        }

        $model = new UserModel();
        $model->update($user['id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        // Update session user data setelah password diubah
        session()->set('user', $model->find($user['id']));

        session()->setFlashdata('success', 'Kata sandi berhasil diubah.');
        return redirect()->back();
    }

    public function forgotPasswordForm()
    {
        return view('auth/forgot_password');
    }

    public function sendResetToken()
    {
        $email = $this->request->getPost('email');
        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('error', 'Email tidak ditemukan');
            return redirect()->back()->withInput();
        }

        $token = bin2hex(random_bytes(32));
        $expire = date('Y-m-d H:i:s', time() + 3600); // 1 jam

        $model->update($user['id'], [
            'reset_token' => $token,
            'reset_token_expired_at' => $expire,
        ]);

        $emailService = \Config\Services::email();
        $resetLink = base_url('/reset-password?token=' . $token);
        $emailService->setTo($email);
        $emailService->setSubject('Reset Password Anda - Sistem Kosan');
        $emailService->setMessage("Klik link berikut untuk mengatur ulang password Anda:<br><a href=\"$resetLink\">$resetLink</a>");

        if (!$emailService->send()) {
            session()->setFlashdata('error', 'Gagal mengirim email. Coba lagi.');
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Email reset sudah dikirim!');
        return redirect()->to('/login');
    }

    public function resetPasswordForm()
    {
        $token = $this->request->getGet('token');
        $model = new UserModel();
        $user = $model->where('reset_token', $token)
                      ->where('reset_token_expired_at >=', date('Y-m-d H:i:s'))
                      ->first();

        if (!$user) {
            session()->setFlashdata('error', 'Token tidak valid atau sudah kadaluarsa.');
            return redirect()->to('/login');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

public function updatePasswordFromToken()
{
    $token = $this->request->getPost('token');
    $password = $this->request->getPost('password');
    $konfirmasi = $this->request->getPost('konfirmasi');

    if (empty($password) || empty($konfirmasi)) {
        session()->setFlashdata('error', 'Password dan konfirmasi wajib diisi.');
        return redirect()->back()->withInput();
    }

    if ($password !== $konfirmasi) {
        session()->setFlashdata('error', 'Konfirmasi password tidak cocok.');
        return redirect()->back()->withInput();
    }

    $model = new UserModel();
    $user = $model->where('reset_token', $token)
                  ->where('reset_token_expired_at >=', date('Y-m-d H:i:s'))
                  ->first();

    if (!$user) {
        session()->setFlashdata('error', 'Token tidak valid atau sudah kadaluarsa.');
        return redirect()->to('/login');
    }

    // Regex validasi password: huruf kecil, besar, angka, simbol tertentu, minimal 8 karakter
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@\$%^&*()_+\{}\[\]:;"\'<>,.?\/\\\\~`&-]).{8,}$/';

    if (!preg_match($pattern, $password)) {
        session()->setFlashdata('error', 'Sandi harus minimal 8 karakter, mengandung huruf besar, kecil, angka, dan simbol (tanpa #).');
        return redirect()->back()->withInput();
    }

    if (strpos($password, '#') !== false) {
        session()->setFlashdata('error', 'Sandi tidak boleh mengandung simbol #.');
        return redirect()->back()->withInput();
    }

    $model->update($user['id'], [
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'reset_token' => null,
        'reset_token_expired_at' => null,
    ]);

    session()->setFlashdata('success', 'Password berhasil diubah, silakan login kembali.');
    return redirect()->to('/login');
}

}