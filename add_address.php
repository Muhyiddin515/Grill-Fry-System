<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['save_address'])) {

    $country   = trim($_POST['country']);
    $city      = trim($_POST['city']);
    $area      = trim($_POST['area']);
    $street    = trim($_POST['street']);
    $building  = trim($_POST['building']);
    $floor     = trim($_POST['floor']);
    $apartment = trim($_POST['apartment']);

   
    $full_address = 
        "Country: $country, City: $city, Area: $area, Street: $street, " .
        "Building: $building, Floor: $floor, Apartment: $apartment";

    $user_type = 'customer';

    $stmt = $connection->prepare("
        INSERT INTO address (user_id, user_type, address)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("iss", $user_id, $user_type, $full_address);
    $stmt->execute();
    $stmt->close();

    header("Location: select_address.php");
    exit;
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Add Address</title>
    <link rel="stylesheet" href="address.css">
    
</head>
<body>

<?php include 'nav.php'; ?>

<div class="page-container">
    <h2 class="page-title">➕ Add New Address</h2>

    <form method="POST">

        <div class="form-group">
            <input type="text" name="country" placeholder="Country" required>
        </div>

        <div class="form-group">
            <input type="text" name="city" placeholder="City" required>
        </div>

        <div class="form-group">
            <input type="text" name="area" placeholder="Area">
        </div>

        <div class="form-group">
            <input type="text" name="street" placeholder="Street">
        </div>

        <div class="form-group">
            <input type="text" name="building" placeholder="Building">
        </div>

        <div class="form-group">
            <input type="text" name="floor" placeholder="Floor">
        </div>

        <div class="form-group">
            <input type="text" name="apartment" placeholder="Apartment">
        </div>

        <button type="submit" name="save_address" class="btn">
            Save Address
        </button>

    </form>
</div>

</body>
</html>
