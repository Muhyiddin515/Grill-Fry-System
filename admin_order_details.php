<?php
session_start();
require 'connection.php';


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}

$order_id = (int)$_GET['order_id'];


if (isset($_POST['status'])) {

    $newStatus = $_POST['status'];

    
    $stmtUpdate = $connection->prepare(
        "UPDATE orders SET status=? WHERE id=?"
    );
    $stmtUpdate->bind_param("si", $newStatus, $order_id);
    $stmtUpdate->execute();

   
    if ($newStatus == 'done') {

       
        $check = $connection->prepare(
            "SELECT id FROM money WHERE order_id=?"
        );
        $check->bind_param("i", $order_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows == 0) {

            $stmtMoney = $connection->prepare("
                INSERT INTO money (order_id, user_id, amount, payment_method)
                SELECT id, user_id, total, payment_method
                FROM orders
                WHERE id = ?
            ");
            $stmtMoney->bind_param("i", $order_id);
            $stmtMoney->execute();
        }
    }
}




$stmtOrder = $connection->prepare("
    SELECT 
        o.user_id,
        o.total,
        o.status,
        o.payment_method,
        o.voucher_image,
        o.created_at,
        a.address AS delivery_address
    FROM orders o
    LEFT JOIN address a 
        ON a.user_id = o.user_id 
       AND a.user_type = 'customer'
    WHERE o.id = ?
");
$stmtOrder->bind_param("i", $order_id);
$stmtOrder->execute();
$order = $stmtOrder->get_result()->fetch_assoc();

if (!$order) {
    die('Order not found');
}



$stmt = $connection->prepare("
    SELECT item_name, quantity, price
    FROM order_items WHERE order_id=?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Order Details - GRILL & FRY</title>

<link rel="stylesheet" href="stylesheet.css">
<link rel="stylesheet" href="order_style.css">

<style>
     body {
      background-color: wheat;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }

      .nav{
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px 0;
    }
    .nav .logo h1{
    font-weight: 600;
    font-family: sans-serif;
    color: #000000;
}

.nav .logo b{
    color: #ff511c;

}

.nav ul{
    display: flex;
    list-style: none;
}
.nav ul li{
    margin-right:30px;
}
.nav ul li a{
    text-decoration: none;
    color:#000000;
    font-weight: 500;
    font-family: sans-serif;
    font-size:17px ;
}

.nav ul .active::after{
    content: '';
    width: 50%;
    height: 3px;
    background-color: #ff511c;
    display: flex;
    position: relative;
    margin-left: 10px;
}

    header {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 10px;
    }
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.info-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
.info-card b {
    display: block;
    color: #555;
    margin-bottom: 6px;
}

.payment-badge {
    padding: 8px 18px;
    border-radius: 20px;
    color: white;
    font-size: 15px;
    display: inline-block;
}
.payment-cash { background:#28a745; }
.payment-wish { background:purple; }
.payment-card { background:#007bff; }

.status-form {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}

.voucher-box img {
    max-width: 260px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,.2);
    margin-top: 10px;
}
</style>
</head>

<body>

<?php include 'admin_nav.php'; ?>

<div class="page-container">

    <h1 class="page-title">📦 Order #<?php echo $order_id; ?></h1>

    
    <div class="info-grid">
        <div class="info-card">
            <b>User ID</b>
            <?php echo $order['user_id']; ?>
        </div>

        <div class="info-card">
            <b>Total</b>
            $<?php echo number_format($order['total'],2); ?>
        </div>

        <div class="info-card">
            <b>Date</b>
            <?php echo $order['created_at']; ?>
        </div>

        <div class="info-card">
            <b>Payment</b>
            <?php if ($order['payment_method']=="Cash on Delivery"): ?>
                <span class="payment-badge payment-cash">💵 Cash on Delivery</span>
            <?php elseif ($order['payment_method']=="Wish Money"): ?>
                <span class="payment-badge payment-wish">📱 Wish Money</span>
            <?php else: ?>
                <span class="payment-badge payment-card">💳 Card</span>
            <?php endif; ?>
        </div>
        <div class="info-card">
    <b>Delivery Address</b>
    <?php echo htmlspecialchars($order['delivery_address'] ?? 'No address'); ?>
</div>


    </div>

   
    <?php if ($order['payment_method']=="Wish Money"): ?>
        <div class="info-card voucher-box">
            <b>Payment Voucher</b>

            <?php if (!empty($order['voucher_image'])): ?>
                <a href="uploads/vouchers/<?php echo $order['voucher_image']; ?>" target="_blank">
                    <img src="uploads/vouchers/<?php echo $order['voucher_image']; ?>" alt="Voucher Image">
                </a>
                <p style="font-size:14px;color:#666;">Click image to view full size</p>
            <?php else: ?>
                <p style="color:red;">⚠ No voucher uploaded</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    
    <form method="post" class="status-form">
        <b>Status:</b>
        <select name="status">
            <option value="pending" <?php if($order['status']=="pending") echo "selected"; ?>>Pending</option>
            <option value="confirmed" <?php if($order['status']=="confirmed") echo "selected"; ?>>Confirmed</option>
            <option value="done" <?php if($order['status']=="done") echo "selected"; ?>>Done</option>
        </select>
        <button class="btn btn-dark">Update Status</button>
    </form>

   
    <table class="table">
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>$<?php echo number_format($row['price'],2); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div style="text-align:center;margin-top:30px;">
        <a href="admin_orders.php" class="btn btn-dark">⬅ Back to Orders</a>
    </div>

</div>

</body>
</html>
