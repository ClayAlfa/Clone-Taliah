<?php
require_once '../config.php';

requireAdmin();

$page_title = 'Manajemen Produk';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = trim($_POST['name']);
                $description = trim($_POST['description']);
                $price = floatval($_POST['price']);
                $stock = intval($_POST['stock']);
                $category_id = intval($_POST['category_id']);
                $brand = trim($_POST['brand']);
                $size = trim($_POST['size']);
                $material = trim($_POST['material']);
                
                if (empty($name) || empty($description) || $price <= 0 || $stock < 0 || $category_id <= 0) {
                    $_SESSION['error'] = 'Semua field harus diisi dengan benar!';
                } else {
                    try {
                        $image_url = !empty($_POST['image_url']) ? trim($_POST['image_url']) : null;
                        
                        $stmt = $pdo->prepare("
                            INSERT INTO products (name, description, price, stock, category_id, brand, size, material, image_url, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
                        ");
                        $stmt->execute([$name, $description, $price, $stock, $category_id, $brand, $size, $material, $image_url]);
                        
                        logActivity($_SESSION['user_id'], 'Menambahkan produk: ' . $name);
                        $_SESSION['success'] = 'Produk berhasil ditambahkan!';
                    } catch (Exception $e) {
                        $_SESSION['error'] = 'Gagal menambahkan produk: ' . $e->getMessage();
                    }
                }
                break;
                
            case 'edit':
                $id = intval($_POST['id']);
                $name = trim($_POST['name']);
                $description = trim($_POST['description']);
                $price = floatval($_POST['price']);
                $stock = intval($_POST['stock']);
                $category_id = intval($_POST['category_id']);
                $brand = trim($_POST['brand']);
                $size = trim($_POST['size']);
                $material = trim($_POST['material']);
                
                if (empty($name) || empty($description) || $price <= 0 || $stock < 0 || $category_id <= 0) {
                    $_SESSION['error'] = 'Semua field harus diisi dengan benar!';
                } else {
                    try {
                        $image_url = !empty($_POST['image_url']) ? trim($_POST['image_url']) : null;
                        
                        $stmt = $pdo->prepare("
                            UPDATE products 
                            SET name = ?, description = ?, price = ?, stock = ?, category_id = ?, brand = ?, size = ?, material = ?, image_url = ?, updated_at = NOW()
                            WHERE id = ?
                        ");
                        $stmt->execute([$name, $description, $price, $stock, $category_id, $brand, $size, $material, $image_url, $id]);
                        
                        logActivity($_SESSION['user_id'], 'Mengubah produk: ' . $name);
                        $_SESSION['success'] = 'Produk berhasil diperbarui!';
                    } catch (Exception $e) {
                        $_SESSION['error'] = 'Gagal memperbarui produk: ' . $e->getMessage();
                    }
                }
                break;
                
            case 'delete':
                $id = intval($_POST['id']);
                try {
                    // Get product name for log
                    $stmt = $pdo->prepare("SELECT name FROM products WHERE id = ?");
                    $stmt->execute([$id]);
                    $product_name = $stmt->fetchColumn();
                    
                    // No need to delete separate image files since we're using URLs
                    $pdo->prepare("DELETE FROM carts WHERE product_id = ?")->execute([$id]);
                    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
                    
                    logActivity($_SESSION['user_id'], 'Menghapus produk: ' . $product_name);
                    $_SESSION['success'] = 'Produk berhasil dihapus!';
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Gagal menghapus produk: ' . $e->getMessage();
                }
                break;
                
        }
    }
    
    header('Location: products.php');
    exit;
}

// Get filters
$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

// Build query
$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category_filter)) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $category_filter;
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Sort options
$sort_options = [
    'newest' => 'p.created_at DESC',
    'oldest' => 'p.created_at ASC',
    'name_asc' => 'p.name ASC',
    'name_desc' => 'p.name DESC',
    'price_asc' => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'stock_asc' => 'p.stock ASC',
    'stock_desc' => 'p.stock DESC'
];

$order_clause = 'ORDER BY ' . ($sort_options[$sort] ?? $sort_options['newest']);

// Pagination
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Get total count
$count_query = "SELECT COUNT(*) FROM products p JOIN categories c ON p.category_id = c.id $where_clause";
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_products = $stmt->fetchColumn();
$total_pages = ceil($total_products / $per_page);

// Get products
$query = "
    SELECT p.*, c.name as category_name
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    $where_clause 
    $order_clause 
    LIMIT $per_page OFFSET $offset
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories for filter and form
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

require_once 'includes/admin_header.php';
?>

<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Produk</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus me-2"></i>Tambah Produk
        </button>
    </div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Brand</label>
                            <input type="text" class="form-control" name="brand">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ukuran</label>
                            <input type="text" class="form-control" name="size" placeholder="Contoh: 38, 39, 40">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga *</label>
                            <input type="number" class="form-control" name="price" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok *</label>
                            <input type="number" class="form-control" name="stock" min="0" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Material</label>
                            <input type="text" class="form-control" name="material" placeholder="Contoh: Kulit asli, Canvas, Sintetis">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi *</label>
                            <textarea class="form-control" name="description" rows="4" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">URL Gambar Produk</label>
                            <input type="url" class="form-control" name="image_url" placeholder="https://source.unsplash.com/400x400?shoes">
                            <small class="text-muted">Contoh: https://source.unsplash.com/400x400?shoes atau URL gambar lainnya</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk *</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select" name="category_id" id="edit_category_id" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Brand</label>
                            <input type="text" class="form-control" name="brand" id="edit_brand">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ukuran</label>
                            <input type="text" class="form-control" name="size" id="edit_size">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga *</label>
                            <input type="number" class="form-control" name="price" id="edit_price" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok *</label>
                            <input type="number" class="form-control" name="stock" id="edit_stock" min="0" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Material</label>
                            <input type="text" class="form-control" name="material" id="edit_material">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi *</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="4" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">URL Gambar Produk</label>
                            <input type="url" class="form-control" name="image_url" id="edit_image_url" placeholder="https://source.unsplash.com/400x400?shoes">
                            <small class="text-muted">Masukkan URL gambar produk. Contoh: https://source.unsplash.com/400x400?shoes</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Product Images Modal -->
<div class="modal fade" id="productImagesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gambar Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="productImagesContainer">
                    <!-- Images will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong id="deleteProductName"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteProductId">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editProduct(product) {
    document.getElementById('edit_id').value = product.id;
    document.getElementById('edit_name').value = product.name;
    document.getElementById('edit_description').value = product.description;
    document.getElementById('edit_price').value = product.price;
    document.getElementById('edit_stock').value = product.stock;
    document.getElementById('edit_category_id').value = product.category_id;
    document.getElementById('edit_brand').value = product.brand || '';
    document.getElementById('edit_size').value = product.size || '';
    document.getElementById('edit_material').value = product.material || '';
    document.getElementById('edit_image_url').value = product.image_url || '';
    
    new bootstrap.Modal(document.getElementById('editProductModal')).show();
}

function deleteProduct(id, name) {
    document.getElementById('deleteProductId').value = id;
    document.getElementById('deleteProductName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

</script>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pencarian</label>
                    <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Cari nama, deskripsi, atau brand...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="category">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $category_filter == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Urutkan</label>
                    <select class="form-select" name="sort">
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Terbaru</option>
                        <option value="oldest" <?php echo $sort == 'oldest' ? 'selected' : ''; ?>>Terlama</option>
                        <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Nama A-Z</option>
                        <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Nama Z-A</option>
                        <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Harga Terendah</option>
                        <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Harga Tertinggi</option>
                        <option value="stock_asc" <?php echo $sort == 'stock_asc' ? 'selected' : ''; ?>>Stok Terendah</option>
                        <option value="stock_desc" <?php echo $sort == 'stock_desc' ? 'selected' : ''; ?>>Stok Tertinggi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Produk (<?php echo number_format($total_products); ?> produk)</h5>
        </div>
        <div class="card-body p-0">
            <?php if ($products): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Brand</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <?php 
                                $image_src = 'https://source.unsplash.com/60x60?product';
                                if (!empty($product['image_url']) && filter_var($product['image_url'], FILTER_VALIDATE_URL)) {
                                    $image_src = htmlspecialchars($product['image_url']);
                                } elseif (!empty($product['image_url'])) {
                                    $keyword = urlencode(str_replace(' ', '+', $product['name']));
                                    $image_src = "https://source.unsplash.com/60x60?" . $keyword;
                                }
                                ?>
                                <img src="<?php echo $image_src; ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo htmlspecialchars(substr($product['description'], 0, 50)); ?>...</small>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['brand'] ?? ''); ?></td>
                            <td><?php echo formatPrice($product['price']); ?></td>
                            <td>
                                <span class="badge <?php echo $product['stock'] <= 5 ? 'bg-warning' : 'bg-success'; ?>">
                                    <?php echo $product['stock']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo $product['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $product['stock'] > 0 ? 'Tersedia' : 'Habis'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="deleteProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="card-footer">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada produk ditemukan</h5>
                <p class="text-muted">Coba ubah filter pencarian atau tambah produk baru</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
