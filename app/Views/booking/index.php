<?= $this->extend('layouts/index') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card booking-card">
            <div class="card-header text-white">
                <h4 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Booking Kamar <?= esc($kamar['nomor_kamar']) ?></h4>
            </div>
            
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Detail Kamar</h5>
                    
                    <div class="detail-item">
                        <strong>Nomor:</strong>
                        <span><?= esc($kamar['nomor_kamar']) ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <strong>Harga:</strong>
                        <span class="fw-bold text-primary">Rp<?= number_format($kamar['harga_kamar'], 0, ',', '.') ?>/bulan</span>
                    </div>
                    
                    <?php
                    $fotos = json_decode($kamar['foto'], true);
                    if (is_array($fotos) && count($fotos) > 0):
                    ?>
                        <div class="kamar-foto-gallery">
                            <div id="kamarCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <?php foreach ($fotos as $index => $foto): ?>
                                        <button type="button" data-bs-target="#kamarCarousel" data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active"' : '' ?>></button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="carousel-inner">
                                    <?php foreach ($fotos as $index => $foto): 
                                        $path = FCPATH . 'uploads/kamar/' . $foto;
                                        if (file_exists($path)):
                                    ?>
                                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                            <img src="<?= base_url('uploads/kamar/' . $foto) ?>" class="d-block w-100" alt="Foto Kamar">
                                        </div>
                                    <?php
                                        endif;
                                    endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#kamarCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#kamarCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada foto tersedia.
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <h6 class="fw-bold"><i class="fas fa-list-check text-primary me-2"></i>Fasilitas Kamar</h6>
                        <ul class="fasilitas-list mt-3">
                            <?php
                            $fasilitas = json_decode($kamar['fasilitas'], true);
                            if ($fasilitas && is_array($fasilitas)) {
                                foreach ($fasilitas as $f) {
                                    echo '<li>' . esc($f) . '</li>';
                                }
                            } else {
                                echo '<li>Tidak ada fasilitas khusus.</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <form action="<?= base_url('booking/process') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="id_kamar" value="<?= esc($kamar['id_kamar']) ?>">

    <!-- Data Pemesan -->
    <h5 class="fw-bold mb-3"><i class="fas fa-user-edit text-primary me-2"></i>Data Pemesan</h5>

    <div class="mb-4">
        <label for="nama" class="form-label fw-medium">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama" name="nama"
               value="<?= old('nama') ?? (session()->get('user')['nama'] ?? '') ?>" required>
    </div>

    <!-- Metode Pembayaran -->
    <div class="mb-4">
        <h5 class="fw-bold mb-3"><i class="fas fa-credit-card text-primary me-2"></i>Pilih Metode Pembayaran</h5>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="metode_pembayaran" id="transfer" value="transfer" checked>
            <label class="form-check-label" for="transfer">
                Transfer Bank
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="metode_pembayaran" id="ewallet" value="ewallet">
            <label class="form-check-label" for="ewallet">
                E-Wallet (OVO, GoPay, dll)
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="metode_pembayaran" id="cod" value="cod">
            <label class="form-check-label" for="cod">
                Cash
            </label>
        </div>
    </div>

    <button type="submit" class="btn btn-booking w-100 mt-3">
        <i class="fas fa-check-circle me-2"></i>Konfirmasi Booking
    </button>
</form>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
