<?php namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\BookingModel;
use App\Models\KamarModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class TransaksiController extends Controller
{
    protected $transaksiModel;
    protected $bookingModel;
    protected $kamarModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->bookingModel = new BookingModel();
        $this->kamarModel = new KamarModel();
    }

    public function create($id_booking)
    {
        $booking = $this->bookingModel->find($id_booking);
        if (!$booking) {
            return redirect()->to('/booking/kelolaboking')->with('error', 'Booking tidak ditemukan.');
        }

        $transaksi = $this->transaksiModel->where('id_booking', $id_booking)->first();
        $kamar = $this->kamarModel->find($booking['id_kamar']);

        return view('transaksi/create', [
            'booking'   => $booking,
            'transaksi' => $transaksi,
            'kamar'     => $kamar
        ]);
    }

    public function process()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'id_kamar'           => 'required|integer',
            'nama'               => 'required|string',
            'metode_pembayaran'  => 'required|string',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $bookingData = [
            'id_kamar'         => $this->request->getPost('id_kamar'),
            'nama_pemesan'     => $this->request->getPost('nama'),
            'tanggal_booking'  => date('Y-m-d'),
            'status'           => 'Menunggu Pembayaran',
        ];

        $this->bookingModel->insert($bookingData);
        $idBooking = $this->bookingModel->getInsertID();

        $this->transaksiModel->insert([
            'id_booking'         => $idBooking,
            'metode_pembayaran'  => $this->request->getPost('metode_pembayaran'),
            'status_pembayaran'  => 'menunggu',
            'tanggal_pembayaran' => date('Y-m-d'),
        ]);

        return redirect()->to('/transaksi/create/' . $idBooking)
                         ->with('message', 'Booking berhasil, silakan upload bukti pembayaran.');
    }

    public function store()
    {
        $idBooking = $this->request->getPost('id_booking');
        $metode    = $this->request->getPost('metode_pembayaran');

        $rules = [
            'id_booking' => 'required|integer',
        ];

        if ($metode !== 'cod') {
            $rules['bukti_pembayaran'] = 'uploaded[bukti_pembayaran]|max_size[bukti_pembayaran,2048]|mime_in[bukti_pembayaran,image/jpg,image/jpeg,image/png,application/pdf]';
        }

        if (!$this->validate($rules)) {
            return redirect()->to("/transaksi/create/$idBooking")
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_booking'         => $idBooking,
            'status_pembayaran'  => 'menunggu',
            'metode_pembayaran'  => $metode,
            'tanggal_pembayaran' => Time::now()->toDateTimeString(),
        ];

        if ($metode !== 'cod') {
            $file = $this->request->getFile('bukti_pembayaran');
            if ($file && $file->isValid()) {
                $namaFile   = $file->getRandomName();
                $uploadPath = FCPATH . 'uploads/bukti/';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $file->move($uploadPath, $namaFile);
                $data['bukti_pembayaran'] = $namaFile;
            }
        }

        $existing = $this->transaksiModel->where('id_booking', $idBooking)->first();

        if ($existing) {
            $this->transaksiModel->update($existing['id_pembayaran'], $data);
        } else {
            $this->transaksiModel->insert($data);
        }

        $this->bookingModel->update($idBooking, ['status' => 'Menunggu Pembayaran']);

        return redirect()->to("/transaksi/create/$idBooking")
                         ->with('success', 'Bukti pembayaran berhasil diupload!');
    }

    public function upload_bukti()
    {
        $db = \Config\Database::connect();

        $rules = [
            'id_booking' => 'required|integer',
            'bukti_pembayaran' => [
                'uploaded[bukti_pembayaran]',
                'mime_in[bukti_pembayaran,image/jpg,image/jpeg,image/png,application/pdf]',
                'max_size[bukti_pembayaran,2048]',
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bookingId = $this->request->getPost('id_booking');
        $file      = $this->request->getFile('bukti_pembayaran');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File upload tidak valid: ' . $file->getErrorString());
        }

        $newName    = $file->getRandomName();
        $uploadPath = FCPATH . 'uploads/bukti_pembayaran/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $db->transBegin();

        try {
            if (!$file->move($uploadPath, $newName)) {
                throw new \RuntimeException($file->getErrorString());
            }

            $transaksiData = [
                'id_booking'         => $bookingId,
                'bukti_pembayaran'   => $newName,
                'status_pembayaran'  => 'menunggu',
                'tanggal_pembayaran' => Time::now()->toDateTimeString(),
            ];

            $existing = $this->transaksiModel->where('id_booking', $bookingId)->first();

            if ($existing) {
                $oldFile = $existing['bukti_pembayaran'] ?? null;
                if ($oldFile && file_exists($uploadPath . $oldFile)) {
                    unlink($uploadPath . $oldFile);
                }

                $this->transaksiModel->update($existing['id_pembayaran'], $transaksiData);
            } else {
                $this->transaksiModel->insert($transaksiData);
            }

            $this->bookingModel->update($bookingId, ['status' => 'Menunggu Pembayaran']);

            $db->transCommit();

            return redirect()->to('/transaksi/create/' . $bookingId)->with('success', 'Bukti pembayaran berhasil diupload.');
        } catch (\Exception $e) {
            $db->transRollback();

            if (file_exists($uploadPath . $newName)) {
                @unlink($uploadPath . $newName);
            }

            return redirect()->to('/transaksi/create/' . $bookingId)->with('error', 'Upload gagal: ' . $e->getMessage());
        }
    }
    
}