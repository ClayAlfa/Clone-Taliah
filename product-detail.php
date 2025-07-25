<?php
require_once 'config.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    header('Location: products.php');
    exit;
}

// Get product details
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}

$page_title = $product['name'];

include 'includes/header.php';
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="products.php">Produk</a></li>
            <?php if ($product['category_name']): ?>
            <li class="breadcrumb-item">
                <a href="products.php?category=<?php echo $product['category_id']; ?>">
                    <?php echo htmlspecialchars($product['category_name']); ?>
                </a>
            </li>
            <?php endif; ?>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-6 mb-4">
            <div class="product-detail-image">
                <?php 
                $image_src = 'https://source.unsplash.com/600x600?product';
                if (!empty($product['image_url']) && filter_var($product['image_url'], FILTER_VALIDATE_URL)) {
                    $image_src = htmlspecialchars($product['image_url']);
                } elseif (!empty($product['image_url'])) {
                    // Jika ada image_url tapi tidak valid, gunakan unsplash dengan keyword dari nama produk
                    $keyword = urlencode(str_replace(' ', '+', $product['name']));
                    $image_src = "https://source.unsplash.com/600x600?" . $keyword;
                }
                ?>
                <img src="<?php echo $image_src; ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     class="img-fluid rounded shadow-sm">
                
                <?php if ($product['stock'] <= 5 && $product['stock'] > 0): ?>
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-warning">Stok Terbatas</span>
                    </div>
                <?php elseif ($product['stock'] == 0): ?>
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-danger">Stok Habis</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="product-detail-info">
                <h1 class="display-6 fw-bold mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="mb-3">
                    <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($product['category_name']); ?></span>
                </div>

                <div class="price-section mb-4">
                    <h2 class="text-primary fw-bold mb-0"><?php echo formatPrice($product['price']); ?></h2>
                </div>

                <div class="product-description mb-4">
                    <h5>Deskripsi Produk</h5>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="product-info mb-4">
                    <div class="row">
                        <div class="col-6">
                            <strong>Stok:</strong>
                            <span class="<?php echo $product['stock'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                <?php echo $product['stock']; ?> tersedia
                            </span>
                        </div>
                        <div class="col-6">
                            <strong>SKU:</strong>
                            <span class="text-muted">#<?php echo $product['id']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart Section -->
                <?php if (isLoggedIn()): ?>
                    <?php if ($product['stock'] > 0): ?>
                        <div class="add-to-cart-section mb-4">
                            <div class="row align-items-center">
                                <div class="col-4">
                                    <label for="quantity" class="form-label">Jumlah:</label>
                                    <select id="quantity" class="form-select">
                                        <?php for ($i = 1; $i <= min(10, $product['stock']); $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-8">
                                    <button class="btn btn-primary btn-lg w-100 add-to-cart" 
                                            data-product-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-cart-plus me-2"></i>
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Produk ini sedang tidak tersedia
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <a href="login.php" class="alert-link">Login</a> untuk menambahkan produk ke keranjang
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="products.php" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali ke Produk
                    </a>
                    <?php if ($product['category_id']): ?>
                    <a href="products.php?category=<?php echo $product['category_id']; ?>" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>
                        Produk Serupa
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Information Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                                    data-bs-target="#description" type="button" role="tab">
                                Deskripsi Detail
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="info-tab" data-bs-toggle="tab" 
                                    data-bs-target="#info" type="button" role="tab">
                                Informasi Produk
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabsContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                        </div>
                        <div class="tab-pane fade" id="info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Nama Produk:</strong></td>
                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kategori:</strong></td>
                                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga:</strong></td>
                                            <td><?php echo formatPrice($product['price']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Stok:</strong></td>
                                            <td><?php echo $product['stock']; ?> unit</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Ditambahkan:</strong></td>
                                            <td><?php echo date('d F Y', strtotime($product['created_at'])); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if ($product['category_id']): ?>
    <?php
    $related_stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.category_id = ? AND p.id != ? 
        ORDER BY RAND() 
        LIMIT 4
    ");
    $related_stmt->execute([$product['category_id'], $product_id]);
    $related_products = $related_stmt->fetchAll();
    ?>
    
    <?php if ($related_products): ?>
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Produk Serupa</h3>
            <div class="row g-4">
                <?php foreach ($related_products as $related): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card">
                        <div class="product-image">
                            <?php 
                            $related_image_src = 'https://source.unsplash.com/300x300?product';
                            if (!empty($related['image_url']) && filter_var($related['image_url'], FILTER_VALIDATE_URL)) {
                                $related_image_src = htmlspecialchars($related['image_url']);
                            } elseif (!empty($related['image_url'])) {
                                $keyword = urlencode(str_replace(' ', '+', $related['name']));
                                $related_image_src = "https://source.unsplash.com/300x300?" . $keyword;
                            }
                            ?>
                            <img src="<?php echo $related_image_src; ?>" 
                                 alt="<?php echo htmlspecialchars($related['name']); ?>" 
                                 class="card-img-top">
                            <?php if ($related['stock'] <= 5): ?>
                                <div class="product-badge bg-warning">Stok Terbatas</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body product-info">
                            <h6 class="product-title"><?php echo $related['name']; ?></h6>
                            <p class="product-description"><?php echo substr($related['description'], 0, 50) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="product-price"><?php echo formatPrice($related['price']); ?></span>
                                <small class="text-muted"><?php echo $related['category_name']; ?></small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Stok: <?php echo $related['stock']; ?></small>
                                <div class="btn-group btn-group-sm">
                                    <a href="product-detail.php?id=<?php echo $related['id']; ?>" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if (isLoggedIn() && $related['stock'] > 0): ?>
                                    <button class="btn btn-primary add-to-cart" 
                                            data-product-id="<?php echo $related['id']; ?>">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.product-detail-image {
    position: relative;
}

.product-detail-image img {
    width: 100%;
    height: auto;
    max-height: 600px;
    object-fit: cover;
}

.product-detail-info {
    height: 100%;
}

.price-section h2 {
    font-size: 2.5rem;
}

.add-to-cart-section {
    border-top: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
    padding: 1.5rem 0;
}

.action-buttons {
    margin-top: 1rem;
}

.product-card {
    transition: transform 0.2s;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    position: relative;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.product-info {
    padding: 1rem;
}

.product-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.product-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
}

.product-price {
    font-weight: 700;
    color: #007bff;
    font-size: 1.1rem;
}
</style>

<?php include 'includes/footer.php'; ?>
