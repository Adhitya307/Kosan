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

    // ============================
    // Form Booking Kamar
    // ============================
    public function index($id_kamar)
    {
        $kamar = $this->kamarModel->find($id_kamar);

        if (!$kamar || strtolower($kamar['status_kamar']) !== 'tersedia') {
            return redirect()->back()->with('error', 'Kamar tidak tersedia untuk dipesan.');
        }

        $data = [
            'title' => 'Konfirmasi Booking Kamar',
            'kamar' => $kamar,
            'validation' => \Config\Services::validation()
        ];

        return view('booking/index', $data);
    }

    // ============================
    // Proses Booking
    // ============================
    public function process()
    {
        $rules = [
            'id_kamar' => 'required',
            'nama' => 'required|min_length[3]|max_length[100]',
            'metode_pembayaran' => 'required'
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

        // Simpan booking
        $this->bookingModel->save($data);
        $id_booking = $this->bookingModel->getInsertID();

        // Simpan transaksi awal
        $transaksiModel = new \App\Models\TransaksiModel();
        $transaksiModel->save([
            'id_booking' => $id_booking,
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'status_pembayaran' => 'Menunggu Pembayaran',
            'tanggal_pembayaran' => date('Y-m-d H:i:s')
        ]);

        // Update status kamar jadi "Dipesan"
        $this->kamarModel->update($data['id_kamar'], ['status_kamar' => 'Dipesan']);

        return redirect()->to('/booking/success/' . $id_booking)->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');
    }

    // ============================
    // Halaman Sukses Booking
    // ============================
    public function success($id_booking)
    {
        $booking = $this->bookingModel->find($id_booking);
        if (!$booking) {
            return redirect()->to('/booking/kelolabooking')->with('error', 'Booking tidak ditemukan.');
        }

        $kamar = $this->kamarModel->find($booking['id_kamar']);
        $transaksiModel = new \App\Models\TransaksiModel();
        $transaksi = $transaksiModel->where('id_booking', $id_booking)->first();

        $data = [
            'title' => 'Booking Berhasil!',
            'booking' => $booking,
            'kamar' => $kamar,
            'transaksi' => $transaksi
        ];

        return view('booking/success', $data);
    }

    // ============================
    // Halaman Admin: Kelola Booking
    // ============================
    public function kelolabooking()
    {
        $bookings = $this->bookingModel
            ->select('booking.*, transaksi.bukti_pembayaran, transaksi.status_pembayaran, transaksi.metode_pembayaran')
            ->join('transaksi', 'transaksi.id_booking = booking.id_booking', 'left')
            ->findAll();

        $kamars = $this->kamarModel->findAll();

        $data = [
            'title' => 'Kelola Data Booking',
            'bookings' => $bookings,
            'kamars' => $kamars,
        ];

        return view('booking/kelolabooking', $data);
    }

    // ============================
    // Update Status Booking via AJAX
    // ============================
    public function updateStatus()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/booking');
        }

        $input = $this->request->getJSON(true);
        $id_booking = $input['id_booking'] ?? null;
        $status = strtolower($input['status'] ?? '');

        $validStatus = ['menunggu pembayaran', 'lunas', 'dibatalkan'];
        if (!$id_booking || !in_array($status, $validStatus)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
        }

        $booking = $this->bookingModel->find($id_booking);
        if (!$booking) {
            return $this->response->setJSON(['success' => false, 'message' => 'Booking tidak ditemukan']);
        }

        $this->bookingModel->update($id_booking, ['status' => $status]);

        // Update status kamar sesuai status booking
        $newKamarStatus = ($status === 'lunas') ? 'dipesan' : 'tersedia';

        try {
            $this->kamarModel->update($booking['id_kamar'], ['status_kamar' => $newKamarStatus]);
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Gagal update status kamar: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal update status kamar']);
        }
    }

    // ============================
    // Halaman Customer: Info Booking
    // ============================
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
