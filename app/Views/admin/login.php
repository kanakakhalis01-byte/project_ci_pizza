<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Pizza's Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #212529; }
        .card { border-radius: 15px; border: none; }
        .btn-admin { background-color: #0d6efd; color: white; border-radius: 25px; }
    </style>
</head>
<body>

<div class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">Admin Login</h3>
                        <p class="text-muted small">Pizza's Order Management</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger py-2 small text-center">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="/admin/login" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Admin username" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Admin password" required>
                        </div>
                        <button type="submit" class="btn btn-admin w-100 fw-bold">Login sebagai Admin</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="/" class="text-decoration-none small text-muted">← Kembali ke Website</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>