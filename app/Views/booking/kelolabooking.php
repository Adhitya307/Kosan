<?= $this->extend('layouts/index') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/kelolabooking.css') ?>">

<div class="booking-container">
    <div class="booking-header">
        <h1><?= $title ?></h1>
        <p>Kelola status booking kamar kos</p>
    </div>
    
    <div class="booking-table-container">
        <table id="booking-table">
            <thead>
                <tr>
                    <th>ID Booking</th>
                    <th>Nama</th>
                    <th>Kamar</th>
                    <th>Status Booking</th>
                    <th>Status Pembayaran</th>
                    <th>Metode Pembayaran</th>
                    <th>Bukti Pembayaran</th>
                    <th>Tanggal Booking</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <?php
                        // Cari nama kamar berdasarkan id_kamar
                        $kamarNama = '';
                        foreach ($kamars as $k) {
                            if ($k['id_kamar'] == $booking['id_kamar']) {
                                $kamarNama = $k['nomor_kamar'];
                                break;
                            }
                        }
                        $statusOptions = ['Menunggu Pembayaran', 'Lunas', 'Dibatalkan'];
                    ?>
                    <tr>
                        <td><?= $booking['id_booking'] ?></td>
                        <td><?= esc($booking['nama']) ?></td>
                        <td><?= esc($kamarNama) ?></td>
                        <td>
                            <select class="status-dropdown <?= strtolower($booking['status']) ?>" data-id="<?= $booking['id_booking'] ?>">
                                <?php foreach ($statusOptions as $status): ?>
                                    <option value="<?= $status ?>" <?= $booking['status'] === $status ? 'selected' : '' ?>>
                                        <?= $status ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><?= esc($booking['status_pembayaran'] ?? '-') ?></td>
                        <td><?= esc($booking['metode_pembayaran'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($booking['bukti_pembayaran'])): ?>
                                <a href="<?= base_url('uploads/bukti/' . $booking['bukti_pembayaran']) ?>" target="_blank">Lihat Bukti</a>
                            <?php else: ?>
                                <em>Belum Upload</em>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d M Y', strtotime($booking['tanggal_booking'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="toast" class="toast-notification">
    <i class="fas fa-check-circle"></i>
    <span class="toast-message">Status berhasil diubah!</span>
</div>

<script>
document.querySelectorAll('.status-dropdown').forEach(function(dropdown){
    dropdown.addEventListener('change', function(){
        const idBooking = this.dataset.id;
        const newStatus = this.value;
        const toast = document.getElementById('toast');
        const toastMessage = toast.querySelector('.toast-message');

        fetch('<?= base_url('booking/updateStatus') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({
                id_booking: idBooking,
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                toastMessage.textContent = 'Status berhasil diubah!';
                toast.className = 'toast-notification show';
                
                // Update warna dropdown
                dropdown.className = 'status-dropdown';
                if(newStatus === 'Lunas') {
                    dropdown.classList.add('status-success');
                } else if(newStatus === 'Dibatalkan') {
                    dropdown.classList.add('status-danger');
                } else {
                    dropdown.classList.add('status-warning');
                }
            } else {
                toastMessage.textContent = 'Gagal mengubah status!';
                toast.className = 'toast-notification show error';
            }

            setTimeout(() => {
                toast.className = 'toast-notification';
            }, 3000);
        })
        .catch(() => {
            toastMessage.textContent = 'Terjadi kesalahan saat menghubungi server.';
            toast.className = 'toast-notification show error';
            setTimeout(() => {
                toast.className = 'toast-notification';
            }, 3000);
        });
    });
});
</script>
<?= $this->endSection() ?>
