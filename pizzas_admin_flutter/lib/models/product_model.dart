class ProductModel {
  final int id;
  final String name;
  final double price;
  final String description;
  final String? imageUrl;

  ProductModel({
    required this.id,
    required this.name,
    required this.price,
    required this.description,
    this.imageUrl,
  });

  factory ProductModel.fromJson(Map<String, dynamic> json) {
    return ProductModel(
      id: json['id'],
      name: json['name'],
      price: (json['price'] as num).toDouble(),
      description: json['description'],
      imageUrl: json['imageUrl'],
    );
  }
}
