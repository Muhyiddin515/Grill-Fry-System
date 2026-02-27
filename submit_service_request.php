<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$service_type = trim($_POST['service_type'] ?? '');
$event_date   = $_POST['event_date'] ?? null;
$time_from    = $_POST['time_from'];  
$time_to      = $_POST['time_to'];
$people       = (int)($_POST['people'] ?? 0);
$notes        = trim($_POST['notes'] ?? '');

if ($service_type === '') {
    die("Service type is required.");
}

$stmt = $connection->prepare("
    INSERT INTO service_requests
    (user_id, service_type, event_date, time_from, time_to, people, notes, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
");


$stmt->bind_param(
    "issssis",
    $_SESSION['user_id'],
    $service_type,
    $event_date,
    $time_from,
    $time_to,
    $people,
    $notes
);


$stmt->execute();
$stmt->close();

header("Location: services.php?success=1");
exit;
