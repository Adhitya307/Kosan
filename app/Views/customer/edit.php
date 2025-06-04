<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="/css/customer_form.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="form-wrapper">
    <div class="form-decoration"></div>
    
    <div class="form-container">
        <div class="form-header">
            <div class="logo-container">
                <i class="fas fa-user-edit logo-icon"></i>
                <h1><?= esc($title) ?></h1>
            </div>
            <p class="form-subtitle">Perbarui data pelanggan dengan informasi terbaru</p>
        </div>

        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger alert-flash">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="alert-content">
                    <ul class="error-list">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form action="/customer/update/<?= esc($customer['id']) ?>" method="post" class="elegant-form">
            <?= csrf_field() ?>

            <div class="form-section">
                <h3 class="section-title">
                    <span class="section-icon">
                        <i class="fas fa-id-card"></i>
                    </span>
                    Informasi Pribadi
                </h3>
                
                <div class="form-row">
                    <div class="form-group floating">
                        <input
                            type="text"
                            class="form-control <?= session()->getFlashdata('errors.nama') ? 'is-invalid' : '' ?>"
                            id="nama"
                            name="nama"
                            value="<?= esc(old('nama', $customer['nama'])) ?>"
                            placeholder=" "
                            required
                        />
                        <label for="nama">Nama Lengkap</label>
                        <?php if (session()->getFlashdata('errors.nama')): ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= esc(session()->getFlashdata('errors.nama')) ?>
                        </div>
                        <?php endif; ?>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group floating">
                        <input
                            type="email"
                            class="form-control <?= session()->getFlashdata('errors.email') ? 'is-invalid' : '' ?>"
                            id="email"
                            name="email"
                            value="<?= esc(old('email', $customer['email'])) ?>"
                            placeholder=" "
                            required
                        />
                        <label for="email">Alamat Email</label>
                        <?php if (session()->getFlashdata('errors.email')): ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= esc(session()->getFlashdata('errors.email')) ?>
                        </div>
                        <?php endif; ?>
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">
                    <span class="section-icon">
                        <i class="fas fa-address-book"></i>
                    </span>
                    Kontak & Alamat
                </h3>

                <div class="form-row">
                    <div class="form-group floating">
                        <input
                            type="text"
                            class="form-control <?= session()->getFlashdata('errors.telepon') ? 'is-invalid' : '' ?>"
                            id="telepon"
                            name="telepon"
                            value="<?= esc(old('telepon', $customer['telepon'])) ?>"
                            placeholder=" "
                            required
                        />
                        <label for="telepon">Nomor Telepon</label>
                        <?php if (session()->getFlashdata('errors.telepon')): ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= esc(session()->getFlashdata('errors.telepon')) ?>
                        </div>
                        <?php endif; ?>
                        <div class="input-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group floating">
                        <textarea
                            class="form-control <?= session()->getFlashdata('errors.alamat') ? 'is-invalid' : '' ?>"
                            id="alamat"
                            name="alamat"
                            placeholder=" "
                            rows="3"
                            required
                        ><?= esc(old('alamat', $customer['alamat'])) ?></textarea>
                        <label for="alamat">Alamat Lengkap</label>
                        <?php if (session()->getFlashdata('errors.alamat')): ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= esc(session()->getFlashdata('errors.alamat')) ?>
                        </div>
                        <?php endif; ?>
                        <div class="input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-update">
                    <i class="fas fa-sync-alt"></i>
                    <span>Perbarui Data</span>
                </button>
                <a href="/customer" class="btn btn-cancel">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>