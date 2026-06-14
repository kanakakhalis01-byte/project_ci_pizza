class AdminModel {
  final String username;
  final String token;

  AdminModel({required this.username, required this.token});

  factory AdminModel.fromJson(Map<String, dynamic> json) {
    return AdminModel(
      username: json['username'],
      token: json['token'],
    );
  }
}
