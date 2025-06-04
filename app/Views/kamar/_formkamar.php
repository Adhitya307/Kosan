<link rel="stylesheet" href="/css/formkamar.css">

<div class="form-container">
    <!-- Nomor Kamar -->
    <div class="mb-4">
        <label for="nomor_kamar" class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light">
                <i class="fas fa-door-open"></i>
            </span>
            <input type="text" 
                   name="nomor_kamar" 
                   id="nomor_kamar" 
                   class="form-control <?= (isset(session()->getFlashdata('errors')['nomor_kamar'])) ? 'is-invalid' : '' ?>" 
                   value="<?= old('nomor_kamar', isset($kamar['nomor_kamar']) ? $kamar['nomor_kamar'] : '') ?>" 
                   placeholder="Masukkan nomor kamar"
                   required>
        </div>
        <div class="invalid-feedback">
            <?= session()->getFlashdata('errors')['nomor_kamar'] ?? '' ?>
        </div>
    </div>

    <!-- Harga Kamar -->
    <div class="mb-4">
        <label for="harga_kamar" class="form-label">Harga Kamar <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light">Rp</span>
            <input type="number" 
                   step="100000" 
                   min="0" 
                   name="harga_kamar" 
                   id="harga_kamar" 
                   class="form-control <?= (isset(session()->getFlashdata('errors')['harga_kamar'])) ? 'is-invalid' : '' ?>" 
                   value="<?= old('harga_kamar', isset($kamar['harga_kamar']) ? $kamar['harga_kamar'] : '') ?>" 
                   placeholder="Masukkan harga kamar"
                   required>
            <span class="input-group-text bg-light">/bulan</span>
        </div>
        <div class="invalid-feedback">
            <?= session()->getFlashdata('errors')['harga_kamar'] ?? '' ?>
        </div>
        <small class="text-muted">Gunakan angka tanpa titik atau koma</small>
    </div>

    <!-- Fasilitas -->
    <div class="mb-4">
        <label class="form-label d-block">Fasilitas Kamar</label>
        <div class="facilities-container">
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

            $oldFasilitas = old('fasilitas');
            if (!$oldFasilitas && isset($kamar['fasilitas'])) {
                $oldFasilitas = json_decode($kamar['fasilitas'], true);
            }
            if (!is_array($oldFasilitas)) $oldFasilitas = [];

            $facilityIcons = [
                'Kamar Mandi Dalam' => 'fa-bath',
                'Wi-Fi / Internet' => 'fa-wifi',
                'AC (Air Conditioner)' => 'fa-snowflake',
                'Tempat Parkir' => 'fa-car',
                'Listrik 24 Jam' => 'fa-bolt',
                'Laundry' => 'fa-tshirt',
                'Dapur Bersama' => 'fa-utensils'
            ];

            foreach ($allowedFasilitas as $fasilitas) : 
                $checked = in_array($fasilitas, $oldFasilitas);
            ?>
                <div class="facility-item">
                    <input class="form-check-input <?= (isset(session()->getFlashdata('errors')['fasilitas'])) ? 'is-invalid' : '' ?>" 
                           type="checkbox" 
                           name="fasilitas[]" 
                           id="fasilitas_<?= md5($fasilitas) ?>" 
                           value="<?= $fasilitas ?>" 
                           <?= $checked ? 'checked' : '' ?>>
                    <label class="form-check-label" for="fasilitas_<?= md5($fasilitas) ?>">
                        <i class="fas <?= $facilityIcons[$fasilitas] ?? 'fa-check' ?> me-2"></i>
                        <?= $fasilitas ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="invalid-feedback d-block">
            <?= session()->getFlashdata('errors')['fasilitas'] ?? '' ?>
        </div>
    </div>

    <!-- Status Kamar -->
    <div class="mb-4">
        <label for="status_kamar" class="form-label">Status Kamar <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text bg-light">
                <i class="fas fa-info-circle"></i>
            </span>
            <select name="status_kamar" 
                    id="status_kamar" 
                    class="form-select <?= (isset(session()->getFlashdata('errors')['status_kamar'])) ? 'is-invalid' : '' ?>" 
                    required>
                <?php 
                $statusOld = old('status_kamar', isset($kamar['status_kamar']) ? $kamar['status_kamar'] : 'tersedia');
                $statuses = [
                    'tersedia' => 'Tersedia',
                    'dipesan' => 'Dipesan',
                    'ditempati' => 'Ditempati',
                    'maintenance' => 'Maintenance'
                ];
                foreach ($statuses as $value => $label) : ?>
                    <option value="<?= $value ?>" <?= ($statusOld === $value) ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="invalid-feedback">
            <?= session()->getFlashdata('errors')['status_kamar'] ?? '' ?>
        </div>
    </div>

    <!-- Foto Kamar -->
    <div class="mb-4">
        <label for="foto" class="form-label">Foto Kamar</label>
        <div class="file-upload-container">
            <div class="file-upload-input">
                <input type="file" 
                       name="foto[]" 
                       id="foto" 
                       class="form-control <?= (isset(session()->getFlashdata('errors')['foto'])) ? 'is-invalid' : '' ?>" 
                       multiple
                       accept="image/*">
                <label for="foto" class="upload-label">
                    <i class="fas fa-cloud-upload-alt me-2"></i>
                    <span>Pilih file atau drop di sini</span>
                </label>
            </div>
            <div class="invalid-feedback">
                <?= session()->getFlashdata('errors')['foto'] ?? '' ?>
            </div>
            <small class="text-muted">Format: JPG, PNG (Maksimal 5MB per file)</small>
        </div>
    </div>

    <!-- Existing Photos -->
    <?php if (isset($kamar['foto'])): ?>
        <div class="mb-4">
            <label class="form-label">Foto Saat Ini</label>
            <div class="photo-preview-container">
                <?php 
                $fotos = json_decode($kamar['foto'], true);
                if (is_array($fotos)) : 
                    foreach ($fotos as $foto) : ?>
                        <div class="photo-thumbnail">
                            <img src="<?= base_url('uploads/kamar/' . $foto) ?>" alt="Foto Kamar">
                            <div class="photo-actions">
                                <a href="<?= base_url('uploads/kamar/' . $foto) ?>" target="_blank" class="btn-view">
                                    <i class="fas fa-expand"></i>
                                </a>
                                <button type="button" class="btn-remove" data-photo="<?= $foto ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; 
                endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
// Optional JavaScript for enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // File upload label update
    const fileInput = document.getElementById('foto');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const files = this.files;
            const label = this.nextElementSibling.querySelector('span');
            
            if (files.length > 0) {
                if (files.length === 1) {
                    label.textContent = files[0].name;
                } else {
                    label.textContent = `${files.length} file dipilih`;
                }
            } else {
                label.textContent = 'Pilih file atau drop di sini';
            }
        });
    }
    
    // Photo removal handling (would need backend implementation)
    const removeButtons = document.querySelectorAll('.btn-remove');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const photoName = this.getAttribute('data-photo');
            // Here you would typically make an AJAX call to remove the photo
            console.log('Request to remove photo:', photoName);
            this.closest('.photo-thumbnail').remove();
        });
    });
});
</script>