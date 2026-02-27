<?php
session_start();
require 'connection.php';


if (!isset($_SESSION['admin_id']) && (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 16)) {
    die("Access denied");
}

if (!isset($_GET['employee_id'])) {
    die("Employee not found");
}

$employee_id = (int)$_GET['employee_id'];


$stmt = $connection->prepare("
    SELECT * FROM address
    WHERE user_id = ? AND user_type = 'employee'
");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$address = $stmt->get_result()->fetch_assoc();

if (isset($_POST['save_address'])) {

    $country   = $_POST['country'];
    $city      = $_POST['city'];
    $area      = $_POST['area'];
    $street    = $_POST['street'];
    $building  = $_POST['building'];
    $floor     = $_POST['floor'];
    $apartment = $_POST['apartment'];

    if ($address) {
       
        $stmt = $connection->prepare("
            UPDATE address SET
            country=?, city=?, area=?, street=?, building=?, floor=?, apartment=?
            WHERE user_id=? AND user_type='employee'
        ");
        $stmt->bind_param(
            "sssssssi",
            $country, $city, $area, $street, $building, $floor, $apartment, $employee_id
        );
    } else {
        
        $stmt = $connection->prepare("
            INSERT INTO address
            (user_id, user_type, country, city, area, street, building, floor, apartment)
            VALUES (?, 'employee', ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "isssssss",
            $employee_id,
            $country, $city, $area, $street, $building, $floor, $apartment
        );
    }

    $stmt->execute();
    header("Location: employees.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Address</title>
    <link rel="stylesheet" href="address.css">
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="page-container">
    <h2 class="page-title">📍 Employee Address</h2>

    <form method="POST">
        <div class="form-group">
            <input type="text" name="country" placeholder="Country"
                   value="<?= $address['country'] ?? '' ?>" required>
        </div>

        <div class="form-group">
            <input type="text" name="city" placeholder="City"
                   value="<?= $address['city'] ?? '' ?>" required>
        </div>

        <div class="form-group">
            <input type="text" name="area" placeholder="Area"
                   value="<?= $address['area'] ?? '' ?>">
        </div>

        <div class="form-group">
            <input type="text" name="street" placeholder="Street"
                   value="<?= $address['street'] ?? '' ?>">
        </div>

        <div class="form-group">
            <input type="text" name="building" placeholder="Building"
                   value="<?= $address['building'] ?? '' ?>">
        </div>

        <div class="form-group">
            <input type="text" name="floor" placeholder="Floor"
                   value="<?= $address['floor'] ?? '' ?>">
        </div>

        <div class="form-group">
            <input type="text" name="apartment" placeholder="Apartment"
                   value="<?= $address['apartment'] ?? '' ?>">
        </div>

        <button class="btn" name="save_address">Save Address</button>
    </form>
</div>

</body>
</html>
