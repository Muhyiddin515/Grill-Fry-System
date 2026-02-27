<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['address_id'])) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$address_id = (int)$_POST['address_id'];
$payment_method = $_POST['payment_method'] ?? 'Cash on Delivery';

$stmt = $connection->prepare("SELECT * FROM cart WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Cart is empty.");
}

$total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total += $row['price'];
}

$voucherImage = null;
if ($payment_method === "Wish Money" && isset($_FILES['voucher']) && $_FILES['voucher']['error'] == 0) {
    $dir = "uploads/vouchers/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $voucherImage = time() . "_" . basename($_FILES['voucher']['name']);
    move_uploaded_file($_FILES['voucher']['tmp_name'], $dir . $voucherImage);
}


$stmt_order = $connection->prepare("
    INSERT INTO orders 
    (user_id, total, status, payment_method, voucher_image, address_id)
    VALUES (?, ?, 'pending', ?, ?, ?)
");
$stmt_order->bind_param(
    "idssi",
    $user_id,
    $total,
    $payment_method,
    $voucherImage,
    $address_id
);
$stmt_order->execute();
$order_id = $stmt_order->insert_id;


$stmt_item = $connection->prepare("
    INSERT INTO order_items (order_id, item_name, quantity, price)
    VALUES (?, ?, ?, ?)
");

foreach ($items as $item) {
    $stmt_item->bind_param(
        "isid",
        $order_id,
        $item['item_name'],
        $item['quantity'],
        $item['price']
    );
    $stmt_item->execute();
}


$stmt_clear = $connection->prepare("DELETE FROM cart WHERE user_id=?");
$stmt_clear->bind_param("i", $user_id);
$stmt_clear->execute();

header("Location: order_success.php?order_id=".$order_id);
exit;
