<?php
require_once 'config.php';

echo "<h2>Adding image_url Column to Products Table</h2>";

try {
    // Check if column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'image_url'");
    $column_exists = $stmt->fetch();
    
    if ($column_exists) {
        echo "<p style='color: orange;'>⚠️ Kolom image_url sudah ada di tabel products</p>";
    } else {
        // Add image_url column
        echo "<p>Menambahkan kolom image_url ke tabel products...</p>";
        $pdo->exec("ALTER TABLE products ADD COLUMN image_url VARCHAR(500) DEFAULT NULL");
        echo "<p style='color: green;'>✅ Kolom image_url berhasil ditambahkan ke tabel products</p>";
    }
    
    // Check if product_images table exists and migrate data
    $stmt = $pdo->query("SHOW TABLES LIKE 'product_images'");
    $table_exists = $stmt->fetch();
    
    if ($table_exists) {
        echo "<p>Memindahkan data dari product_images ke products...</p>";
        
        // Update products with primary images from product_images
        $stmt = $pdo->exec("
            UPDATE products p 
            SET image_url = (
                SELECT pi.image_url 
                FROM product_images pi 
                WHERE pi.product_id = p.id 
                AND pi.is_primary = 1 
                LIMIT 1
            )
            WHERE EXISTS (
                SELECT 1 
                FROM product_images pi 
                WHERE pi.product_id = p.id 
                AND pi.is_primary = 1
            )
        ");
        
        echo "<p style='color: green;'>✅ $stmt produk berhasil diupdate dengan gambar dari product_images</p>";
        
        // Optionally drop product_images table
        echo "<p>Menghapus tabel product_images...</p>";
        $pdo->exec("DROP TABLE product_images");
        echo "<p style='color: green;'>✅ Tabel product_images berhasil dihapus</p>";
    }
    
    echo "<h3 style='color: green;'>✅ Proses selesai!</h3>";
    echo "<p>Sekarang tabel products sudah memiliki kolom image_url dan data sudah dipindahkan.</p>";
    echo "<p><a href='admin/products.php'>← Kembali ke halaman products</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
