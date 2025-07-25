<?php
require_once 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Get order ID from URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$order_id) {
    header('Location: orders.php');
    exit();
}

// Handle status update
if ($_POST['action'] === 'update_status' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    $valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    
    if (in_array($new_status, $valid_statuses)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        if ($stmt->execute([$new_status, $order_id])) {
            $success_message = "Order status updated successfully.";
        } else {
            $error_message = "Failed to update order status.";
        }
    }
}

// Fetch order details
$stmt = $pdo->prepare("
    SELECT o.*, u.username, u.email, u.first_name, u.last_name 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php');
    exit();
}

// Fetch order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.title, p.price, p.image_url 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

$page_title = "Order Details #" . $order['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: #333;
            color: white;
            padding: 1rem 0;
            margin-bottom: 30px;
        }
        
        .header h1 {
            text-align: center;
        }
        
        .nav {
            text-align: center;
            margin-top: 10px;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        
        .nav a:hover {
            background-color: #555;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #333;
            text-decoration: none;
            padding: 8px 15px;
            background: #e0e0e0;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .back-link:hover {
            background-color: #d0d0d0;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .card h2 {
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        
        .info-row {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            width: 120px;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status.processing {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status.shipped {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status.delivered {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status.cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-form {
            margin-top: 15px;
        }
        
        .status-form select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        
        .btn.primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn.primary:hover {
            background-color: #0056b3;
        }
        
        .order-items {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .order-items h2 {
            background: #f8f9fa;
            padding: 15px 20px;
            margin: 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table th,
        .items-table td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .items-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .order-details {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .items-table {
                font-size: 14px;
            }
            
            .items-table th,
            .items-table td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Shoe Store Admin</h1>
            <div class="nav">
                <a href="admin/dashboard.php">Dashboard</a>
                <a href="admin/products.php">Products</a>
                <a href="orders.php">Orders</a>
                <a href="admin/users.php">Users</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <a href="orders.php" class="back-link">← Back to Orders</a>
        
        <h1><?php echo $page_title; ?></h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <div class="order-details">
            <div class="card">
                <h2>Order Information</h2>
                <div class="info-row">
                    <span class="info-label">Order ID:</span>
                    #<?php echo $order['id']; ?>
                </div>
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status <?php echo $order['status']; ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total:</span>
                    $<?php echo number_format($order['total_amount'], 2); ?>
                </div>
                
                <form method="POST" class="status-form">
                    <input type="hidden" name="action" value="update_status">
                    <select name="status">
                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                    <button type="submit" class="btn primary">Update Status</button>
                </form>
            </div>
            
            <div class="card">
                <h2>Customer Information</h2>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                </div>
                <div class="info-row">
                    <span class="info-label">Username:</span>
                    <?php echo htmlspecialchars($order['username']); ?>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <?php echo htmlspecialchars($order['email']); ?>
                </div>
            </div>
        </div>
        
        <div class="order-items">
            <h2>Order Items</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($order_items as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <?php if ($item['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                         class="product-image">
                                <?php else: ?>
                                    <div class="product-image" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #666;">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="4"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
