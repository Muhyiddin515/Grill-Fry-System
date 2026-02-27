<?php
session_start();
require "connection.php";


if(!isset($_SESSION['admin_id'])){
    
}

$msg = "";

if(isset($_POST['add_type'])){

    $type_name = $_POST['type_name'];

   
    $image_name = time() . "_" . $_FILES['image']['name'];
    $target = "uploads/types/" . $image_name;

    if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){

       
        $stmt = $connection->prepare("INSERT INTO types (type_name, image) VALUES (?, ?)");
        $stmt->bind_param("ss", $type_name, $target);
        $stmt->execute();
        $stmt->close();

        $msg = "Type added successfully!";
    } else {
        $msg = "Error uploading image!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'admin_nav.php'; ?>
<head>
    <meta charset="UTF-8">
    <title>Add Menu Type - Admin</title>
    <style>
                body {
            background-color: wheat;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
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
            font-size:17px;
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

      

        h2 {
            text-align:center;
            color:  #ff4d4d;
            margin-top: 0;
        }


        .container {
            width: 450px;
            margin: 60px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
        }


        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #ff511c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
     header {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 10px;
    }

    .logout-button {
      background-color: #f57c00;
      color: white;
      border: none;
      padding: 10px 18px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 4px;
      transition: background-color 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .logout-button:hover {
      background-color: #d96600;
    }

        button:hover {
            background: #e04416;
        }

        .msg {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            color: green;
            font-weight: bold;
        }

        .back {
            text-align: center;
            margin-top: 15px;
        }

        .back a {
            color: #ff511c;
            text-decoration: none;
            font-weight: bold;
        }
    </style>

</head>
<body>
   
<header>
    
</header>

    <div class="container">
        <h2>Add New Menu Type</h2>

        <?php if($msg != ""): ?>
            <p class="msg"><?= $msg ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <label>Type Name:</label>
            <input type="text" name="type_name" placeholder="e.g. Pasta, Sandwich..." required>

            <label>Choose Image:</label>
            <input type="file" name="image" required>

            <button type="submit" name="add_type">Add Type</button>
        </form>

        <div class="back">
            <a href="admin_menu.php">← Back to menu </a>
        </div>
    </div>

</body>
</html>
