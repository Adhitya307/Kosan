<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Sistem Kosan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url('/css/dashboard.css') ?>" />
    <!-- Jangan pakai link untuk JS -->
</head>
<body>

<header>
    <div class="logo">
        <img src="<?= base_url('icons/kosan.png') ?>" alt="Logo Kosan" />
        <h2>Sistem Kosan</h2>
    </div>

    <div class="user-info">
        <div class="avatar">
            <?= strtoupper(substr($user['nama'] ?? 'U', 0, 1)) ?>
        </div>
        <span class="username"><?= esc($user['nama'] ?? 'Pengguna') ?></span>
        <a href="<?= base_url('logout') ?>" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</header>

<div class="main-container">
    <?php if ($role === 'admin'): ?>
        <h2 class="section-title">Menu Administrasi</h2>
        <div class="menu-wrapper">
            <div class="menu-container">
                <a href="<?= base_url('booking/kelolaboking') ?>" class="menu-item">
                    <i class="fas fa-exchange-alt"></i>
                    <h3>Data Booking</h3>
                    <p>Kelola Booking penyewaan</p>
                </a>
                <a href="<?= base_url('admin') ?>" class="menu-item">
                    <i class="fas fa-user-shield"></i>
                    <h3>Data Admin</h3>
                    <p>Kelola administrator sistem</p>
                </a>
                <a href="<?= base_url('customer') ?>" class="menu-item">
                    <i class="fas fa-users"></i>
                    <h3>Data Customer</h3>
                    <p>Kelola data pelanggan</p>
                </a>
                <a href="<?= base_url('kamar') ?>" class="menu-item">
                    <i class="fas fa-bed"></i>
                    <h3>Data Kamar</h3>
                    <p>Kelola kamar kos</p>
                </a>
            </div>
        </div>
    <?php elseif ($role === 'customer'): ?>
        <nav class="customer-navbar">
            <div class="container">
                <a href="<?= base_url('informasi_booking') ?>" class="btn-booked">
                    <i class="fas fa-bookmark"></i> Booked
                </a>
            </div>
        </nav>

        <h2 class="section-title">Kamar Tersedia</h2>
        <div class="kamar-list">
            <?php if (!empty($kamar)) : ?>
                <?php foreach ($kamar as $k) : ?>
                    <div class="kamar-item">
<?php
$fotos = json_decode($k['foto'], true);
if (is_array($fotos) && count($fotos) > 0):
    $foto_pertama = $fotos[0];
?>
    <img src="<?= base_url('uploads/kamar/' . $foto_pertama) ?>" alt="Foto Kamar" class="kamar-img" />
<?php else: ?>
    <img src="https://via.placeholder.com/400x300?text=Kamar+Kosan" alt="Foto Kamar" class="kamar-img" />
<?php endif; ?>


                        <div class="kamar-details">
                            <h4>Kamar <?= esc($k['nomor_kamar']) ?></h4>
                            <div class="kamar-meta">
                                <span class="kamar-price">Rp<?= number_format($k['harga_kamar'], 0, ',', '.') ?>/bulan</span>
                                <span class="kamar-status <?= $k['status_kamar'] === 'tersedia' ? 'status-available' : 'status-booked' ?>">
                                    <?= esc($k['status_kamar']) ?>
                                </span>
                            </div>
                            <a href="<?= base_url('booking/' . $k['id_kamar']) ?>" class="book-btn">
                                <i class="fas fa-calendar-check"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-rooms">Tidak ada kamar tersedia saat ini.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Letakkan script JS di bawah sebelum penutup body -->
<script src="<?= base_url('js/dashboard.js') ?>"></script>

</body>
</html>
