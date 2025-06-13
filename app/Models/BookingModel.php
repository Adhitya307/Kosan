<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'id_booking';
    protected $allowedFields = [
        'id_kamar', 
        'id_customer', 
        'nama',
        'status', 
        'tanggal_booking',
        'bukti_pembayaran'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getBookingWithTransaction()
{
    return $this->db->table('booking')
        ->select('booking.*, transaksi.metode_pembayaran')
        ->join('transaksi', 'transaksi.id_booking = booking.id_booking', 'left')
        ->where('booking.id_user', session()->get('id_user'))
        ->get()->getResultArray();
}

}