<?php
session_start();
require 'connection.php';

// Admin only
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}


$sql = "
SELECT id, user_id, total, payment_method, status, created_at
FROM orders
WHERE 
    payment_method IN ('Wish Money','Card','Cash on Delivery')
    AND status = 'done'
ORDER BY created_at DESC
";

$result = $connection->query($sql);

$totalMoney = 0;
$orders = [];

while ($row = $result->fetch_assoc()) {
    $totalMoney += $row['total'];
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin - Manage Money</title>

  <link rel="stylesheet" href="order_style.css">

  <style>
    body {
      background-color: wheat;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #000;
      margin-top: 20px;
    }

    .money-box {
      max-width: 400px;
      margin: 30px auto;
      background: #28a745;
      color: white;
      padding: 25px;
      border-radius: 20px;
      text-align: center;
      font-size: 22px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    table {
      width: 90%;
      margin: 30px auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }

    th {
      background: #ff511c;
      color: white;
    }

    .payment-badge {
      padding: 6px 14px;
      border-radius: 20px;
      color: white;
      font-size: 14px;
      display: inline-block;
    }

    .payment-wish { background: purple; }
    .payment-card { background: #007bff; }

    .view-btn {
      padding: 6px 14px;
      background: #333;
      color: white;
      border-radius: 6px;
      text-decoration: none;
    }

    .view-btn:hover {
      background: #000;
    }
  </style>
</head>

<body>

<?php include 'admin_nav.php'; ?>

<h2>💰 Money Management</h2>


<div class="money-box">
    Total Collected<br>
    <b>$<?php echo number_format($totalMoney,2); ?></b>
</div>


<table>
    <tr>
        <th>Order ID</th>
        <th>User ID</th>
        <th>Total</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Date</th>
        <th>Details</th>
    </tr>

    <?php if (count($orders) == 0): ?>
        <tr>
            <td colspan="7">No paid orders yet.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($orders as $row): ?>
        <tr>
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo $row['user_id']; ?></td>
            <td>$<?php echo number_format($row['total'],2); ?></td>

            <td>
<?php if ($row['payment_method'] == "Wish Money"): ?>
    <span class="payment-badge payment-wish">📱 Wish</span>

<?php elseif ($row['payment_method'] == "Card"): ?>
    <span class="payment-badge payment-card">💳 Card</span>

<?php else: ?>
    <span class="payment-badge" style="background:#28a745;">💵 Cash</span>
<?php endif; ?>
</td>


            <td><?php echo ucfirst($row['status']); ?></td>
            <td><?php echo $row['created_at']; ?></td>

            <td>
                <a class="view-btn" href="admin_order_details.php?order_id=<?php echo $row['id']; ?>">
                    View
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

</body>
</html>
