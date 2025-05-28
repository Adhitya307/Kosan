<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarModel extends Model
{
    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';
    protected $allowedFields = [
        'nomor_kamar', 'harga_kamar', 'fasilitas', 'status_kamar', 'foto'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id_kamar' => 'permit_empty|integer',
        'nomor_kamar' => 'required', // is_unique di-handle di controller untuk update
        'harga_kamar' => 'required|numeric|greater_than[0]',
        'status_kamar' => 'required|in_list[tersedia,dipesan,ditempati,maintenance]',
        'foto' => 'permit_empty'
    ];

    protected $validationMessages = [
        'nomor_kamar' => [
            'required' => 'Nomor kamar wajib diisi.',
            // 'is_unique' => 'Nomor kamar sudah terdaftar.'  <-- pindahkan ke controller
        ],
        'harga_kamar' => [
            'required' => 'Harga kamar wajib diisi.',
            'numeric' => 'Harga harus berupa angka.',
            'greater_than' => 'Harga harus lebih dari 0.'
        ],
        'fasilitas' => [
            'in_list' => 'Fasilitas tidak valid.'
        ],
        'status_kamar' => [
            'required' => 'Status kamar harus dipilih.',
            'in_list' => 'Status tidak valid.'
        ],
        'foto' => [
            'is_image' => 'File harus berupa gambar.',
            'mime_in' => 'Tipe gambar tidak diperbolehkan.',
            'max_size' => 'Ukuran gambar maksimal 2MB.'
        ]
    ];
}
