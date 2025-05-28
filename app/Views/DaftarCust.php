<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="fas fa-users me-2"></i><?= esc($title) ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data Customer</li>
                </ol>
            </nav>
        </div>
        <a href="<?= base_url('customer/tambah') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Tambah Customer
        </a>
    </div>

<?php if (session()->getFlashdata('message')): ?>
    <?php
        // Deteksi jenis pesan: jika mengandung kata "sudah ada", anggap error
        $message = session()->getFlashdata('message');
        $isError = stripos($message, 'sudah ada') !== false;

        // Tentukan jenis alert dan ikon berdasarkan tipe pesan
        $alertType = $isError ? 'danger' : 'success';
        $icon = $isError ? 'fas fa-exclamation-circle' : 'fas fa-check-circle';
    ?>
    <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
        <i class="<?= $icon ?> me-2"></i>
        <?= esc($message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customers)) : ?>
                            <?php foreach ($customers as $i => $customer) : ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td class="fw-semibold"><?= esc($customer['nama']) ?></td>
                                    <td><?= esc($customer['email']) ?></td>
                                    <td><?= esc($customer['telepon']) ?></td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                              data-bs-toggle="tooltip" title="<?= esc($customer['alamat']) ?>">
                                            <?= esc($customer['alamat']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('customer/edit/' . $customer['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?= base_url('customer/delete/' . $customer['id']) ?>" 
                                                  method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="<?= base_url('assets/img/empty.svg') ?>" alt="Empty" width="200" class="mb-3">
                                        <h5 class="text-muted">Data customer tidak ditemukan</h5>
                                        <a href="<?= base_url('customer/tambah') ?>" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus-circle me-1"></i> Tambah Customer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
    }
    
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .table thead th {
        border-bottom: none;
        padding: 12px 16px;
        background-color: #f8f9fa;
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
    
    .breadcrumb {
        background-color: transparent;
        padding: 0;
        font-size: 0.9rem;
    }
</style>

<script>
    // Inisialisasi tooltip
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Auto close alert
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>

<?= $this->endSection(); ?>