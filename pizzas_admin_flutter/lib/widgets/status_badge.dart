import 'package:flutter/material.dart';

class StatusBadge extends StatelessWidget {
  final String status;

  StatusBadge({required this.status});

  @override
  Widget build(BuildContext context) {
    Color bgColor;
    Color textColor = Colors.white;

    switch (status) {
      case 'Completed':
        bgColor = Colors.green;
        break;
      case 'Processing':
        bgColor = Colors.blue;
        break;
      case 'Pending':
      default:
        bgColor = Colors.orange;
        textColor = Colors.black;
        break;
    }

    return Container(
      padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Text(
        status,
        style: TextStyle(color: textColor, fontWeight: FontWeight.bold),
      ),
    );
  }
}
