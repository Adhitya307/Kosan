<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/informasi.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <div class="booking-header">
            <h1><i class="fas fa-calendar-check"></i> <?= esc($title) ?></h1>
            <p>Informasi lengkap tentang booking kamar Anda</p>
        </div>

        <div class="booking-card">
            <?php if (empty($bookings)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>Belum ada data booking</h3>
                    <p>Anda belum melakukan booking kamar kos</p>
                    <a href="<?= base_url('kamar') ?>" class="btn-primary">
                        <i class="fas fa-search"></i> Cari Kamar
                    </a>
                </div>
            <?php else: ?>
                <div class="booking-table-container">
                    <table class="booking-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Nama</th>
                                <th><i class="fas fa-door-open"></i> Kamar</th>
                                <th><i class="fas fa-tag"></i> Harga</th>
                                <th><i class="fas fa-info-circle"></i> Status</th>
                                <th><i class="fas fa-calendar-day"></i> Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?= esc($booking['nama']) ?></td>
                                    <td>No. <?= esc($booking['nomor_kamar']) ?></td>
                                    <td>Rp<?= number_format($booking['harga_kamar'], 0, ',', '.') ?>/bln</td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($booking['status']) ?>">
                                            <?= esc($booking['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= esc(date('d M Y H:i', strtotime($booking['tanggal_booking']))) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>

        <!-- Tombol Kembali -->
        <div style="text-align:center; margin-top: 40px;">
            <a href="<?= base_url('dashboard') ?>" class="btn-primary" style="padding: 10px 25px; display: inline-block;">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>
