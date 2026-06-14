import 'package:flutter/material.dart';
import '../models/transaction_model.dart';
import '../services/transaction_service.dart';

class TransactionProvider with ChangeNotifier {
  final TransactionService _transactionService = TransactionService();
  List<TransactionModel> _transactions = [];
  bool _isLoading = false;

  List<TransactionModel> get transactions => _transactions;
  bool get isLoading => _isLoading;

  Future<void> loadTransactions() async {
    _isLoading = true;
    notifyListeners();

    try {
      _transactions = await _transactionService.getTransactions();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> updateStatus(int id, String status) async {
    await _transactionService.updateStatus(id, status);
    
    // Update local state without full reload
    final index = _transactions.indexWhere((t) => t.id == id);
    if (index != -1) {
      final updatedT = _transactions[index];
      _transactions[index] = TransactionModel(
        id: updatedT.id,
        transactionTime: updatedT.transactionTime,
        username: updatedT.username,
        total: updatedT.total,
        status: status,
        paymentProofUrl: updatedT.paymentProofUrl,
        items: updatedT.items,
      );
      notifyListeners();
    }
  }
}
