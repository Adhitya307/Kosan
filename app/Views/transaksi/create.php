<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<h2>Form Transaksi Pembayaran</h2>

<!-- ✅ Feedback pesan -->
<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<form action="<?= base_url('/transaksi/store') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field(); ?>
    <input type="hidden" name="id_booking" value="<?= esc($booking['id_booking'] ?? '') ?>" />

    <div class="mb-3">
        <label>Total Pembayaran:</label>
        <input type="text" 
               value="Rp<?= number_format($kamar['harga_kamar'] ?? 0, 0, ',', '.') ?>" 
               readonly 
               class="form-control" />
        <input type="hidden" name="jumlah_pembayaran" value="<?= esc($kamar['harga_kamar'] ?? 0) ?>">
    </div>

    <div class="mb-3">
        <label>Metode Pembayaran:</label>
        <?php 
            $metode = $transaksi['metode_pembayaran'] ?? $booking['metode_pembayaran'] ?? '-'; 
        ?>
        <input type="text" class="form-control" value="<?= esc($metode) ?>" readonly>
        <input type="hidden" name="metode_pembayaran" value="<?= esc($metode) ?>">
    </div>

    <div class="mb-3">
        <label for="bukti_pembayaran">Upload Bukti Pembayaran (jika ada)</label>
        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*" />
        <small class="form-text text-muted">Upload wajib kecuali jika memilih metode "Cash / Bayar di Tempat".</small>
    </div>

<div class="d-flex justify-content-between">
    <button type="submit" class="btn btn-primary">Bayar</button>
    <a href="<?= base_url('/informasi_booking') ?>" class="btn btn-secondary">Kembali</a>
</div>


</form>

<!-- ✅ Script untuk atur required file upload -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const metode = "<?= esc($metode) ?>";
        const buktiInput = document.getElementById('bukti_pembayaran');

        if (metode === 'cod') {
            buktiInput.required = false;
        } else {
            buktiInput.required = true;
        }
    });
</script>

<?= $this->endSection(); ?>
