<?php namespace App\Controllers;
use App\Models\AdminModel;
use App\Models\ProductModel;
use App\Models\TransactionModel;

class Admin extends BaseController {
    
    // AUTENTIKASI ADMIN 
    public function login() {
        if (session()->get('admin_logged_in')) return redirect()->to('/admin/dashboard');
        return view('admin/login');
    }

 public function processLogin() {
        $model = new \App\Models\AdminModel();
        
        $inputUsername = $this->request->getPost('username');
        $inputPassword = $this->request->getPost('password');
        
        $admin = $model->where('username', $inputUsername)->first();

        if ($admin && password_verify($inputPassword, $admin['password'])) {
            session()->set([
                'admin_id' => $admin['id'], 
                'admin_username' => $admin['username'], 
                'admin_logged_in' => true
            ]);
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau Password Admin salah!');
    }

    public function logout() {
        session()->remove(['admin_id', 'admin_username', 'admin_logged_in']);
        return redirect()->to('/admin/login');
    }

    // DASHBOARD
    public function dashboard() {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');
        return view('admin/dashboard');
    }

    // FITUR CRUD PRODUK 
    public function products() {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');
        $productModel = new ProductModel();
        $data['products'] = $productModel->findAll();
        return view('admin/products', $data);
    }

    // CREATE Produk
    public function productCreate() {
        $productModel = new ProductModel();
        
        // Handle upload gambar
        $file = $this->request->getFile('image');
        $imageName = $file->getRandomName();
        $file->move('img/', $imageName); // Simpan ke public/img/

        $productModel->save([
            'name'  => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'desc'  => $this->request->getPost('desc'),
            'img'   => 'img/' . $imageName
        ]);
        return redirect()->to('/admin/products')->with('success', 'Produk berhasil ditambahkan.');
    }

    // UPDATE Produk
    public function productUpdate($id) {
        $productModel = new ProductModel();
        $data = [
            'name'  => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'desc'  => $this->request->getPost('desc')
        ];

        // Cek jika ada gambar baru yang diupload
        $file = $this->request->getFile('image');
        if ($file->isValid() && !$file->hasMoved()) {
            $imageName = $file->getRandomName();
            $file->move('img/', $imageName);
            $data['img'] = 'img/' . $imageName;
        }

        $productModel->update($id, $data);
        return redirect()->to('/admin/products')->with('success', 'Produk berhasil diupdate.');
    }

    // DELETE Produk
    public function productDelete($id) {
        $productModel = new ProductModel();
        $productModel->delete($id);
        return redirect()->to('/admin/products')->with('success', 'Produk berhasil dihapus.');
    }

    // FITUR KELOLA PESANAN (TRANSAKSI)
    public function transactions() {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');
        
        $db = \Config\Database::connect();
        
        // Ambil semua transaksi beserta nama pembelinya (diurutkan dari yang terbaru)
        $builder = $db->table('transactions');
        $builder->select('transactions.*, users.username');
        $builder->join('users', 'users.id = transactions.user_id', 'left');
        $builder->orderBy('transactions.transaction_time', 'DESC');
        $data['transactions'] = $builder->get()->getResultArray();

        // Ambil detail item pizza untuk setiap transaksi
        foreach($data['transactions'] as &$trans) {
            $trans['items'] = $db->table('transaction_items')
                                 ->select('transaction_items.*, products.name')
                                 ->join('products', 'products.id = transaction_items.product_id')
                                 ->where('transaction_id', $trans['id'])
                                 ->get()->getResultArray();
        }

        return view('admin/transactions', $data);
    }

    // Fungsi untuk mengubah status pesanan
    public function updateTransactionStatus($id) {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');

        $transModel = new TransactionModel();
        $transModel->update($id, [
            'status' => $this->request->getPost('status')
        ]);
        
        return redirect()->to('/admin/transactions')->with('success', 'Status pesanan #' . $id . ' berhasil diperbarui!');
    }
}