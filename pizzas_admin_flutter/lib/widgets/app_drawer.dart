import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class AppDrawer extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final username = Provider.of<AuthProvider>(context).username ?? 'Admin';
    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          DrawerHeader(
            decoration: BoxDecoration(color: Colors.blue),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                Icon(Icons.admin_panel_settings, color: Colors.white, size: 48),
                SizedBox(height: 8),
                Text('Pizza\'s Order Admin', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                Text(username, style: TextStyle(color: Colors.white70)),
              ],
            ),
          ),
          ListTile(
            leading: Icon(Icons.dashboard),
            title: Text('Dashboard'),
            onTap: () => Navigator.pushReplacementNamed(context, '/dashboard'),
          ),
          ListTile(
            leading: Icon(Icons.local_pizza),
            title: Text('Kelola Produk'),
            onTap: () => Navigator.pushReplacementNamed(context, '/products'),
          ),
          ListTile(
            leading: Icon(Icons.shopping_cart),
            title: Text('Kelola Pesanan'),
            onTap: () => Navigator.pushReplacementNamed(context, '/transactions'),
          ),
          Divider(),
          ListTile(
            leading: Icon(Icons.logout, color: Colors.red),
            title: Text('Logout', style: TextStyle(color: Colors.red)),
            onTap: () async {
              await Provider.of<AuthProvider>(context, listen: false).logout();
              Navigator.pushReplacementNamed(context, '/login');
            },
          ),
        ],
      ),
    );
  }
}
