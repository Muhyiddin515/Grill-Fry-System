<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $connection->prepare("
    SELECT id, total, status, payment_method, created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders - GRILL & FRY</title>

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
    <h1 class="page-title">📦 My Orders</h1>

    <?php if ($result->num_rows == 0): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>

    <table class="table">
        <tr>
            <th>Order ID</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Date</th>
            <th>Details</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td>#<?php echo $row['id']; ?></td>

            <td>$<?php echo number_format($row['total'], 2); ?></td>

            <td>
                <?php if ($row['payment_method'] == "Cash on Delivery"): ?>
                    <span class="payment-badge payment-cash">💵 Cash</span>
                <?php elseif ($row['payment_method'] == "Wish Money"): ?>
                    <span class="payment-badge payment-wish">📱 Wish</span>
                <?php elseif ($row['payment_method'] == "Card"): ?>
                    <span class="payment-badge payment-card">💳 Card</span>
                <?php else: ?>
                    <?php echo htmlspecialchars($row['payment_method']); ?>
                <?php endif; ?>
            </td>

            <td>
                <span class="status <?php echo $row['status']; ?>">
                    <?php echo ucfirst($row['status']); ?>
                </span>
            </td>

            <td><?php echo $row['created_at']; ?></td>

            <td>
                <a href="my_order_details.php?order_id=<?php echo $row['id']; ?>" class="btn btn-dark">
                    View
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <?php endif; ?>
</div>

</body>
</html>
