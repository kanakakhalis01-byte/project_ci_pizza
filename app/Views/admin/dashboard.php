<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Pizza's Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/admin/dashboard">Pizza's Order - Admin Panel</a>
        <div class="d-flex align-items-center">
            <span class="text-light me-3">Halo, <?= session()->get('admin_username') ?></span>
            <a href="/admin/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="fw-bold mb-4">Dashboard Admin</h2>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow border-0 bg-primary text-white h-100 rounded-4">
                <div class="card-body text-center py-5">
                    <h1 class="display-4">🍕</h1>
                    <h3 class="fw-bold">Kelola Menu Pizza</h3>
                    <p>Tambah, edit, atau hapus daftar menu pizza.</p>
                    <a href="/admin/products" class="btn btn-light fw-bold mt-2 rounded-pill px-4">Buka Menu Produk</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow border-0 bg-warning text-dark h-100 rounded-4">
                <div class="card-body text-center py-5">
                    <h1 class="display-4">🛒</h1>
                    <h3 class="fw-bold">Kelola Pesanan Masuk</h3>
                    <p>Pantau pesanan pelanggan dan ubah status pengiriman.</p>
                    <a href="/admin/transactions" class="btn btn-dark fw-bold mt-2 rounded-pill px-4">Buka Daftar Pesanan</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>