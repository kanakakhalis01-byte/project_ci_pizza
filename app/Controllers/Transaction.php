<?php namespace App\Controllers;
use App\Models\TransactionModel;
use App\Models\TransactionItemModel;

class Transaction extends BaseController {
    
    // Proses Checkout
    public function checkout() {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) return redirect()->back();

        $total = 0;
        foreach ($cart as $item) $total += $item['price'] * $item['quantity'];

        $transModel = new TransactionModel();
        $itemModel = new TransactionItemModel();

        // Simpan ke tabel transactions
        $transModel->insert([
            'user_id' => session()->get('user_id'),
            'transaction_time' => date('Y-m-d H:i:s'),
            'total' => $total,
            'status' => 'Pending',
            'payment_proof' => null
        ]);
        $transactionId = $transModel->insertID();

        foreach ($cart as $item) {
            $itemModel->insert([
                'transaction_id' => $transactionId,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        session()->remove('cart');
        
        return redirect()->to('/transactions/pay/' . $transactionId);
    }

    // Tampilkan Halaman Pembayaran
    public function pay($id) {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        $transModel = new TransactionModel();
        $data['transaction'] = $transModel->find($id);
        
        // Cegah akses jika bukan pesanan miliknya
        if(!$data['transaction'] || $data['transaction']['user_id'] != session()->get('user_id')) {
            return redirect()->to('/transactions');
        }
        return view('transactions/pay', $data);
    }

    // Proses Upload Bukti
    public function uploadProof($id) {
        $transModel = new TransactionModel();
        $file = $this->request->getFile('proof');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('img/payments/', $newName); 
            
            $transModel->update($id, [
                'payment_proof' => 'img/payments/' . $newName
            ]);
            return redirect()->to('/transactions')->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu konfirmasi admin.');
        }
        return redirect()->back()->with('error', 'Gagal mengunggah foto!');
    }

    // Riwayat Transaksi User
    public function index() {
        if (!session()->get('logged_in')) return redirect()->to('/login');
        
        $transModel = new TransactionModel();
        $data['transactions'] = $transModel->where('user_id', session()->get('user_id'))->orderBy('transaction_time', 'DESC')->findAll();
        
        $db = \Config\Database::connect();
        foreach($data['transactions'] as &$trans) {
            $trans['items'] = $db->table('transaction_items')
                                 ->select('transaction_items.*, products.name')
                                 ->join('products', 'products.id = transaction_items.product_id')
                                 ->where('transaction_id', $trans['id'])
                                 ->get()->getResultArray();
        }

        return view('transactions/index', $data);
    }
}