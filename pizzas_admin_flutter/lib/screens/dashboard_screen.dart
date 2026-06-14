import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../services/auth_service.dart';
import '../models/dashboard_model.dart';
import '../widgets/app_drawer.dart';

class DashboardScreen extends StatefulWidget {
  @override
  _DashboardScreenState createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  final AuthService _authService = AuthService();
  DashboardModel? _dashboard;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadDashboard();
  }

  void _loadDashboard() async {
    try {
      final dashboard = await _authService.getDashboard();
      setState(() {
        _dashboard = dashboard;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    }
  }

  @override
  Widget build(BuildContext context) {
    final username = Provider.of<AuthProvider>(context).username ?? 'Admin';
    return Scaffold(
      appBar: AppBar(title: Text('Dashboard - Halo, $username')),
      drawer: AppDrawer(),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: GridView.count(
                crossAxisCount: MediaQuery.of(context).size.width > 600 ? 2 : 1,
                crossAxisSpacing: 16,
                mainAxisSpacing: 16,
                childAspectRatio: 2,
                children: [
                  _buildCard(
                    context,
                    title: 'Kelola Menu Pizza',
                    icon: '🍕',
                    color: Colors.blue,
                    subtitle: '${_dashboard?.totalProducts ?? 0} Produk',
                    route: '/products',
                  ),
                  _buildCard(
                    context,
                    title: 'Kelola Pesanan Masuk',
                    icon: '🛒',
                    color: Colors.orange,
                    subtitle: '${_dashboard?.pendingOrders ?? 0} Pending / ${_dashboard?.totalTransactions ?? 0} Total',
                    route: '/transactions',
                  ),
                ],
              ),
            ),
    );
  }

  Widget _buildCard(BuildContext context, {required String title, required String icon, required Color color, required String subtitle, required String route}) {
    return Card(
      color: color,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: InkWell(
        onTap: () => Navigator.pushNamed(context, route),
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(icon, style: TextStyle(fontSize: 48)),
              SizedBox(height: 8),
              Text(title, style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
              SizedBox(height: 4),
              Text(subtitle, style: TextStyle(color: Colors.white70, fontSize: 16)),
            ],
          ),
        ),
      ),
    );
  }
}
