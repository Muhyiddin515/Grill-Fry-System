<?php
session_start();
require 'connection.php';


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}


if (!isset($_GET['id'])) {
    die("No discount selected");
}

$id = (int)$_GET['id'];
$msg = "";


$stmt = $connection->prepare("
    SELECT * FROM global_discounts WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$discount = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$discount) {
    die("Discount not found");
}


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

        $stmt = $connection->prepare("
            UPDATE global_discounts
            SET discount_name = ?, 
                discount_percent = ?, 
                start_date = ?, 
                end_date = ?
            WHERE id = ?
        ");
        $stmt->bind_param("sissi", $name, $percent, $start, $end, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: admin_add_discount.php?updated=1");
        exit;
    }
}
include 'admin_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Discount</title>

<style>
body{
    background:wheat;
    font-family:Arial, sans-serif;
}
.box{
    max-width:450px;
    margin:60px auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,.15);
}
h2{
    text-align:center;
    margin-bottom:20px;
}
input,button{
    width:100%;
    padding:12px;
    margin:10px 0;
    font-size:15px;
}
button{
    background:#3498db;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
button:hover{
    background:#2c80b4;
}
.msg{
    text-align:center;
    font-weight:bold;
    margin-bottom:10px;
}
label{
    font-weight:bold;
}
</style>
</head>

<body>

<div class="box">
<h2>Edit Global Discount</h2>

<?php if($msg): ?>
    <div class="msg"><?= $msg ?></div>
<?php endif; ?>

<form method="post">

<input type="text" 
       name="discount_name"
       value="<?= htmlspecialchars($discount['discount_name']) ?>"
       required>

<input type="number" 
       name="discount_percent" 
       min="1" 
       max="90"
       value="<?= $discount['discount_percent'] ?>"
       required>

<label>Start Date</label>
<input type="date" 
       name="start_date"
       value="<?= $discount['start_date'] ?>"
       required>

<label>End Date</label>
<input type="date" 
       name="end_date"
       value="<?= $discount['end_date'] ?>"
       required>

<button type="submit">Update Discount</button>

</form>
</div>

</body>
</html>
