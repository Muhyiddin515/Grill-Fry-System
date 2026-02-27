<?php
session_start();
require 'connection.php';


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}


$statusFilter = $_GET['status'] ?? 'all';
$where = "";
if (in_array($statusFilter, ['pending','confirmed','done'])) {
    $where = "WHERE status = '$statusFilter'";
}


$sql = "
SELECT id, user_id, total, status, payment_method, created_at
FROM orders
$where
ORDER BY created_at DESC
";
$result = $connection->query($sql);


$countPending   = $connection->query("SELECT COUNT(*) c FROM orders WHERE status='pending'")->fetch_assoc()['c'];
$countConfirmed = $connection->query("SELECT COUNT(*) c FROM orders WHERE status='confirmed'")->fetch_assoc()['c'];
$countDone      = $connection->query("SELECT COUNT(*) c FROM orders WHERE status='done'")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Orders - GRILL & FRY</title>

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

.filter-bar {
    text-align: center;
    margin-bottom: 25px;
}
.filter-bar a {
    margin: 5px;
    padding: 10px 18px;
    border-radius: 20px;
    text-decoration: none;
    color: white;
    background: #333;
    font-size: 14px;
}
.filter-bar a.active,
.filter-bar a:hover {
    background: #ff4d4d;
}

.counter-box {
    display: flex;
    justify-content: center;
    gap: 25px;
    margin-bottom: 30px;
}
.counter {
    background: white;
    padding: 15px 25px;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    font-size: 16px;
}
.counter b {
    font-size: 22px;
    display: block;
    margin-top: 5px;
}
</style>
</head>

<body>

<?php include 'admin_nav.php'; ?>
<div class="page-container">
    <h1 class="page-title">📦 Orders Management</h1>

    
    <div class="counter-box">
        <div class="counter">Pending<b><?php echo $countPending; ?></b></div>
        <div class="counter">Confirmed<b><?php echo $countConfirmed; ?></b></div>
        <div class="counter">Done<b><?php echo $countDone; ?></b></div>
    </div>

   
    <div class="filter-bar">
        <a href="admin_orders.php" class="<?php echo $statusFilter=='all'?'active':''; ?>">All</a>
        <a href="admin_orders.php?status=pending" class="<?php echo $statusFilter=='pending'?'active':''; ?>">Pending</a>
        <a href="admin_orders.php?status=confirmed" class="<?php echo $statusFilter=='confirmed'?'active':''; ?>">Confirmed</a>
        <a href="admin_orders.php?status=done" class="<?php echo $statusFilter=='done'?'active':''; ?>">Done</a>
    </div>

    >
    <table class="table">
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo $row['user_id']; ?></td>
            <td>$<?php echo number_format($row['total'],2); ?></td>

            <td>
                <?php if ($row['payment_method']=="Cash on Delivery"): ?>
                    <span class="payment-badge payment-cash">💵 Cash</span>
                <?php elseif ($row['payment_method']=="Wish Money"): ?>
                    <span class="payment-badge payment-wish">📱 Wish</span>
                <?php else: ?>
                    <span class="payment-badge payment-card">💳 Card</span>
                <?php endif; ?>
            </td>

            <td>
                <span class="status <?php echo $row['status']; ?>">
                    <?php echo ucfirst($row['status']); ?>
                </span>
            </td>

            <td><?php echo $row['created_at']; ?></td>

            <td>
                <a class="btn btn-dark" href="admin_order_details.php?order_id=<?php echo $row['id']; ?>">
                    View
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
