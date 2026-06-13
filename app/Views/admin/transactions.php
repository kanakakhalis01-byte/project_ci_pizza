<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/admin/dashboard">← Kembali ke Dashboard</a>
            <a href="/admin/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="fw-bold mb-3">Daftar Pesanan Masuk (Transaksi)</h2>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">ID Pesanan</th>
                                <th>Tanggal</th>
                                <th>Pembeli</th>
                                <th>Detail Item</th>
                                <th>Total ($)</th>
                                <th>Bukti Transfer</th> 
                                <th>Status Saat Ini</th>
                                <th class="pe-4">Ubah Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $t): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-danger">#<?= $t['id'] ?></td>
                                    <td><?= $t['transaction_time'] ?></td>
                                    <td class="fw-bold text-primary"><?= $t['username'] ?? 'Guest/User Terhapus' ?></td>
                                    <td>
                                        <ul class="list-unstyled mb-0 small">
                                            <?php foreach ($t['items'] as $item): ?>
                                                <li>- <?= $item['name'] ?> (x<?= $item['quantity'] ?>)</li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                    <td class="fw-bold">$<?= number_format($t['total'], 2) ?></td>
                                    
                                    <td>
                                        <?php if ($t['payment_proof']): ?>
                                            <a href="<?= base_url($t['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-info text-white">Lihat Bukti</a>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Belum Bayar</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= $t['status'] == 'Completed' ? 'success' : ($t['status'] == 'Processing' ? 'primary' : 'warning text-dark') ?>">
                                            <?= $t['status'] ?>
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <form action="/admin/transactions/update-status/<?= $t['id'] ?>" method="POST" class="d-flex">
                                            <select name="status" class="form-select form-select-sm me-2" required>
                                                <option value="Pending" <?= $t['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="Processing" <?= $t['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                                <option value="Completed" <?= $t['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>