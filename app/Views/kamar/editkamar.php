<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container mt-4">
    <h2>Edit Data Kamar</h2>

    <!-- Alert sukses -->
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message'); ?>
        </div>
    <?php endif; ?>

    <!-- Alert error validasi -->
    <?php if (isset($validation) && $validation->getErrors()) : ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($validation->getErrors() as $error) : ?>
                    <li><?= esc($error); ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('kamar/update/' . $kamar['id_kamar']); ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>
        <input type="hidden" name="_method" value="PUT">
        <?= $this->include('kamar/_formkamar'); ?>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url('kamar'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?= $this->endSection(); ?>
