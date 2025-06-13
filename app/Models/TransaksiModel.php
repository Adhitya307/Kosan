<?php namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_pembayaran';
    protected $allowedFields = [
        'id_booking',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'tanggal_pembayaran'
    ];
    protected $useTimestamps = false; // Pastikan ini false jika tidak ada kolom timestamp
}