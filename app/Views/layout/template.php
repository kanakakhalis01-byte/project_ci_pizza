<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza's Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body { background-color: #fef8e5; }
        .navbar-custom { background-color: #ff9f1c; }
        .hero-section { background-color: #fff3e0; padding: 80px 0; }
        .pizza-card { border: none; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: 0.3s; }
        .pizza-card:hover { transform: translateY(-5px); }
        .btn-custom { background-color: #e71d36; color: white; border-radius: 20px; }
        .btn-custom:hover { background-color: #d11a2f; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">Pizza's Order</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
        
        <li class="nav-item">
            <a class="nav-link text-dark fw-bold" data-bs-toggle="offcanvas" href="#cartSidebar" role="button">
                🛒 Cart <span class="badge bg-danger"><?= count(session()->get('cart') ?? []) ?></span>
            </a>
        </li>
        
        <?php if(session()->get('logged_in')): ?>
            <li class="nav-item"><a class="nav-link" href="/transactions">Riwayat Transaksi</a></li>
            <li class="nav-item"><a class="btn btn-danger btn-sm ms-3" href="/logout">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="btn btn-outline-dark btn-sm ms-2" href="/login">Login</a></li>
            <li class="nav-item"><a class="btn btn-light btn-sm ms-2 fw-bold text-danger" href="/register">Register</a></li>
        <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>

<?= $this->renderSection('content'); ?>

<div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
  <div class="offcanvas-header bg-warning">
    <h5 class="offcanvas-title fw-bold" id="cartSidebarLabel">🛒 Shopping Cart</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <?php $cart = session()->get('cart') ?? []; $total = 0; ?>
    <?php if (empty($cart)): ?>
        <p class="text-muted text-center mt-5">Keranjang Anda kosong.</p>
    <?php else: ?>
        <?php foreach ($cart as $item): $total += $item['price'] * $item['quantity']; ?>
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <div>
                <h6 class="mb-0 fw-bold"><?= $item['name']; ?></h6>
                <small class="text-danger">$<?= number_format($item['price'], 2); ?></small>
                
                <form action="/cart/update" method="POST" class="d-inline-block mt-1">
                    <input type="hidden" name="product_id" value="<?= $item['id']; ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1" class="form-control form-control-sm d-inline w-auto" onchange="this.form.submit()">
                </form>
            </div>
            
            <a href="/cart/remove/<?= $item['id']; ?>" class="btn btn-sm btn-outline-danger">Hapus</a>
        </div>
        <?php endforeach; ?>
        
        <div class="mt-4">
            <h5 class="fw-bold d-flex justify-content-between">Total: <span>$<?= number_format($total, 2); ?></span></h5>
            <form action="/checkout" method="POST" class="mt-3">
                <button type="submit" class="btn btn-danger w-100 fw-bold py-2">CHECKOUT SEKARANG</button>
            </form>
        </div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>