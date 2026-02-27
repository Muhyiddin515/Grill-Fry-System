<?php
session_start();
require 'connection.php';


if (!isset($_SESSION['user_id'])) {
    echo 'no session';
    exit;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    echo 'invalid request';
    exit;
}

if (!isset($connection)) {
    echo 'no connection';
    exit;
}

$id = intval($_POST['id']);
$user_id = intval($_SESSION['user_id']);


$stmt = $connection->prepare("UPDATE grill_fry_bookings SET canceled = 1 WHERE id = ? AND user_id = ?");
if (!$stmt) {
    echo 'prepare failed: ' . $connection->error;
    exit;
}

$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo 'success';
} else {
    echo 'update failed or not authorized';
}
?>
