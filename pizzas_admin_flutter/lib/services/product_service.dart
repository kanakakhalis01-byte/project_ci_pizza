import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import '../config/api_config.dart';
import '../models/product_model.dart';
import 'auth_service.dart';

class ProductService {
  final AuthService _authService = AuthService();

  Future<List<ProductModel>> getProducts() async {
    final token = await _authService.getToken();
    final response = await http.get(
      Uri.parse('${ApiConfig.apiUrl}/products'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      final List data = jsonDecode(response.body)['data'];
      return data.map((json) => ProductModel.fromJson(json)).toList();
    } else {
      throw Exception('Gagal memuat produk');
    }
  }

  Future<ProductModel> saveProduct({
    int? id,
    required String name,
    required String price,
    required String description,
    XFile? imageFile,
  }) async {
    final token = await _authService.getToken();
    var uri = Uri.parse('${ApiConfig.apiUrl}/products${id != null ? '/$id' : ''}');
    
    var request = http.MultipartRequest(id != null ? 'PUT' : 'POST', uri);
    request.headers['Authorization'] = 'Bearer $token';
    request.fields['name'] = name;
    request.fields['price'] = price;
    request.fields['desc'] = description;

    if (imageFile != null) {
      request.files.add(await http.MultipartFile.fromPath('image', imageFile.path));
    }

    var streamedResponse = await request.send();
    var response = await http.Response.fromStream(streamedResponse);

    if (response.statusCode == 200 || response.statusCode == 201) {
      return ProductModel.fromJson(jsonDecode(response.body)['data']);
    } else {
      throw Exception('Gagal menyimpan produk: ${jsonDecode(response.body)['error']}');
    }
  }

  Future<void> deleteProduct(int id) async {
    final token = await _authService.getToken();
    final response = await http.delete(
      Uri.parse('${ApiConfig.apiUrl}/products/$id'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode != 200) {
      throw Exception('Gagal menghapus produk');
    }
  }
}
