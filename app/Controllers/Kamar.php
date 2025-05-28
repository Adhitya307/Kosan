<?php
namespace App\Controllers;

use App\Models\KamarModel;

class Kamar extends BaseController
{
    protected $kamarModel;

    public function __construct()
    {
        $this->kamarModel = new KamarModel();
    }

    public function index()
    {
        return view('kamar/indexkamar', [
            'title' => 'Data Kamar',
            'kamar' => $this->kamarModel->findAll()
        ]);
    }

    public function create()
    {
        return view('kamar/createkamar', [
            'title' => 'Tambah Kamar',
            'validation' => \Config\Services::validation()
        ]);
    }

public function store()
{
    // Validasi, tambahkan validasi foto.* untuk multiple
    $validationRules = $this->kamarModel->getValidationRules();
    $validationRules['foto.*'] = 'is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]';

    if (!$this->validate($validationRules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $files = $this->request->getFiles();
    $fotoNames = [];

    if (!empty($files['foto'])) {
        foreach ($files['foto'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/kamar';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $fotoNames[] = $newName;
            }
        }
    }

    $fasilitasPost = $this->request->getPost('fasilitas');
    $fasilitas = is_array($fasilitasPost) ? json_encode($fasilitasPost) : json_encode([]);

    $data = [
        'nomor_kamar' => $this->request->getPost('nomor_kamar'),
        'harga_kamar' => $this->request->getPost('harga_kamar'),
        'fasilitas' => $fasilitas,
        'status_kamar' => $this->request->getPost('status_kamar'),
        'foto' => json_encode($fotoNames)
    ];

    if (!$this->kamarModel->save($data)) {
        return redirect()->back()->withInput()->with('errors', $this->kamarModel->errors());
    }

    return redirect()->to('/kamar')->with('message', 'Data kamar berhasil ditambahkan.');
}

    public function edit($id)
    {
        $kamar = $this->kamarModel->find($id);
        if (!$kamar) {
            return redirect()->to('/kamar')->with('message', 'Data tidak ditemukan.');
        }

        return view('kamar/editkamar', [
            'title' => 'Edit Kamar',
            'kamar' => $kamar,
            'validation' => \Config\Services::validation()
        ]);
    }

public function update($id)
{
    $oldData = $this->kamarModel->find($id);
    if (!$oldData) {
        return redirect()->to('/kamar')->with('message', 'Data tidak ditemukan.');
    }

    $rules = $this->kamarModel->getValidationRules();
    $rules['nomor_kamar'] = 'required|is_unique[kamar.nomor_kamar,id_kamar,' . $id . ']';
    $rules['foto.*'] = 'is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]';

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $files = $this->request->getFiles();

    // Ambil foto lama dalam array
    $oldFotos = json_decode($oldData['foto'], true);
    if (!is_array($oldFotos)) {
        $oldFotos = [];
    }

    $newFotoNames = $oldFotos;

    if (!empty($files['foto'])) {
        foreach ($files['foto'] as $file) {
            if ($file->getError() !== UPLOAD_ERR_NO_FILE) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $uploadPath = FCPATH . 'uploads/kamar';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $newFotoNames[] = $newName;
                }
            }
        }
    }

    // Jika ingin hapus foto lama, kamu bisa buat fitur khusus, karena ini hanya menambah foto baru

    $fasilitasPost = $this->request->getPost('fasilitas');
    $fasilitas = is_array($fasilitasPost) ? json_encode($fasilitasPost) : json_encode([]);

    $data = [
        'id_kamar' => $id,
        'nomor_kamar' => $this->request->getPost('nomor_kamar'),
        'harga_kamar' => $this->request->getPost('harga_kamar'),
        'fasilitas' => $fasilitas,
        'status_kamar' => $this->request->getPost('status_kamar'),
        'foto' => json_encode($newFotoNames)
    ];

    if (!$this->kamarModel->save($data)) {
        return redirect()->back()->withInput()->with('errors', $this->kamarModel->errors());
    }

    return redirect()->to('/kamar')->with('message', 'Data kamar berhasil diperbarui.');
}

public function delete($id)
{
    $kamar = $this->kamarModel->find($id);
    if ($kamar) {
        $fotos = json_decode($kamar['foto'], true);
        if (is_array($fotos)) {
            foreach ($fotos as $foto) {
                $filePath = FCPATH . 'uploads/kamar/' . $foto;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
        $this->kamarModel->delete($id);
    }
    return redirect()->to('/kamar')->with('message', 'Data kamar berhasil dihapus.');
}

}
