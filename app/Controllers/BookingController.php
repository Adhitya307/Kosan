<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\KamarModel;

class BookingController extends BaseController
{
    protected $bookingModel;
    protected $kamarModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->kamarModel = new KamarModel();
    }

    // Menampilkan form booking
    public function index($id_kamar)
    {
        $kamar = $this->kamarModel->find($id_kamar);

        if (!$kamar || $kamar['status_kamar'] !== 'Tersedia') {
            return redirect()->back()->with('error', 'Kamar tidak tersedia untuk dipesan.');
        }

        $data = [
            'title' => 'Konfirmasi Booking Kamar',
            'kamar' => $kamar,
            'validation' => \Config\Services::validation()
        ];

        return view('booking/index', $data);
    }

    // Proses Booking
    public function process()
    {
        $rules = [
            'id_kamar' => 'required',
            'nama' => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'id_kamar' => $this->request->getPost('id_kamar'),
            'id_customer' => session()->get('user')['id'],
            'nama' => $this->request->getPost('nama'),
            'status' => 'Menunggu Pembayaran',
            'tanggal_booking' => date('Y-m-d H:i:s')
        ];

        $this->bookingModel->save($data);

        // Update status kamar menjadi 'Dipesan'
        $this->kamarModel->update($data['id_kamar'], ['status_kamar' => 'Dipesan']);

        return redirect()->to('/booking/success/' . $this->bookingModel->getInsertID())->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');
    }

    // Halaman sukses booking
    public function success($id_booking)
    {
        $booking = $this->bookingModel->find($id_booking);
        $kamar = $this->kamarModel->find($booking['id_kamar']);

        $data = [
            'title' => 'Instruksi Pembayaran',
            'booking' => $booking,
            'kamar' => $kamar
        ];

        return view('booking/success', $data);
    }

    // Kelola semua booking
    public function kelolabooking()
    {
        $bookings = $this->bookingModel->findAll();
        $kamars = $this->kamarModel->findAll();

        $data = [
            'title' => 'Kelola Data Booking',
            'bookings' => $bookings,
            'kamars' => $kamars,
        ];

        return view('booking/kelolaboking', $data);
    }

public function updateStatus()
{
    if (!$this->request->isAJAX()) {
        return redirect()->to('/booking');
    }

    $input = $this->request->getJSON(true);
    $id_booking = $input['id_booking'] ?? null;
    $status = $input['status'] ?? null;

    // Konversi status ke lowercase untuk match dengan validationRules
    $status = strtolower($status);
    $validStatus = ['menunggu pembayaran', 'lunas', 'dibatalkan'];

    if (!$id_booking || !in_array($status, $validStatus)) {
        return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
    }

    // Ambil data booking
    $booking = $this->bookingModel->find($id_booking);
    if (!$booking) {
        return $this->response->setJSON(['success' => false, 'message' => 'Booking tidak ditemukan']);
    }

    // Update status booking (pastikan lowercase)
    $this->bookingModel->update($id_booking, ['status' => $status]);

    // Tentukan status kamar baru (harus lowercase)
    $newKamarStatus = ($status === 'lunas') ? 'dipesan' : 'tersedia';

    // Update status kamar
    try {
        $this->kamarModel->update($booking['id_kamar'], ['status_kamar' => $newKamarStatus]);
        return $this->response->setJSON(['success' => true]);
    } catch (\Exception $e) {
        log_message('error', 'Gagal update status kamar: ' . $e->getMessage());
        return $this->response->setJSON(['success' => false, 'message' => 'Gagal update status kamar']);
    }
}
    // Informasi booking untuk pelanggan
    public function informasiBooking()
    {
        $bookings = $this->bookingModel
            ->select('booking.*, kamar.nomor_kamar, kamar.harga_kamar')
            ->join('kamar', 'kamar.id_kamar = booking.id_kamar')
            ->orderBy('booking.tanggal_booking', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Informasi Booking',
            'bookings' => $bookings
        ];

        return view('booking/informasi_booking', $data);
    }
}
