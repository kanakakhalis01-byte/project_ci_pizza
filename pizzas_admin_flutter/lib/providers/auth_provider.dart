import 'package:flutter/material.dart';
import '../services/auth_service.dart';

class AuthProvider with ChangeNotifier {
  final AuthService _authService = AuthService();
  bool _isAuthenticated = false;
  String? _username;
  bool _isLoading = false;

  bool get isAuthenticated => _isAuthenticated;
  String? get username => _username;
  bool get isLoading => _isLoading;

  Future<void> checkAuthStatus() async {
    final token = await _authService.getToken();
    if (token != null) {
      _isAuthenticated = true;
      // You can also get username from SharedPreferences here if needed
    }
    notifyListeners();
  }

  Future<void> login(String username, String password) async {
    _isLoading = true;
    notifyListeners();

    try {
      final admin = await _authService.login(username, password);
      _isAuthenticated = true;
      _username = admin!.username;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> logout() async {
    await _authService.logout();
    _isAuthenticated = false;
    _username = null;
    notifyListeners();
  }
}
