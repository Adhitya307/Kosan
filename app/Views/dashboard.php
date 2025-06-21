<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Sistem Kosan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url('/css/dashboard.css') ?>" />

<style>
    /* WA Floating Button */
    #wa-floating {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999;
        text-align: right;
    }

    #wa-button {
        background-color: #25D366;
        border: none;
        border-radius: 50%;
        padding: 15px;
        font-size: 22px;
        color: white;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        transition: background 0.3s;
    }

    #wa-button:hover {
        background-color: #1ebc59;
    }

    #wa-info {
        background-color: white;
        color: #333;
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.2);
        margin-top: 8px;

        width: 220px;
        font-size: 14px;
        animation: fadeIn 0.3s ease;
    }

    #wa-info p {
        margin: 8px 0;
        line-height: 1.4;
    }

    #wa-info a {
        color: #25D366;
        text-decoration: none;
        cursor: pointer;
    }

    #wa-info a:hover {
        text-decoration: underline;
    }

    #wa-info.hidden {
        display: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

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

<!-- WA Floating Button dan Info Panel -->
<!-- WA Floating Button dan Info Panel -->
<div id="wa-floating">
    <button id="wa-button" type="button">
        <i class="fas fa-address-book"></i> <!-- GANTI INI -->
    </button>
    <div id="wa-info" class="hidden">
        <p><strong>WhatsApp:</strong><br>0881-0230-82302</p>
        <p><strong>Instagram:</strong><br>@kosan.kita</p>
    </div>
</div>


<!-- Script -->
<script>
    const waBtn = document.getElementById("wa-button");
    const waInfo = document.getElementById("wa-info");

    waBtn.addEventListener("click", (e) => {
        e.stopPropagation(); // mencegah klik bubble
        waInfo.classList.toggle("hidden");
    });

    document.addEventListener("click", function (e) {
        if (!waBtn.contains(e.target) && !waInfo.contains(e.target)) {
            waInfo.classList.add("hidden");
        }
    });
</script>


</body>
</html>
