import 'dart:convert';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';
import '../models/transaction_model.dart';
import 'auth_service.dart';

class TransactionService {
  final AuthService _authService = AuthService();

  Future<List<TransactionModel>> getTransactions() async {
    final token = await _authService.getToken();
    final response = await http.get(
      Uri.parse('${ApiConfig.apiUrl}/transactions'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      final List data = jsonDecode(response.body)['data'];
      return data.map((json) => TransactionModel.fromJson(json)).toList();
    } else {
      throw Exception('Gagal memuat transaksi');
    }
  }

  Future<TransactionModel> updateStatus(int id, String status) async {
    final token = await _authService.getToken();
    final response = await http.put(
      Uri.parse('${ApiConfig.apiUrl}/transactions/$id/status'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({'status': status}),
    );

    if (response.statusCode == 200) {
      return TransactionModel.fromJson(jsonDecode(response.body)['data']);
    } else {
      throw Exception('Gagal mengupdate status transaksi');
    }
  }
}
