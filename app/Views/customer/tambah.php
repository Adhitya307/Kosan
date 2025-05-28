<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
    <h1><?= $title ?></h1>

    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <form action="/customer/store" method="post">
        <?= csrf_field() ?>

<div class="mb-3">
    <label for="nama" class="form-label">Nama</label>
    <input
        type="text"
        class="form-control <?= ($validation->hasError('nama')) ? 'is-invalid' : '' ?>"
        id="nama"
        name="nama"
        value="<?= old('nama') ?>"
    />
    <div class="invalid-feedback">
        <?= $validation->getError('nama') ?>
    </div>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input
        type="email"
        class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>"
        id="email"
        name="email"
        value="<?= old('email') ?>"
    />
    <div class="invalid-feedback">
        <?= $validation->getError('email') ?>
    </div>
</div>

<div class="mb-3">
    <label for="telepon" class="form-label">Telepon</label>
    <input
        type="text"
        class="form-control <?= ($validation->hasError('telepon')) ? 'is-invalid' : '' ?>"
        id="telepon"
        name="telepon"
        value="<?= old('telepon') ?>"
    />
    <div class="invalid-feedback">
        <?= $validation->getError('telepon') ?>
    </div>
</div>

<div class="mb-3">
    <label for="alamat" class="form-label">Alamat</label>
    <textarea
        class="form-control <?= ($validation->hasError('alamat')) ? 'is-invalid' : '' ?>"
        id="alamat"
        name="alamat"
        rows="3"
    ><?= old('alamat') ?></textarea>
    <div class="invalid-feedback">
        <?= $validation->getError('alamat') ?>
    </div>
</div>


        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/customer" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
