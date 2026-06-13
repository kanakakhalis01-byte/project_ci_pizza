<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5 pt-4 mb-5">
    <h2 class="fw-bold mb-4">Riwayat Transaksi</h2>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (empty($transactions)): ?>
        <div class="alert alert-warning text-center">Anda belum memiliki riwayat transaksi.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($transactions as $t): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                            <h5 class="fw-bold text-danger">Order #<?= $t['id']; ?></h5>
                            <span class="badge bg-<?= $t['status'] == 'Pending' ? 'warning text-dark' : 'success'; ?>"><?= $t['status']; ?></span>
                        </div>
                        <p class="text-muted small mb-3">Tanggal: <?= $t['transaction_time']; ?></p>
                        
                        <h6 class="fw-bold mb-2">Items:</h6>
                        <ul class="list-unstyled mb-3">
                            <?php foreach ($t['items'] as $item): ?>
                                <li>✔️ <?= $item['name']; ?> <span class="text-muted">(x<?= $item['quantity']; ?>)</span></li>
                            <?php endforeach; ?>
                        </ul>
                        <h5 class="fw-bold text-end">Total: $<?= number_format($t['total'], 2); ?></h5>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>