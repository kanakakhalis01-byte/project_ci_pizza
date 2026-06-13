<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<section class="hero-section text-center text-md-start">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h1 class="display-4 fw-bold">Welcome to <br> The world of Tasty & Fresh Pizza</h1>
                <p class="lead">Fresh, cheesy, and guaranteed to make you happy! Every bite is a perfect blend of rich flavors and premium ingredients.</p>
                <a href="#menu" class="btn btn-custom btn-lg px-4">Choose a Pizza</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="<?= base_url('img/home.png'); ?>" alt="home" class="img-fluid w-75">
            </div>
        </div>
    </div>
</section>

<section id="menu" class="container mt-5 pt-5 mb-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Our Pizza Menu</h2>
        <p class="text-muted">We have selected for You the most exquisite tastes around the world</p>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8 col-lg-6">
            <form action="/#menu" method="GET" class="d-flex shadow-sm rounded bg-white p-1">
                <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" class="form-control form-control-lg border-0" placeholder="Find your pizza...">
                <button type="submit" class="btn btn-danger btn-lg fw-bold px-4 rounded">Cari</button>
            </form>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-warning text-center py-5 shadow-sm rounded-4">
            <h1 class="display-1 mb-3">🍕</h1>
            <h4 class="fw-bold">Sorry, pizza "<?= esc($keyword) ?>" not found!</h4>
            <p class="text-muted mb-4">Try using other keywords like "cheese", "meat", or "mushroom".</p>
            <a href="/#menu" class="btn btn-outline-dark fw-bold">← View All Menu</a>
        </div>
    <?php else: ?>

        <div class="row g-4">
            <?php foreach ($products as $product): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card pizza-card h-100 text-center p-3 shadow-sm border-0">
                    <img src="<?= base_url(str_replace('public/', '', $product['img'])); ?>" class="card-img-top w-75 mx-auto" alt="<?= $product['name']; ?>">
                    
                    <div class="card-body px-0">
                        <h5 class="card-title fw-bold text-dark"><?= $product['name']; ?></h5>
                        <p class="card-text small text-muted mb-3" style="min-height: 40px;"><?= $product['desc']; ?></p>
                        <h5 class="text-danger fw-bold mb-0">$<?= number_format($product['price'], 2); ?></h5>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0 px-0 pb-0">
                        <form action="/cart/add" method="POST">
                            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                            <button type="submit" class="btn btn-warning w-100 text-dark fw-bold shadow-sm">🛒 Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
    <?php endif; ?>
</section>

<?= $this->endSection(); ?>