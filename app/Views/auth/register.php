<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container" style="margin-top: 80px; margin-bottom: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: #ff9f1c;">Register</h2>
                        <p class="text-muted">Join Tasty Pizza today!</p>
                    </div>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="/register" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control form-control-lg" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-lg w-100 text-white fw-bold" style="border-radius: 25px;">Register</button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p>Already have an account? <a href="/login" class="text-decoration-none fw-bold text-danger">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>