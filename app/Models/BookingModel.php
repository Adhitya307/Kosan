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
        'tanggal_booking'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}