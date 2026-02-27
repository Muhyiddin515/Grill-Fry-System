<?php
session_start();
require 'connection.php'; 

// Check if voice text was sent
if (!isset($_POST['voiceText']) || empty($_POST['voiceText'])) {
    die("No voice text received.");
}


if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to place an order.");
}
$user_id = $_SESSION['user_id'];

$voiceText = strtolower(trim($_POST['voiceText']));




$menu_items = [
    "shrimpy pizza", "vegi pizza", "multi pizza", "mushroom pizza",
    "classic burger", "grilled chicken burger", "beef burger", "fillet burger"
];

$orders_detected = [];


foreach ($menu_items as $item) {
    if (strpos($voiceText, $item) !== false) {
        
        $pattern = '/(\d+)\s*(?:x|pieces|piece|orders?|times?)?\s*' . preg_quote($item, '/') . '/';
        if (preg_match($pattern, $voiceText, $matches)) {
            $qty = intval($matches[1]);
        } else {
           
            $qty = 1;
        }

        $orders_detected[] = ["item" => $item, "qty" => $qty];
    }
}


if (empty($orders_detected)) {
    die("Sorry, we couldn’t recognize any menu items in your voice message.");
}


date_default_timezone_set('Asia/Beirut');
$today = date('Y-m-d');
$timeNow = date('H:i:s');

$name = "Voice Order";
$phone = "00000000";
$people = 1;

$insert_booking = $conn->prepare("
    INSERT INTO grill_fry_bookings (name, phone, people, date, time, user_id, canceled, status)
    VALUES (?, ?, ?, ?, ?, ?, 0, 'pending')
");
$insert_booking->bind_param("ssissi", $name, $phone, $people, $today, $timeNow, $user_id);
$insert_booking->execute();
$booking_id = $insert_booking->insert_id;


$insert_food = $conn->prepare("INSERT INTO food_orders (user_id, booking_id, food_item) VALUES (?, ?, ?)");
foreach ($orders_detected as $order) {
    for ($i = 0; $i < $order['qty']; $i++) {
        $insert_food->bind_param("iis", $user_id, $booking_id, $order['item']);
        $insert_food->execute();
    }
}


$response = "✅ Your order was placed successfully!\n";
$response .= "Booking ID: {$booking_id}\nItems:\n";
foreach ($orders_detected as $order) {
    $response .= "- {$order['qty']} × " . ucfirst($order['item']) . "\n";
}

echo nl2br($response); 
?>
