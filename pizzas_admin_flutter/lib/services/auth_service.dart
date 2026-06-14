import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../config/api_config.dart';
import '../models/admin_model.dart';
import '../models/dashboard_model.dart';

class AuthService {
  Future<AdminModel?> login(String username, String password) async {
    final response = await http.post(
      Uri.parse('${ApiConfig.apiUrl}/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'username': username, 'password': password}),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      final admin = AdminModel.fromJson(data);
      
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('token', admin.token);
      await prefs.setString('username', admin.username);
      
      return admin;
    } else {
      final error = jsonDecode(response.body)['error'];
      throw Exception(error ?? 'Gagal login');
    }
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
    await prefs.remove('username');
  }

  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  Future<DashboardModel> getDashboard() async {
    final token = await getToken();
    final response = await http.get(
      Uri.parse('${ApiConfig.apiUrl}/dashboard'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      return DashboardModel.fromJson(jsonDecode(response.body)['data']);
    } else {
      throw Exception('Gagal memuat dashboard');
    }
  }
}
