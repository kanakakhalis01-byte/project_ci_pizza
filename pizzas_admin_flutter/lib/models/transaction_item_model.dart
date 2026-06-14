class TransactionItemModel {
  final String productName;
  final int quantity;
  final double price;

  TransactionItemModel({
    required this.productName,
    required this.quantity,
    required this.price,
  });

  factory TransactionItemModel.fromJson(Map<String, dynamic> json) {
    return TransactionItemModel(
      productName: json['productName'],
      quantity: json['quantity'],
      price: (json['price'] as num).toDouble(),
    );
  }
}
