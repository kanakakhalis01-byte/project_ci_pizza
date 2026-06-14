import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../providers/transaction_provider.dart';
import '../widgets/app_drawer.dart';
import '../widgets/status_badge.dart';

class TransactionsScreen extends StatefulWidget {
  @override
  _TransactionsScreenState createState() => _TransactionsScreenState();
}

class _TransactionsScreenState extends State<TransactionsScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() =>
        Provider.of<TransactionProvider>(context, listen: false).loadTransactions());
  }

  void _updateStatus(int id, String newStatus) async {
    try {
      await Provider.of<TransactionProvider>(context, listen: false).updateStatus(id, newStatus);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Status berhasil diperbarui')));
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    }
  }

  void _openPaymentProof(String url) async {
    final uri = Uri.parse(url);
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Tidak dapat membuka link bukti pembayaran')));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Daftar Pesanan Masuk')),
      drawer: AppDrawer(),
      body: Consumer<TransactionProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading) return Center(child: CircularProgressIndicator());
          
          if (provider.transactions.isEmpty) {
            return Center(child: Text('Belum ada pesanan masuk.'));
          }

          return ListView.builder(
            padding: EdgeInsets.all(16),
            itemCount: provider.transactions.length,
            itemBuilder: (context, index) {
              final t = provider.transactions[index];
              return Card(
                margin: EdgeInsets.only(bottom: 16),
                child: Padding(
                  padding: EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text('Order #${t.id}', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: Colors.red)),
                          StatusBadge(status: t.status),
                        ],
                      ),
                      Divider(),
                      Text('Waktu: ${t.transactionTime}'),
                      Text('Pembeli: ${t.username}', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.blue)),
                      SizedBox(height: 8),
                      Text('Item:', style: TextStyle(fontWeight: FontWeight.bold)),
                      ...t.items.map((item) => Text('- ${item.productName} (x${item.quantity})')),
                      SizedBox(height: 8),
                      Text('Total: \$${t.total.toStringAsFixed(2)}', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                      SizedBox(height: 16),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          if (t.paymentProofUrl != null)
                            ElevatedButton.icon(
                              onPressed: () => _openPaymentProof(t.paymentProofUrl!),
                              icon: Icon(Icons.receipt),
                              label: Text('Lihat Bukti'),
                              style: ElevatedButton.styleFrom(backgroundColor: Colors.info),
                            )
                          else
                            Text('Belum Bayar', style: TextStyle(color: Colors.grey)),
                            
                          DropdownButton<String>(
                            value: t.status,
                            items: ['Pending', 'Processing', 'Completed']
                                .map((s) => DropdownMenuItem(value: s, child: Text(s)))
                                .toList(),
                            onChanged: (newStatus) {
                              if (newStatus != null && newStatus != t.status) {
                                _updateStatus(t.id, newStatus);
                              }
                            },
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
