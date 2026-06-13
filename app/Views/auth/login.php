<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: #e71d36;">Login</h2>
                        <p class="text-muted">Welcome back to Tasty Pizza!</p>
                    </div>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger text-center">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="/login" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Enter your username" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Enter your password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-danger btn-lg w-100" style="background-color: #e71d36; border-radius: 25px;">
                            Login
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">Don't have an account? <a href="/register" class="text-decoration-none fw-bold" style="color: #ff9f1c;">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>