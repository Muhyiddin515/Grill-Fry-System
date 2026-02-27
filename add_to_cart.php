<?php
session_start();
require 'connection.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','message'=>'Not logged in']);
    exit;
}


$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);

$item_name = $data['item_name'];
$type      = $data['type'];
$quantity  = (int)$data['quantity'];
$unit      = (float)$data['price']; 
$note = trim($data['special_note'] ?? '');

$total = $unit * $quantity;

$stmt = $connection->prepare("
    INSERT INTO cart 
(user_id, item_name, special_note, type, quantity, unit_price, price)
VALUES (?, ?, ?, ?, ?, ?, ?)

");
$stmt->bind_param("isssidd",
    $user_id,
    $item_name,
    $note,
    $type,
    $quantity,
    $unit,
    $total
);

$stmt->execute();

?>
