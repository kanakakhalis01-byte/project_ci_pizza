<?php namespace App\Controllers;
use App\Models\ProductModel;

class Cart extends BaseController {
    
    // Menambah produk ke keranjang
    public function add() {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk memesan pizza.');
        }
        $productId = $this->request->getPost('product_id');
        $productModel = new ProductModel();
        $product = $productModel->find($productId);

        if ($product) {
            $cart = session()->get('cart') ?? [];
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity']++;
            } else {
                $cart[$productId] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'img' => $product['img'],
                    'quantity' => 1
                ];
            }
            session()->set('cart', $cart);
        }
        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // Mengupdate jumlah barang
    public function update() {
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');
        $cart = session()->get('cart') ?? [];

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity > 0 ? $quantity : 1;
            session()->set('cart', $cart);
        }
        return redirect()->back();
    }

    // Menghapus barang dari keranjang
    public function remove($productId) {
        $cart = session()->get('cart') ?? [];
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->set('cart', $cart);
        }
        return redirect()->back();
    }
}