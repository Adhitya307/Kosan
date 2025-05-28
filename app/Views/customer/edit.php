<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-4">
        <h1><?= esc($title) ?></h1>
        
        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="/customer/update/<?= esc($customer['id']) ?>" method="post">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="nama" 
                    name="nama" 
                    value="<?= esc(old('nama', $customer['nama'])) ?>" 
                    required
                >
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    name="email" 
                    value="<?= esc(old('email', $customer['email'])) ?>" 
                    required
                >
            </div>
            <div class="mb-3">
                <label for="telepon" class="form-label">Telepon</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="telepon" 
                    name="telepon" 
                    value="<?= esc(old('telepon', $customer['telepon'])) ?>" 
                    required
                >
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea 
                    class="form-control" 
                    id="alamat" 
                    name="alamat" 
                    rows="3" 
                    required
                ><?= esc(old('alamat', $customer['alamat'])) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/customer" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
