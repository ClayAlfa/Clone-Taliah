<?php
require_once '../config.php';

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

try {
    // Get order ID from POST data
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    
    if ($order_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    
    // Verify that the order belongs to the current user and can be cancelled
    $stmt = $pdo->prepare("
        SELECT id, status 
        FROM orders 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch();
    
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found or access denied']);
        exit;
    }
    
    // Check if order can be cancelled (only pending orders can be cancelled)
    if ($order['status'] !== 'pending') {
        echo json_encode(['success' => false, 'message' => 'Order cannot be cancelled. Status: ' . $order['status']]);
        exit;
    }
    
    // Update order status to cancelled
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET status = 'cancelled', updated_at = NOW() 
        WHERE id = ? AND user_id = ?
    ");
    
    if ($stmt->execute([$order_id, $user_id])) {
        echo json_encode([
            'success' => true, 
            'message' => 'Order has been successfully cancelled'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to cancel order. Please try again.'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Cancel order error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred while cancelling the order'
    ]);
}
?>
