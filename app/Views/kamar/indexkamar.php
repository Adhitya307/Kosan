<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Daftar Kamar Kos</h2>
        <div>
            <a href="<?= site_url('kamar/create'); ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Kamar
            </a>
            <a href="<?= site_url('dashboard'); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
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
                                <td><?= $i + 1; ?></td>
                                <td class="fw-bold"><?= esc($k['nomor_kamar']); ?></td>
                                <td>Rp<?= number_format($k['harga_kamar'], 0, ',', '.'); ?></td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <?php 
                                        $fasilitas = explode(',', $k['fasilitas']);
                                        foreach ($fasilitas as $f): 
                                        ?>
                                            <span class="badge bg-light text-dark border"><?= trim($f) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge <?= $k['status_kamar'] === 'tersedia' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= ucfirst($k['status_kamar']); ?>
                                    </span>
                                </td>
<td>
    <?php 
    $fotos = json_decode($k['foto'], true);
    if (is_array($fotos) && count($fotos) > 0): ?>
        <div class="d-flex flex-wrap gap-1">
            <?php foreach ($fotos as $foto): ?>
                <img src="<?= base_url('uploads/kamar/' . $foto); ?>" class="rounded" width="80" height="60" style="object-fit: cover;">
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <span class="text-muted">Tidak ada</span>
    <?php endif; ?>
</td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?= site_url('kamar/edit/' . $k['id_kamar']); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= site_url('kamar/delete/' . $k['id_kamar']); ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?');">
                                            <?= csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
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
        <div class="text-center py-5">
            <img src="<?= base_url('assets/img/empty.svg'); ?>" alt="Empty" width="200" class="mb-3">
            <h5 class="text-muted">Belum ada data kamar</h5>
            <a href="<?= site_url('kamar/create'); ?>" class="btn btn-primary mt-3">
                <i class="fas fa-plus-circle"></i> Tambah Kamar Pertama
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
    .table th {
        white-space: nowrap;
    }
    .badge {
        font-weight: 500;
    }
    .card {
        border-radius: 10px;
        border: none;
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .table thead th {
        border-bottom: none;
        padding: 12px 16px;
    }
    .table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }
    .btn-outline-primary {
        border-color: #4361ee;
        color: #4361ee;
    }
    .btn-outline-primary:hover {
        background-color: #4361ee;
        color: white;
    }
    .btn-outline-danger {
        border-color: #f72585;
        color: #f72585;
    }
    .btn-outline-danger:hover {
        background-color: #f72585;
        color: white;
    }
</style>

<?= $this->endSection(); ?>