<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KamarModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Ambil session
        $session = session();

        // Ambil data user dari session
        $user = $session->get('user');

        // Jika tidak ada user, redirect ke login
        if (!$user) {
            return redirect()->to('/login');
        }

        // Ambil role user
        $role = $user['role'] ?? 'customer'; // default ke customer jika tidak ada

        $data = [
            'role' => $role,
        ];

        // Jika role customer, ambil data kamar
        if ($role === 'customer') {
            $kamarModel = new KamarModel();
            $data['kamar'] = $kamarModel->where('status_kamar', 'tersedia')->findAll();
        }

        return view('dashboard', $data);
    }
}
