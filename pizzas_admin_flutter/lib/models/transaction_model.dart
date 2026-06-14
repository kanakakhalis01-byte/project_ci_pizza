import 'transaction_item_model.dart';

class TransactionModel {
  final int id;
  final String transactionTime;
  final String username;
  final double total;
  final String status;
  final String? paymentProofUrl;
  final List<TransactionItemModel> items;

  TransactionModel({
    required this.id,
    required this.transactionTime,
    required this.username,
    required this.total,
    required this.status,
    this.paymentProofUrl,
    required this.items,
  });

  factory TransactionModel.fromJson(Map<String, dynamic> json) {
    var itemsList = json['items'] as List;
    List<TransactionItemModel> parsedItems = itemsList.map((i) => TransactionItemModel.fromJson(i)).toList();

    return TransactionModel(
      id: json['id'],
      transactionTime: json['transactionTime'],
      username: json['username'],
      total: (json['total'] as num).toDouble(),
      status: json['status'],
      paymentProofUrl: json['paymentProofUrl'],
      items: parsedItems,
    );
  }
}
