<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = (int)$_GET['order_id'];

$stmtOrder = $connection->prepare("
    SELECT payment_method 
    FROM orders 
    WHERE id = ? AND user_id = ?
");
$stmtOrder->bind_param("ii", $order_id, $user_id);
$stmtOrder->execute();
$order = $stmtOrder->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

$payment_method = $order['payment_method'];


$stmt = $connection->prepare("
    SELECT item_name, quantity, price
    FROM order_items
    WHERE order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details - GRILL & FRY</title>

<link rel="stylesheet" href="stylesheet.css">
<link rel="stylesheet" href="order_style.css">

<style>
.payment-badge {
    padding: 6px 14px;
    border-radius: 20px;
    color: white;
    font-size: 14px;
    display: inline-block;
}
.payment-cash { background: #28a745; }
.payment-wish { background: purple; }
.payment-card { background: #007bff; }
</style>
</head>

<body>

<?php include 'nav.php'; ?>

<div class="page-container">

    <h1 class="page-title">📦 Order #<?php echo $order_id; ?></h1>

    <p style="text-align:center;margin-bottom:25px;">
        <b>Payment Method:</b>
        <?php if ($payment_method == "Cash on Delivery"): ?>
            <span class="payment-badge payment-cash">💵 Cash on Delivery</span>
        <?php elseif ($payment_method == "Wish Money"): ?>
            <span class="payment-badge payment-wish">📱 Wish Money</span>
        <?php elseif ($payment_method == "Card"): ?>
            <span class="payment-badge payment-card">💳 Card</span>
        <?php else: ?>
            <?php echo htmlspecialchars($payment_method); ?>
        <?php endif; ?>
    </p>

    <table class="table">
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>$<?php echo number_format($row['price'],2); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div style="text-align:center;margin-top:30px;">
        <a href="my_orders.php" class="btn btn-dark">⬅ Back to My Orders</a>
    </div>

</div>

</body>
</html>
