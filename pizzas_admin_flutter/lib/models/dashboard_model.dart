class DashboardModel {
  final int totalProducts;
  final int totalTransactions;
  final int pendingOrders;
  final int processingOrders;
  final int completedOrders;

  DashboardModel({
    required this.totalProducts,
    required this.totalTransactions,
    required this.pendingOrders,
    required this.processingOrders,
    required this.completedOrders,
  });

  factory DashboardModel.fromJson(Map<String, dynamic> json) {
    return DashboardModel(
      totalProducts: json['totalProducts'],
      totalTransactions: json['totalTransactions'],
      pendingOrders: json['pendingOrders'],
      processingOrders: json['processingOrders'],
      completedOrders: json['completedOrders'],
    );
  }
}
