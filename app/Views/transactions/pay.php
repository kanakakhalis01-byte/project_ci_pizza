<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5 pt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-center text-danger mb-4">Pembayaran Pesanan #<?= $transaction['id'] ?></h3>
                    
                    <div class="alert alert-warning text-center">
                        Total yang harus dibayar:<br>
                        <h2 class="fw-bold">$<?= number_format($transaction['total'], 2) ?></h2>
                    </div>

                    <div class="text-center mb-4">
                        <p class="text-muted mb-1">Silakan transfer ke rekening berikut:</p>
                        <h4 class="fw-bold">BCA: 123-456-7890</h4>
                        <p class="fw-bold text-muted">a.n. Pizza's Order Official</p>
                    </div>

                    <hr>

                    <form action="/transactions/upload-proof/<?= $transaction['id'] ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Unggah Bukti Transfer (JPG/PNG)</label>
                            <input type="file" name="proof" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 fw-bold">Kirim Bukti Pembayaran</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>