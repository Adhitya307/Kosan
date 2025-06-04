<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<!-- Load the CSS file -->
<link rel="stylesheet" href="<?= base_url('/css/kamar.css'); ?>">

<div class="container mt-4">
    <div class="header-container">
        <h2>Daftar Kamar Kos</h2>
        <div class="action-buttons">
            <a href="<?= site_url('kamar/create'); ?>" class="btn btn-add">
                <i class="fas fa-plus-circle"></i> Tambah Kamar
            </a>
            <a href="<?= site_url('dashboard'); ?>" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show flash-message" role="alert">
            <?= session()->getFlashdata('message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table kamar-table">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nomor Kamar</th>
                            <th>Harga</th>
                            <th>Fasilitas</th>
                            <th>Status</th>
                            <th>Foto</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kamar as $i => $k): ?>
                            <tr>
                                <td class="serial-number"><?= $i + 1; ?></td>
                                <td class="room-number"><?= esc($k['nomor_kamar']); ?></td>
                                <td class="room-price">Rp<?= number_format($k['harga_kamar'], 0, ',', '.'); ?></td>
                                <td class="room-facilities">
                                    <div class="facilities-container">
                                        <?php 
                                        $fasilitas = explode(',', $k['fasilitas']);
                                        foreach ($fasilitas as $f): 
                                        ?>
                                            <span class="facility-badge"><?= trim($f) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td class="room-status">
                                    <span class="status-badge <?= $k['status_kamar'] === 'tersedia' ? 'available' : 'occupied' ?>">
                                        <?= ucfirst($k['status_kamar']); ?>
                                    </span>
                                </td>
                                <td class="room-photos">
                                    <?php 
                                    $fotos = json_decode($k['foto'], true);
                                    if (is_array($fotos) && count($fotos) > 0): ?>
                                        <div class="photos-container">
                                            <?php foreach ($fotos as $foto): ?>
                                                <img src="<?= base_url('uploads/kamar/' . $foto); ?>" class="room-photo" alt="Kamar <?= esc($k['nomor_kamar']); ?>">
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="no-photo">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <div class="btn-group">
                                        <a href="<?= site_url('kamar/edit/' . $k['id_kamar']); ?>" class="btn btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= site_url('kamar/delete/' . $k['id_kamar']); ?>" method="post" class="delete-form" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?');">
                                            <?= csrf_field(); ?>
                                            <button type="submit" class="btn btn-delete" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if (empty($kamar)): ?>
        <div class="empty-state">
            <img src="<?= base_url('assets/img/empty.svg'); ?>" alt="Empty" class="empty-image">
            <h5>Belum ada data kamar</h5>
            <a href="<?= site_url('kamar/create'); ?>" class="btn btn-add">
                <i class="fas fa-plus-circle"></i> Tambah Kamar Pertama
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>