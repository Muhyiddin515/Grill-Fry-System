<?php
session_start();
require 'connection.php';

if(!isset($_SESSION['user_id'])) exit;

$msg="";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $old = $_POST['old'];
    $new = password_hash($_POST['new'], PASSWORD_DEFAULT);

    $stmt = $connection->prepare("
        SELECT password FROM grill_fry_users WHERE id=?
    ");
    $stmt->bind_param("i",$_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if(password_verify($old,$res['password'])){
        $up = $connection->prepare("
            UPDATE grill_fry_users SET password=? WHERE id=?
        ");
        $up->bind_param("si",$new,$_SESSION['user_id']);
        $up->execute();
        $msg="Password changed successfully";
    } else {
        $msg="Old password incorrect";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="profile_style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="profile-box">
<h2>Change Password</h2>

<form method="POST">
<input type="password" name="old" placeholder="Old Password" required>
<input type="password" name="new" placeholder="New Password" required>
<button>Change</button>
<p class="msg"><?= $msg ?></p>
</form>

<a href="profile.php">⬅ Back</a>
</div>
<style>
   
*,::after , ::before{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  

}

body{
    margin: 0 100px;
    background-color:wheat;
}
.nav{
    width: 100%;
display: flex;
justify-content: space-between;
align-items: center;
padding: 30px 0;
}

.nav .logo h1{
    font-weight: 600;
    font-family: sans-serif;
    color: #000000;
}

.nav .logo b{
    color: #ff511c;

}

.nav ul{
    display: flex;
    list-style: none;
}
.nav ul li{
    margin-right:30px;
}
.nav ul li a{
    text-decoration: none;
    color:#000000;
    font-weight: 500;
    font-family: sans-serif;
    font-size:17px ;
}

.nav ul .active::after{
    content: '';
    width: 50%;
    height: 3px;
    background-color: #ff511c;
    display: flex;
    position: relative;
    margin-left: 10px;
}


input{
    padding: 10px 20px;
    cursor: pointer;
    font-weight:600 ;
}

.profile-box {
    background: #ffffff;
    width: 420px;
    max-width: 92%;
    padding: 35px 40px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    animation: fadeUp 0.5s ease;
}


.profile-box h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 26px;
    color: #222;
}


.profile-box form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}


.profile-box input[type="text"],
.profile-box input[type="password"] {
    width: 100%;
    padding: 14px 16px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 15px;
    transition: all 0.25s ease;
}

.profile-box input:focus {
    outline: none;
    border-color: #ff511c;
    box-shadow: 0 0 0 3px rgba(255,81,28,0.15);
}


.profile-box input[type="file"] {
    border: 1px dashed #ccc;
    padding: 12px;
    border-radius: 10px;
    cursor: pointer;
    background: #fafafa;
}

.profile-box input[type="file"]::-webkit-file-upload-button {
    background: #ff511c;
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    margin-right: 10px;
}

.profile-box input[type="file"]::-webkit-file-upload-button:hover {
    background: #e34714;
}


.profile-box button {
    margin-top: 10px;
    padding: 14px;
    font-size: 16px;
    font-weight: 600;
    background: linear-gradient(135deg, #ff511c, #ff7845);
    color: white;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: transform .2s ease, box-shadow .2s ease;
}

.profile-box button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255,81,28,0.4);
}


.msg {
    margin-top: 10px;
    text-align: center;
    color: #1a8f1a;
    font-weight: 500;
}


.profile-box a {
    display: block;
    text-align: center;
    margin-top: 20px;
    text-decoration: none;
    color: #6b21a8;
    font-weight: 500;
    transition: color 0.2s ease;
}

.profile-box a:hover {
    color: #4c1d95;
    text-decoration: underline;
}


@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


@media (max-width: 480px) {
    .profile-box {
        padding: 25px;
    }

    .profile-box h2 {
        font-size: 22px;
    }
}
</style>
</body>
</html>
