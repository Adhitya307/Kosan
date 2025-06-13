<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= esc($title) ?></title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/informasi.css') ?>">

    <style>
        /* --- Modal Styles --- */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            max-width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s ease;
            position: relative;
        }

        #imgModal .modal-content {
            max-width: 600px;
            padding: 10px;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 26px;
            color: #aaa;
            background: none;
            border: none;
            cursor: pointer;
        }

        .close:hover { color: #000; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .status-badge { cursor: pointer; }

        .bukti-pembayaran-img {
            max-height: 50px;
            border-radius: 5px;
            object-fit: contain;
            border: 1px solid #ccc;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .bukti-pembayaran-img:hover { transform: scale(1.05); }

        .upload-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .booking-table-container {
            overflow-x: auto;
        }

        #imgModal #imgModalDesc {
            text-align: center;
            margin-top: 10px;
            font-style: italic;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <header class="booking-header">
            <h1><i class="fas fa-calendar-check"></i> <?= esc($title) ?></h1>
            <p>Informasi lengkap tentang booking kamar Anda</p>
        </header>

        <section class="booking-card">
            <?php if (empty($bookings)) : ?>
                <div class="empty-state" style="text-align:center; padding: 50px;">
                    <i class="fas fa-calendar-times" style="font-size: 48px; color: #ccc;"></i>
                    <h3>Belum ada data booking</h3>
                    <p>Anda belum melakukan booking kamar kos</p>
                </div>
            <?php else : ?>
                <div class="booking-table-container">
                    <table class="booking-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kamar</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking) : ?>
                                <tr>
                                    <td><?= esc($booking['nama']) ?></td>
                                    <td>No. <?= esc($booking['nomor_kamar']) ?></td>
                                    <td>Rp<?= number_format($booking['harga_kamar'], 0, ',', '.') ?>/bln</td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower(esc($booking['status'])) ?>">
                                            <?= esc($booking['status']) ?>
                                        </span>
                                    </td>
                                    <td style="display: flex; align-items: center; gap: 10px;">
                                        <?= esc(date('d M Y H:i', strtotime($booking['tanggal_booking']))) ?>
                                        <?php if (!empty($booking['bukti_pembayaran'])) : ?>
                                            <img 
                                                src="<?= base_url('uploads/bukti_pembayaran/' . esc($booking['bukti_pembayaran'])) ?>" 
                                                alt="Bukti Pembayaran <?= esc($booking['nama']) ?>" 
                                                class="bukti-pembayaran-img clickable-img"
                                                title="Klik untuk memperbesar bukti pembayaran"
                                            />
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($booking['bukti_pembayaran'])) : ?>
                                            <span style="color: green; font-weight: bold;">
                                                <i class="fas fa-check-circle"></i> Sudah Upload
                                            </span>
                                        <?php else : ?>
                                            <a href="<?= base_url('transaksi/create/' . $booking['id_booking']) ?>" 
                                               class="btn-upload"
                                               style="padding: 6px 12px; background-color: #007bff; color: white; border-radius: 4px; text-decoration: none;">
                                                Upload
                                            </a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </section>

        <div style="text-align: center; margin-top: 40px;">
            <a href="<?= base_url('dashboard') ?>" class="btn-primary" style="padding: 10px 25px;">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Modal Detail Booking -->
    <div id="bookingModal" class="modal" aria-hidden="true" role="dialog">
        <div class="modal-content">
            <button class="close" aria-label="Tutup">&times;</button>
            <h2>Detail Booking</h2>
            <p><strong>Nama:</strong> <span id="modalNama"></span></p>
            <p><strong>No. Kamar:</strong> <span id="modalKamar"></span></p>
            <p><strong>Harga:</strong> <span id="modalHarga"></span></p>
            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            <p><strong>Tanggal Booking:</strong> <span id="modalTanggal"></span></p>
        </div>
    </div>

    <!-- Modal Zoom Gambar Bukti Pembayaran -->
    <div id="imgModal" class="modal" aria-hidden="true" role="dialog">
        <div class="modal-content">
            <button class="close" aria-label="Tutup">&times;</button>
            <img id="modalImg" src="" alt="Zoom Bukti Pembayaran" style="width: 100%; height: auto; border-radius: 8px;" />
            <p id="imgModalDesc"></p>
        </div>
    </div>

    <!-- Modal Logic -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const bookingModal = document.getElementById("bookingModal");
            const imgModal = document.getElementById("imgModal");
            const modalImg = document.getElementById("modalImg");
            const modalDesc = document.getElementById("imgModalDesc");

            document.querySelectorAll(".status-badge").forEach(badge => {
                badge.addEventListener("click", () => {
                    const row = badge.closest("tr");
                    document.getElementById("modalNama").textContent = row.cells[0].textContent.trim();
                    document.getElementById("modalKamar").textContent = row.cells[1].textContent.trim();
                    document.getElementById("modalHarga").textContent = row.cells[2].textContent.trim();
                    document.getElementById("modalStatus").textContent = row.cells[3].textContent.trim();
                    document.getElementById("modalTanggal").textContent = row.cells[4].textContent.trim();
                    bookingModal.style.display = "block";
                });
            });

            bookingModal.querySelector(".close").onclick = () => bookingModal.style.display = "none";
            imgModal.querySelector(".close").onclick = () => {
                imgModal.style.display = "none";
                modalImg.src = "";
            };

            document.querySelectorAll(".bukti-pembayaran-img").forEach(img => {
                img.addEventListener("click", () => {
                    modalImg.src = img.src;
                    modalImg.alt = img.alt;
                    modalDesc.textContent = img.alt;
                    imgModal.style.display = "block";
                });
            });

            window.addEventListener("click", e => {
                if (e.target === bookingModal) bookingModal.style.display = "none";
                if (e.target === imgModal) {
                    imgModal.style.display = "none";
                    modalImg.src = "";
                }
            });
        });
    </script>
</body>
</html>
