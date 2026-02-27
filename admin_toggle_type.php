<?php
session_start();
require "connection.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    exit("Access denied");
}

$id = intval($_GET['id']);

$connection->query("
    UPDATE types 
    SET is_active = IF(is_active = 1, 0, 1)
    WHERE id = $id
");

header("Location: admin_types.php");
exit;
?>
