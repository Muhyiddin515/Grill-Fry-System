<?php
session_start();
require_once 'connection.php';


if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "Access denied.";
    exit;
}


$types = $connection->query(query: "SELECT * FROM types ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
    <?php include 'admin_nav.php'; ?>
<head>
    <title>Admin - Menu</title>

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
            text-align: center;
            color: #ff4d4d;
            margin-top: 0;
        }

        .type-box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 0 10px #00000040;
        }

        .type-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .type-header img {
            width: 150px;
            height: 120px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #ff511c;
        }

        .add-btn {
            background: #ff511c;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            font-weight: bold;
        }

        .add-btn:hover{
            background:#e04416;
        }

        .items-grid {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .item-card{
            background:#fffaf1;
            padding:15px;
            border-radius:10px;
            box-shadow:0 2px 6px #00000025;
        }

        .item-card img{
            width:100%;
            height:150px;
            object-fit:cover;
            border-radius:10px;
        }

        .item-card h3{
            margin:10px 0 5px;
        }

        .item-card p{
            margin:3px 0;
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


    </style>
</head>

<body>
<header>
</header>



<h2>Admin Menu Management</h2>


<?php while($type = $types->fetch_assoc()): ?>

    <div class="type-box">

        <div class="type-header">
            <img src="<?= $type['image'] ?>" alt="">
            <h2 style="color:black;"><?= $type['type_name'] ?></h2>
        </div>

        <a href="admin_add_item.php?type_id=<?= $type['id'] ?>" class="add-btn">
            ➕ Add Item to <?= $type['type_name'] ?>
        </a>

        <?php
            $items = $connection->query("SELECT * FROM menu_items WHERE type_id=" . $type['id']);
        ?>

        <div class="items-grid">
            <?php while($item = $items->fetch_assoc()): ?>
                <div class="item-card">
                    <img src="<?= $item['image'] ?>">
                    <h3><?= $item['item_name'] ?></h3>
                    <p><?= $item['description'] ?></p>
                    <p><b>Price: $<?= $item['price'] ?></b></p>
                </div>
            <?php endwhile; ?>
        </div>

    </div>

<?php endwhile; ?>

</body>
</html>
