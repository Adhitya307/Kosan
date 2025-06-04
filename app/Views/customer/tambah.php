<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $title ?></title>
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
                <i class="fas fa-user-tie logo-icon"></i>
                <h1><?= $title ?></h1>
            </div>
            <p class="form-subtitle">Silakan lengkapi data pelanggan dengan benar</p>
        </div>

        <?php if(session()->getFlashdata('message')): ?>
            <div class="alert alert-success alert-flash">
                <div class="alert-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-content">
                    <?= session()->getFlashdata('message') ?>
                </div>
            </div>
        <?php endif; ?>

        <form action="/customer/store" method="post" class="elegant-form">
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
                            class="form-control <?= ($validation->hasError('nama')) ? 'is-invalid' : '' ?>"
                            id="nama"
                            name="nama"
                            value="<?= old('nama') ?>"
                            placeholder=" "
                        />
                        <label for="nama">Nama Lengkap</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group floating">
                        <input
                            type="email"
                            class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>"
                            id="email"
                            name="email"
                            value="<?= old('email') ?>"
                            placeholder=" "
                        />
                        <label for="email">Alamat Email</label>
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
                            class="form-control <?= ($validation->hasError('telepon')) ? 'is-invalid' : '' ?>"
                            id="telepon"
                            name="telepon"
                            value="<?= old('telepon') ?>"
                            placeholder=" "
                        />
                        <label for="telepon">Nomor Telepon</label>
                        <div class="input-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group floating">
                        <textarea
                            class="form-control <?= ($validation->hasError('alamat')) ? 'is-invalid' : '' ?>"
                            id="alamat"
                            name="alamat"
                            placeholder=" "
                            rows="3"
                        ><?= old('alamat') ?></textarea>
                        <label for="alamat">Alamat Lengkap</label>
                        <div class="input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i>
                    <span>Simpan Data</span>
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