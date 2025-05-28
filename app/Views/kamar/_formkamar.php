<!-- app/Views/kamar/_formkamar.php -->

<div class="mb-3">
    <label for="nomor_kamar" class="form-label">Nomor Kamar</label>
    <input type="text" 
           name="nomor_kamar" 
           id="nomor_kamar" 
           class="form-control <?= (isset(session()->getFlashdata('errors')['nomor_kamar'])) ? 'is-invalid' : '' ?>" 
           value="<?= old('nomor_kamar', isset($kamar['nomor_kamar']) ? $kamar['nomor_kamar'] : '') ?>" 
           required>
    <div class="invalid-feedback">
        <?= session()->getFlashdata('errors')['nomor_kamar'] ?? '' ?>
    </div>
</div>

<div class="mb-3">
    <label for="harga_kamar" class="form-label">Harga Kamar</label>
    <input type="number" step="0.01" min="0" 
           name="harga_kamar" 
           id="harga_kamar" 
           class="form-control <?= (isset(session()->getFlashdata('errors')['harga_kamar'])) ? 'is-invalid' : '' ?>" 
           value="<?= old('harga_kamar', isset($kamar['harga_kamar']) ? $kamar['harga_kamar'] : '') ?>" 
           required>
    <div class="invalid-feedback">
        <?= session()->getFlashdata('errors')['harga_kamar'] ?? '' ?>
    </div>
</div>

<div class="mb-3">
    <label class="form-label d-block">Fasilitas</label>

<?php 
$allowedFasilitas = [
    'Kamar Mandi Dalam',
    'Wi-Fi / Internet',
    'AC (Air Conditioner)',
    'Tempat Parkir',
    'Listrik 24 Jam',
    'Laundry',
    'Dapur Bersama'
];

// Ambil old input fasilitas (array)
$oldFasilitas = old('fasilitas');

if (!$oldFasilitas && isset($kamar['fasilitas'])) {
    // Dari database biasanya JSON string, decode dulu
    $oldFasilitas = json_decode($kamar['fasilitas'], true);
}

if (!is_array($oldFasilitas)) {
    $oldFasilitas = [];
}

foreach ($allowedFasilitas as $fasilitas) : 
    $checked = in_array($fasilitas, $oldFasilitas);
?>
    <div class="form-check form-check-inline">
        <input class="form-check-input <?= (isset(session()->getFlashdata('errors')['fasilitas'])) ? 'is-invalid' : '' ?>" 
               type="checkbox" 
               name="fasilitas[]" 
               id="fasilitas_<?= md5($fasilitas) ?>" 
               value="<?= $fasilitas ?>" 
               <?= $checked ? 'checked' : '' ?>>
        <label class="form-check-label" for="fasilitas_<?= md5($fasilitas) ?>">
            <?= $fasilitas ?>
        </label>
    </div>
<?php endforeach; ?>
<div class="invalid-feedback d-block">
    <?= session()->getFlashdata('errors')['fasilitas'] ?? '' ?>
</div>


<div class="mb-3">
    <label for="status_kamar" class="form-label">Status Kamar</label>
    <select name="status_kamar" id="status_kamar" class="form-select <?= (isset(session()->getFlashdata('errors')['status_kamar'])) ? 'is-invalid' : '' ?>" required>
        <?php 
        $statusOld = old('status_kamar', isset($kamar['status_kamar']) ? $kamar['status_kamar'] : 'tersedia');
        $statuses = ['tersedia', 'dipesan', 'ditempati', 'maintenance'];
        foreach ($statuses as $status) : ?>
            <option value="<?= $status ?>" <?= ($statusOld === $status) ? 'selected' : '' ?>>
                <?= ucfirst($status) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="invalid-feedback">
        <?= session()->getFlashdata('errors')['status_kamar'] ?? '' ?>
    </div>
</div>

<div class="mb-3">
    <label for="foto" class="form-label">Foto Kamar</label>
    <input type="file" 
           name="foto[]" 
           id="foto" 
           class="form-control <?= (isset(session()->getFlashdata('errors')['foto'])) ? 'is-invalid' : '' ?>" 
           multiple>
    <div class="invalid-feedback">
        <?= session()->getFlashdata('errors')['foto'] ?? '' ?>
    </div>
</div>

<?php if (isset($kamar['foto'])): ?>
    <?php 
    $fotos = json_decode($kamar['foto'], true);
    if (is_array($fotos)) : 
        foreach ($fotos as $foto) : ?>
            <img src="<?= base_url('uploads/kamar/' . $foto) ?>" width="150" alt="Foto Kamar" style="margin-right:10px; margin-bottom:10px;">
        <?php endforeach; 
    endif; ?>
<?php endif; ?>

