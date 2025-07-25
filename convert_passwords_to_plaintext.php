<!DOCTYPE html>
<html>
<head>
    <title>Konversi Password ke Plain Text</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .warning { color: red; font-weight: bold; }
        .success { color: green; }
        .info { background: #f0f8ff; padding: 15px; border-left: 4px solid #007cba; margin: 20px 0; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Konversi Password ke Plain Text</h1>
    
    <div class="warning">
        ⚠️ PERINGATAN: Script ini akan mengubah semua password menjadi plain text!<br>
        ⚠️ INI SANGAT TIDAK AMAN DAN HANYA untuk keperluan development/demo!
    </div>
    
    <?php
    require_once 'config.php';
    
    if (isset($_POST['convert'])) {
        try {
            // Update password admin menjadi plain text
            $stmt = $pdo->prepare("UPDATE users SET password = 'admin123' WHERE email = 'admin@shoebrand.com'");
            if ($stmt->execute()) {
                echo "<div class='success'>✓ Password admin berhasil diubah menjadi: admin123</div>";
            }
            
            // Update password customer demo menjadi plain text
            $stmt = $pdo->prepare("UPDATE users SET password = 'customer123' WHERE email = 'customer@example.com'");
            if ($stmt->execute()) {
                echo "<div class='success'>✓ Password customer demo berhasil diubah menjadi: customer123</div>";
            }
            
            // Update semua password yang masih ter-hash menjadi default password
            $stmt = $pdo->prepare("UPDATE users SET password = 'password123' WHERE LENGTH(password) > 20");
            $stmt->execute();
            $count = $stmt->rowCount();
            
            if ($count > 0) {
                echo "<div class='success'>✓ $count password lainnya berhasil diubah menjadi: password123</div>";
            }
            
            echo "<div class='success'><h3>✅ Konversi password selesai!</h3></div>";
            
        } catch (Exception $e) {
            echo "<div style='color: red'>❌ Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>
    
    <div class="info">
        <h3>📋 Akun Demo yang akan tersedia setelah konversi:</h3>
        <pre>
• Admin: admin@shoebrand.com / admin123
• Customer: customer@example.com / customer123
• User lainnya: [email] / password123</pre>
    </div>
    
    <form method="POST">
        <button type="submit" name="convert" onclick="return confirm('Apakah Anda yakin ingin mengubah semua password menjadi plain text?')" 
                style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            🔓 Konversi ke Plain Text
        </button>
    </form>
    
    <p><a href="index.php">← Kembali ke beranda</a></p>
</body>
</html>
