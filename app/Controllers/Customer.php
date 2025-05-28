<?php namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\BookingModel;

class Customer extends BaseController
{
    protected $customerModel;
    
    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        helper(['form', 'url']);
    }
    
    // Tampilkan daftar customer
    public function index()
    {
        $data = [
            'title' => 'Daftar Customer',
            'customers' => $this->customerModel->where('deleted_at', null)->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('DaftarCust', $data);
    }
    
    // Form tambah customer
    public function create()
    {
        $data = [
            'title' => 'Tambah Customer',
            'validation' => \Config\Services::validation()
        ];
        return view('customer/tambah', $data);
    }
    
    // Simpan data customer baru
public function store()
{
    $validation = \Config\Services::validation();

    // Cek apakah email atau telepon sudah ada
    $existing = $this->customerModel
                    ->where('email', $this->request->getPost('email'))
                    ->orWhere('telepon', $this->request->getPost('telepon'))
                    ->first();

    if ($existing) {
        return redirect()->back()
            ->withInput()
            ->with('message', 'Data dengan email atau telepon tersebut sudah ada.');
    }

    // Validasi lainnya
    $rules = [
        'nama' => 'required',
        'email' => 'required|valid_email',
        'telepon' => 'required',
        'alamat' => 'required'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $this->customerModel->save([
        'nama'    => $this->request->getPost('nama'),
        'email'   => $this->request->getPost('email'),
        'telepon' => $this->request->getPost('telepon'),
        'alamat'  => $this->request->getPost('alamat')
    ]);

    return redirect()->to('/customer')->with('message', 'Data customer berhasil disimpan.');
}

    
    // Form edit customer
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Customer',
            'customer' => $this->customerModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('customer/edit', $data);
    }
    
    // Update data customer
    public function update($id)
    {
        // Validasi input
        if (!$this->validate([
            'nama' => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[customers.email,id,$id]",
            'telepon' => 'required|numeric',
            'alamat' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Update data
        $this->customerModel->save([
            'id' => $id,
            'nama' => $this->request->getVar('nama'),
            'email' => $this->request->getVar('email'),
            'telepon' => $this->request->getVar('telepon'),
            'alamat' => $this->request->getVar('alamat')
        ]);
        
        return redirect()->to('/customer')->with('message', 'Data customer berhasil diupdate');
    }
    
    // Hapus data customer (soft delete)
    public function delete($id)
    {
        $this->customerModel->delete($id);
        return redirect()->to('/customer')->with('message', 'Data customer berhasil dihapus');
    }

}
