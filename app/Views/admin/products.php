<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/admin/dashboard">Pizza's Order - Admin Panel</a>
        <a href="/admin/logout" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manajemen Produk (CRUD)</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah Pizza</button>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama Pizza</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><img src="<?= base_url(str_replace('public/', '', $p['img'])) ?>" width="80" class="rounded"></td>
                        <td class="fw-bold"><?= $p['name'] ?></td>
                        <td>$<?= number_format($p['price'], 2) ?></td>
                        <td><?= $p['desc'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id'] ?>">Edit</button>
                            <a href="/admin/products/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pizza ini?')">Hapus</a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="/admin/products/update/<?= $p['id'] ?>" method="POST" enctype="multipart/form-data" class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Edit Produk</h5></div>
                                <div class="modal-body">
                                    <div class="mb-2"><label>Nama</label><input type="text" name="name" value="<?= $p['name'] ?>" class="form-control" required></div>
                                    <div class="mb-2"><label>Harga</label><input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" class="form-control" required></div>
                                    <div class="mb-2"><label>Deskripsi</label><textarea name="desc" class="form-control" required><?= $p['desc'] ?></textarea></div>
                                    <div class="mb-2"><label>Ganti Gambar (Opsional)</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                                </div>
                                <div class="modal-footer"><button type="submit" class="btn btn-success">Simpan Perubahan</button></div>
                            </form>
                        </div>
                    </div>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="/admin/products/create" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Tambah Pizza Baru</h5></div>
            <div class="modal-body">
                <div class="mb-2"><label>Nama Pizza</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-2"><label>Harga ($)</label><input type="number" step="0.01" name="price" class="form-control" required></div>
                <div class="mb-2"><label>Deskripsi</label><textarea name="desc" class="form-control" required></textarea></div>
                <div class="mb-2"><label>Upload Gambar</label><input type="file" name="image" class="form-control" accept="image/*" required></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>