import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../providers/product_provider.dart';
import '../widgets/app_drawer.dart';

class ProductsScreen extends StatefulWidget {
  @override
  _ProductsScreenState createState() => _ProductsScreenState();
}

class _ProductsScreenState extends State<ProductsScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() =>
        Provider.of<ProductProvider>(context, listen: false).loadProducts());
  }

  void _deleteProduct(int id) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (c) => AlertDialog(
        title: Text('Hapus Produk'),
        content: Text('Apakah Anda yakin ingin menghapus produk ini?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(c, false), child: Text('Batal')),
          TextButton(onPressed: () => Navigator.pop(c, true), child: Text('Hapus', style: TextStyle(color: Colors.red))),
        ],
      ),
    );

    if (confirm == true) {
      try {
        await Provider.of<ProductProvider>(context, listen: false).deleteProduct(id);
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Produk berhasil dihapus')));
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Kelola Produk'),
        actions: [
          IconButton(
            icon: Icon(Icons.add),
            onPressed: () => Navigator.pushNamed(context, '/product-form'),
          ),
        ],
      ),
      drawer: AppDrawer(),
      body: Consumer<ProductProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading) return Center(child: CircularProgressIndicator());
          
          return ListView.builder(
            padding: EdgeInsets.all(16),
            itemCount: provider.products.length,
            itemBuilder: (context, index) {
              final product = provider.products[index];
              return Card(
                margin: EdgeInsets.only(bottom: 16),
                child: ListTile(
                  leading: product.imageUrl != null
                      ? CachedNetworkImage(
                          imageUrl: product.imageUrl!,
                          width: 50,
                          height: 50,
                          fit: BoxFit.cover,
                          errorWidget: (c, u, e) => Icon(Icons.error),
                        )
                      : Icon(Icons.image, size: 50),
                  title: Text(product.name, style: TextStyle(fontWeight: FontWeight.bold)),
                  subtitle: Text('\$${product.price.toStringAsFixed(2)}\n${product.description}'),
                  isThreeLine: true,
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      IconButton(
                        icon: Icon(Icons.edit, color: Colors.orange),
                        onPressed: () => Navigator.pushNamed(context, '/product-form', arguments: product),
                      ),
                      IconButton(
                        icon: Icon(Icons.delete, color: Colors.red),
                        onPressed: () => _deleteProduct(product.id),
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => Navigator.pushNamed(context, '/product-form'),
        child: Icon(Icons.add),
      ),
    );
  }
}
