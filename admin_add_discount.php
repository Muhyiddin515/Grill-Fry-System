<?php
session_start();
require 'connection.php';


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = trim($_POST['discount_name']);
    $percent = (int)$_POST['discount_percent'];
    $start   = $_POST['start_date'];
    $end     = $_POST['end_date'];

    if ($percent <= 0 || $percent > 90) {
        $msg = "❌ Discount must be between 1% and 90%.";
    } elseif ($start > $end) {
        $msg = "❌ Start date must be before end date.";
    } else {

        // 1️⃣ Insert discount
        $stmt = $connection->prepare("
            INSERT INTO global_discounts 
            (discount_name, discount_percent, start_date, end_date)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("siss", $name, $percent, $start, $end);
        $stmt->execute();
        $discount_id = $stmt->insert_id;
        $stmt->close();

        // 2️⃣ Apply to ALL items
        $connection->query("
            UPDATE menu_items
            SET discount_id = $discount_id
        ");

        $msg = "✅ Discount applied to all items successfully.";
    }
}
/* DELETE DISCOUNT */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Remove discount from items
    $connection->query("
        UPDATE menu_items 
        SET discount_id = NULL
        WHERE discount_id = $id
    ");

    // Delete discount
    $stmt = $connection->prepare("
        DELETE FROM global_discounts WHERE id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_add_discount.php");
    exit;
}
$discounts = $connection->query("
    SELECT *
    FROM global_discounts
    ORDER BY start_date DESC
");

include 'admin_nav.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Global Discount</title>
<style>
body{background:wheat;font-family:Arial;}
.box{max-width:450px;margin:50px auto;background:#fff;padding:25px;border-radius:10px;}
input,button{width:100%;padding:12px;margin:8px 0;}
button{background:#ff511c;color:#fff;border:none;}
.msg{text-align:center;font-weight:bold;}
.discount-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
}

.discount-card-pro {
    background: linear-gradient(135deg, #ffffff, #fff5ef);
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 12px 30px rgba(0,0,0,.15);
    transition: transform .25s ease, box-shadow .25s ease;
}

.discount-card-pro:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(0,0,0,.25);
}

.top-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-row h3 {
    margin: 0;
    font-size: 22px;
    letter-spacing: 1px;
}

.percent-circle {
    width: 70px;
    height: 70px;
    background: #ff511c;
    color: white;
    border-radius: 50%;
    font-size: 18px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}

.date-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 25px 0;
    color: #444;
}

.date-row small {
    color: #777;
}

.line {
    flex: 1;
    height: 2px;
    background: linear-gradient(to right, #ff511c, #ddd);
    margin: 0 15px;
}

.action-row {
    display: flex;
    justify-content: space-between;
}

.btn-edit, .btn-delete {
    padding: 10px 22px;
    border-radius: 8px;
    color: white;
    text-decoration: none;
    font-weight: 500;
}

.btn-edit {
    background: #3498db;
}

.btn-delete {
    background: #e74c3c;
}

</style>
</head>
<body>

<div class="box">
<h2>Add Discount to All Items</h2>
<?php if($msg): ?><div class="msg"><?= $msg ?></div><?php endif; ?>

<form method="post">
<input type="text" name="discount_name" placeholder="Discount Name" required>
<input type="number" name="discount_percent" min="1" max="90" placeholder="Discount %" required>
<label>Start Date</label>
<input type="date" name="start_date" required>
<label>End Date</label>
<input type="date" name="end_date" required>
<button type="submit">Apply Discount</button>
</form>
<div class="discounts-section">
    <h2>Existing Discounts</h2>

    <div class="discount-grid">

        <?php while ($d = $discounts->fetch_assoc()): ?>
        <div class="discount-card-pro">

            <div class="top-row">
                <h3><?= strtoupper(htmlspecialchars($d['discount_name'])) ?></h3>

                <div class="percent-circle">
                    <?= $d['discount_percent'] ?>%
                </div>
            </div>

            <div class="date-row">
                <div>
                    <small>Start</small>
                    <strong><?= date('d M Y', strtotime($d['start_date'])) ?></strong>
                </div>

                <div class="line"></div>

                <div>
                    <small>End</small>
                    <strong><?= date('d M Y', strtotime($d['end_date'])) ?></strong>
                </div>
            </div>

            <div class="action-row">
                <a href="admin_edit_discount.php?id=<?= $d['id'] ?>" class="btn-edit">Edit</a>
                <a href="?delete=<?= $d['id'] ?>"
                   onclick="return confirm('Delete this discount?')"
                   class="btn-delete">Delete</a>
            </div>

        </div>
        <?php endwhile; ?>

    </div>
</div>



</body>
</html>
