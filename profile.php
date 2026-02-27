<?php
session_start();
require 'connection.php';

if(!isset($_SESSION['user_id'])){
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $connection->prepare("
    SELECT name, email, phone, avatar, created_at
    FROM grill_fry_users
    WHERE id = ?
");


$stmt->bind_param("i",$user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>My Profile - Grill & Fry</title>
<link rel="stylesheet" href="profile_style.css">
</head>

<body>
<section class="Menu">
        <?php include 'nav.php'; ?>

    </section>
<div class="profile-box">
    


  <div class="profile-header">
    <img src="<?= $user['avatar'] ? 'uploads/avatars/'.$user['avatar'] : 'default.png' ?>">
    <h2><?= htmlspecialchars($user['name']) ?></h2>
    <p><?= htmlspecialchars($user['email']) ?></p>
  </div>

  <div class="profile-actions">
    <a href="edit_profile.php">Edit Profile</a>
    <a href="change_password.php">Change Password</a>
    <a href="my_orders.php">My Orders</a>
    <a href="my_bookings.php">My Bookings</a>
  </div>

  <div class="profile-info">
    <p><b>Phone:</b> <?= $user['phone'] ?></p>
    <p><b>Member Since:</b> <?= $user['created_at'] ?></p>
  </div>

</div>

</body>
</html>
