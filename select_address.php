<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $connection->prepare("
    SELECT * FROM address
    WHERE user_id = ? AND user_type = 'customer'
    ORDER BY id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Address</title>
    <link rel="stylesheet" href="address.css">
    <link rel="stylesheet" href="nav.css">
    
    
</head>
<body>

<?php include 'nav.php'; ?>

<div class="page-container">
    <h2 class="page-title">📍 Select Delivery Address</h2>

    <?php if ($result->num_rows == 0): ?>
        <p style="text-align:center;">No address found.</p>

        <div class="address-link">
            <a href="add_address.php">➕ Add Address</a>
        </div>

    <?php else: ?>

    <form action="payment.php" method="GET">

        <div class="form-group">
            <select name="address_id" required>
                <option value="">-- Choose Address --</option>

                <?php while($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>">
                        <?= htmlspecialchars($row['address']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn">Next</button>
    </form>

    <div class="address-link">
        <a href="add_address.php">➕ Add New Address</a>
    </div>

    <?php endif; ?>
</div>

</body>
</html>
