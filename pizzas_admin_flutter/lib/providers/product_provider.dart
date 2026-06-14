import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import '../models/product_model.dart';
import '../services/product_service.dart';

class ProductProvider with ChangeNotifier {
  final ProductService _productService = ProductService();
  List<ProductModel> _products = [];
  bool _isLoading = false;

  List<ProductModel> get products => _products;
  bool get isLoading => _isLoading;

  Future<void> loadProducts() async {
    _isLoading = true;
    notifyListeners();

    try {
      _products = await _productService.getProducts();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> saveProduct({
    int? id,
    required String name,
    required String price,
    required String description,
    XFile? imageFile,
  }) async {
    await _productService.saveProduct(
      id: id,
      name: name,
      price: price,
      description: description,
      imageFile: imageFile,
    );
    await loadProducts(); // Reload after save
  }

  Future<void> deleteProduct(int id) async {
    await _productService.deleteProduct(id);
    _products.removeWhere((p) => p.id == id);
    notifyListeners();
  }
}
