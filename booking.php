<?php
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grill_fry";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to make a booking.");
}

$user_id = (int)$_SESSION['user_id'];


$username = trim($_POST['username'] ?? ''); 
$booking_name = trim($_POST['booking_name'] ?? ''); 
$phone = trim($_POST['phone'] ?? ''); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $people = (int)($_POST['people'] ?? 0);
    $date = $conn->real_escape_string(trim($_POST['date'] ?? ''));
    $time = $conn->real_escape_string(trim($_POST['time'] ?? ''));
    $payment_type = $conn->real_escape_string(trim($_POST['payment_type'] ?? ''));
    $special_instructions = $conn->real_escape_string(trim($_POST['special_instructions'] ?? ''));
    $menu_selection = $_POST['menu_selection'] ?? [];

   
    if ($people > 0 && !empty($date) && !empty($time) && !empty($payment_type) && !empty($username) && !empty($phone) && is_array($menu_selection) && count($menu_selection) > 0) {

       
         $stmt = $connection->prepare("
        INSERT INTO grill_fry_bookings 
        (user_id, name, phone, people, date, time, payment_type, table_number, status, canceled) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 0)
    ");

    $stmt->bind_param(
        "ississs",
        $user_id,
        $user_name, 
        $user_phone,
        $people,
        $date,
        $time,
        $payment_type,
        $table_number
    );

   

        if ($stmt->execute()) {
            $booking_id = $conn->insert_id;

           
            if (!empty($menu_selection)) {
                $stmt_item = $conn->prepare("INSERT INTO order_item (user_id, booking_id, food_item) VALUES (?, ?, ?)");
                foreach ($menu_selection as $food_item) {
                    $food_item = trim($food_item);
                    $stmt_item->bind_param("iis", $user_id, $booking_id, $food_item);
                    $stmt_item->execute();
                }
                $stmt_item->close();
            }

            echo "<script>
                    alert('Booking and order successfully saved!');
                    window.location.href='mybooking.php';
                  </script>";
            exit;
        } else {
            die("Error saving booking: " . $stmt->error);
        }
        
    } else {
        die("Please fill all required fields and add at least one item to cart.");
    }
}

$conn->close();
?>
