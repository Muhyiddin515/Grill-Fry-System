<?php
session_start();
require "connection.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    exit("Access Denied");
}

$id = $_GET['id'];

$connection->query("DELETE FROM types WHERE id=$id");

header("Location: admin_types.php");
exit;
?>
