<?php
require_once '../../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_GET['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID required']);
    exit;
}

$product_id = intval($_GET['product_id']);

try {
    // Get product image from products table
    $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    $images = [];
    if ($product && $product['image_url']) {
        $images[] = [
            'id' => 1,
            'product_id' => $product_id,
            'image_url' => $product['image_url'],
            'is_primary' => 1
        ];
    }
    
    echo json_encode([
        'success' => true,
        'images' => $images
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
