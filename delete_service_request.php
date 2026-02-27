<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = (int)($_GET['id'] ?? 0);


$stmt = $connection->prepare("
    DELETE FROM service_requests
    WHERE id = ? AND user_id = ? AND status = 'pending'
");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$stmt->close();

header("Location: my_service_requests.php");
exit;
