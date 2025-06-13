<?= $this->extend('layouts/index') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card booking-card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Booking Berhasil!</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>Booking kamar berhasil dibuat.
                </div>

                <div class="mb-4">
                    <h5>Detail Booking</h5>
                    <p class="mb-1"><strong>ID Booking:</strong> #<?= esc($booking['id_booking']) ?></p>
                    <p class="mb-1"><strong>Nama:</strong> <?= esc($booking['nama']) ?></p>
                    <p class="mb-1"><strong>Kamar:</strong> <?= esc($kamar['nomor_kamar']) ?></p>
                    <p class="mb-1"><strong>Metode Pembayaran:</strong>
                        <?php
                            $label = [
                                'transfer' => 'Transfer Bank',
                                'ewallet'  => 'E-Wallet',
                                'cod'      => 'Bayar di Tempat'
                            ];
                            echo esc($label[$transaksi['metode_pembayaran']] ?? 'Belum dipilih');
                        ?>
                    </p>
                    <p class="mb-1"><strong>Status:</strong> 
                        <span class="badge bg-warning text-dark"><?= esc($booking['status']) ?></span>
                    </p>
                </div>

                <?php if (isset($transaksi['metode_pembayaran'])): ?>
                    <div class="border-top pt-3">
                        <h5>Instruksi Pembayaran</h5>
                        <div class="bg-light p-3 rounded">
                            <?php if ($transaksi['metode_pembayaran'] === 'transfer'): ?>
                                <p class="mb-1"><strong>Bank:</strong> BCA</p>
                                <p class="mb-1"><strong>Rekening:</strong> 123 456 7890</p>
                                <p class="mb-1"><strong>Atas Nama:</strong> Kosan Kita</p>
                                <p class="mb-1"><strong>Jumlah:</strong> Rp<?= number_format($kamar['harga_kamar'], 0, ',', '.') ?></p>
                                <p class="mt-2 text-muted small">Harap lakukan pembayaran dalam 1x24 jam</p>

                            <?php elseif ($transaksi['metode_pembayaran'] === 'ewallet'): ?>
                                <p class="mb-1"><strong>Provider:</strong> OVO/Gopay/DANA</p>
                                <p class="mb-1"><strong>Nomor:</strong> 0881023072302</p>
                                <p class="mb-1"><strong>Atas Nama:</strong> Kosan Kita</p>
                                <p class="mb-1"><strong>Jumlah:</strong> Rp<?= number_format($kamar['harga_kamar'], 0, ',', '.') ?></p>
                                <p class="mt-2 text-muted small">Harap lakukan pembayaran dalam 1x24 jam</p>

                            <?php elseif ($transaksi['metode_pembayaran'] === 'cod'): ?>
                                <p class="mb-1">Silahkan hubungi WhatsApp Ibu Kos untuk konfirmasi pembayaran:</p>
                                <a href="https://wa.me/62881023072302" class="btn btn-success">
                                    <i class="fab fa-whatsapp me-2"></i>Hubungi via WhatsApp
                                </a>
                                <p class="mt-2 text-muted small">Pembayaran tunai dilakukan maksimal 1x24 jam setelah booking</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary w-100">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
