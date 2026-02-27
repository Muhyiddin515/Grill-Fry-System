<?php
session_start();
require "connection.php";

if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    echo "Access denied.";
    exit;
}

$msg = "";


$types = $connection->query("SELECT * FROM types ORDER BY type_name ASC");


if(isset($_POST['add_item'])){

    $type_id = $_POST['type_id'];
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    
    $image_name = time() . "_" . $_FILES['image']['name'];
    $target = "uploads/items/" . $image_name;

    if(!is_dir("uploads/items")){
        mkdir("uploads/items", 0777, true);
    }

    if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){

        $stmt = $connection->prepare("
            INSERT INTO menu_items (type_id, item_name, description, price, image)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("issds", $type_id, $item_name, $description, $price, $target);
        $stmt->execute();
        $stmt->close();

        $msg = "Item added successfully!";
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
    <title>Add Item - Admin</title>

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

        
        .active {
            color: #ff511c;
            font-weight: bold;
        }

        .active::after {
            content: '';
            width: 50%;
            height: 3px;
            background: #ff511c;
            display: block;
            margin: 3px auto 0;
            border-radius: 2px;
        }

        
        .container {
            width: 500px;
            margin: 60px auto;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 10px #00000030;
        }

        h2 {
            color: #ff511c;
            text-align: center;
        }

        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ff511c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 17px;
        }

        .msg {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            color: green;
        }

        .back {
            text-align: center;
            margin-top: 15px;
        }

        .back a {
            text-decoration: none;
            color: #ff511c;
            font-weight: bold;
        }
    </style>
</head>

<body>





<div class="container">

    <h2>Add New Item</h2>

    <?php if($msg != ""): ?>
        <p class="msg"><?= $msg ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Select Type:</label>
        <select name="type_id" required>
            <option value="">-- Select Type --</option>
            <?php while($t = $types->fetch_assoc()): ?>
                <option value="<?= $t['id'] ?>"><?= $t['type_name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Item Name:</label>
        <input type="text" name="item_name" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Upload Image:</label>
        <input type="file" name="image" required>

        <button type="submit" name="add_item">Add Item</button>
    </form>

    <div class="back">
        <a href="items.php">← Back to items</a>
    </div>

</div>

</body>
</html>
