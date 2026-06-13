<?php namespace App\Controllers;
use App\Models\ProductModel;

class Home extends BaseController {
    
    public function index() {
        $productModel = new ProductModel();
        $keyword = $this->request->getGet('keyword');
        
        if ($keyword) {
            $data['products'] = $productModel->like('name', $keyword)
                                             ->orLike('desc', $keyword)
                                             ->orderBy('id', 'DESC')
                                             ->findAll();
        } else {
            $data['products'] = $productModel->orderBy('id', 'DESC')->findAll(); 
        }
        
        $data['keyword'] = $keyword;
        
        return view('home/index', $data);
    }
}