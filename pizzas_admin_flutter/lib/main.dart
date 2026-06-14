import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/auth_provider.dart';
import 'providers/product_provider.dart';
import 'providers/transaction_provider.dart';
import 'screens/login_screen.dart';
import 'screens/dashboard_screen.dart';
import 'screens/products_screen.dart';
import 'screens/product_form_screen.dart';
import 'screens/transactions_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  final authProvider = AuthProvider();
  await authProvider.checkAuthStatus();

  runApp(MyApp(authProvider: authProvider));
}

class MyApp extends StatelessWidget {
  final AuthProvider authProvider;

  MyApp({required this.authProvider});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider.value(value: authProvider),
        ChangeNotifierProvider(create: (_) => ProductProvider()),
        ChangeNotifierProvider(create: (_) => TransactionProvider()),
      ],
      child: Consumer<AuthProvider>(
        builder: (context, auth, _) {
          return MaterialApp(
            title: "Pizza's Order Admin",
            debugShowCheckedModeBanner: false,
            theme: ThemeData(
              primarySwatch: Colors.blue,
              scaffoldBackgroundColor: Colors.grey[100],
              appBarTheme: AppBarTheme(
                backgroundColor: Colors.blue[900],
                foregroundColor: Colors.white,
                elevation: 0,
              ),
            ),
            home: auth.isAuthenticated ? DashboardScreen() : LoginScreen(),
            routes: {
              '/login': (context) => LoginScreen(),
              '/dashboard': (context) => DashboardScreen(),
              '/products': (context) => ProductsScreen(),
              '/product-form': (context) => ProductFormScreen(),
              '/transactions': (context) => TransactionsScreen(),
            },
          );
        },
      ),
    );
  }
}
