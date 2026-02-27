<?php
session_start();
require 'connection.php';

if (!isset($_GET['order_id'])) {
    header("Location: cart.php");
    exit;
}

$order_id = (int)$_GET['order_id'];


$stmt = $connection->prepare("
    SELECT payment_method 
    FROM orders 
    WHERE id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$payment_method = $order['payment_method'] ?? 'Not specified';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Success - GRILL & FRY</title>

<link rel="stylesheet" href="stylesheet.css">
<link rel="stylesheet" href="order_style.css">

<style>
.success-container {
    max-width: 600px;
    margin: 120px auto;
    background: #fcfaf9ff;
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.success-container h1 {
    color: #28a745;
    margin-bottom: 20px;
}

.order-box {
    font-size: 18px;
    margin: 25px 0;
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    border: 2px dashed #ff4d4d;
}

.payment-badge {
    display: inline-block;
    margin-top: 10px;
    padding: 6px 16px;
    border-radius: 20px;
    color: white;
    font-size: 14px;
}

.payment-cash {
    background: #28a745;
}

.payment-wish {
    background: purple;
}

.payment-card {
    background: #007bff;
}

.success-actions {
    margin-top: 35px;
}

.success-actions a {
    display: inline-block;
    margin: 10px;
    padding: 12px 28px;
    background: #ff4d4d;
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-size: 16px;
    transition: 0.3s;
}

.success-actions a:hover {
    background: #e63b3b;
}
</style>
</head>

<body>

<?php include 'nav.php'; ?>

<div class="success-container">

    <h1>🎉 Order Placed Successfully!</h1>

    <p>Thank you for ordering from <b>GRILL & FRY</b>.</p>

    <div class="order-box">
        <p>
            <b>Order ID:</b> #<?php echo $order_id; ?>
        </p>

        <p>
            <b>Payment Method:</b><br>

            <?php if ($payment_method == "Cash on Delivery"): ?>
                <span class="payment-badge payment-cash">💵 Cash on Delivery</span>
            <?php elseif ($payment_method == "Wish Money"): ?>
                <span class="payment-badge payment-wish">📱 Wish Money</span>
            <?php elseif ($payment_method == "Card"): ?>
                <span class="payment-badge payment-card">💳 Card</span>
            <?php else: ?>
                <span><?php echo htmlspecialchars($payment_method); ?></span>
            <?php endif; ?>
        </p>
    </div>

    <p>Our team is preparing your order.</p>

    <div class="success-actions">
        <a href="menu.php">🍽 Back to Menu</a>
        <a href="my_orders.php">📦 My Orders</a>
    </div>

</div>

</body>
</html>
