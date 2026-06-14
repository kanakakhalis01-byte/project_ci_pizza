import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:image_picker/image_picker.dart';
import 'dart:io';
import '../models/product_model.dart';
import '../providers/product_provider.dart';

class ProductFormScreen extends StatefulWidget {
  @override
  _ProductFormScreenState createState() => _ProductFormScreenState();
}

class _ProductFormScreenState extends State<ProductFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameCtrl = TextEditingController();
  final _priceCtrl = TextEditingController();
  final _descCtrl = TextEditingController();
  
  ProductModel? _existingProduct;
  XFile? _imageFile;
  final ImagePicker _picker = ImagePicker();
  bool _isSaving = false;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    final args = ModalRoute.of(context)?.settings.arguments;
    if (args != null && args is ProductModel && _existingProduct == null) {
      _existingProduct = args;
      _nameCtrl.text = _existingProduct!.name;
      _priceCtrl.text = _existingProduct!.price.toString();
      _descCtrl.text = _existingProduct!.description;
    }
  }

  Future<void> _pickImage() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      setState(() {
        _imageFile = pickedFile;
      });
    }
  }

  void _saveForm() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSaving = true);
    
    try {
      await Provider.of<ProductProvider>(context, listen: false).saveProduct(
        id: _existingProduct?.id,
        name: _nameCtrl.text,
        price: _priceCtrl.text,
        description: _descCtrl.text,
        imageFile: _imageFile,
      );
      
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Produk berhasil disimpan')));
      Navigator.pop(context);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _isSaving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(_existingProduct == null ? 'Tambah Pizza' : 'Edit Pizza')),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              TextFormField(
                controller: _nameCtrl,
                decoration: InputDecoration(labelText: 'Nama Pizza', border: OutlineInputBorder()),
                validator: (v) => v!.isEmpty ? 'Nama harus diisi' : null,
              ),
              SizedBox(height: 16),
              TextFormField(
                controller: _priceCtrl,
                decoration: InputDecoration(labelText: 'Harga (\$)', border: OutlineInputBorder()),
                keyboardType: TextInputType.numberWithOptions(decimal: true),
                validator: (v) => v!.isEmpty ? 'Harga harus diisi' : null,
              ),
              SizedBox(height: 16),
              TextFormField(
                controller: _descCtrl,
                decoration: InputDecoration(labelText: 'Deskripsi', border: OutlineInputBorder()),
                maxLines: 3,
                validator: (v) => v!.isEmpty ? 'Deskripsi harus diisi' : null,
              ),
              SizedBox(height: 16),
              
              Row(
                children: [
                  ElevatedButton.icon(
                    onPressed: _pickImage,
                    icon: Icon(Icons.image),
                    label: Text('Pilih Gambar'),
                  ),
                  SizedBox(width: 16),
                  Expanded(
                    child: Text(_imageFile != null ? _imageFile!.name : 'Tidak ada gambar baru dipilih'),
                  ),
                ],
              ),
              
              SizedBox(height: 32),
              ElevatedButton(
                onPressed: _isSaving ? null : _saveForm,
                style: ElevatedButton.styleFrom(padding: EdgeInsets.symmetric(vertical: 16)),
                child: _isSaving ? CircularProgressIndicator() : Text('Simpan', style: TextStyle(fontSize: 18)),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
