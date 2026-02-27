<?php
session_start();
require "connection.php";

if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    echo "Access denied.";
    exit;
}

$query = "
    SELECT menu_items.*, types.type_name 
    FROM menu_items 
    JOIN types ON menu_items.type_id = types.id
    ORDER BY types.type_name, menu_items.item_name
";
$items = $connection->query($query);

if(isset($_GET['toggle'])){
    $id = intval($_GET['toggle']);
    
    $connection->query("
        UPDATE menu_items 
        SET is_active = IF(is_active = 1, 0, 1)
        WHERE id = $id
    ");

    header("Location: items.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'admin_nav.php'; ?>
    <meta charset="UTF-8">
    <title>Admin - Items Management</title>

    <style>
        body {
            background-color: wheat;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px #00000040;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #ff511c;
            color: white;
        }

        .add-btn {
            display: block;
            width: 180px;
            margin: 10px auto;
            padding: 10px;
            background: black;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 6px;
            font-weight: bold;
        }

        img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }

        .active-btn { background: red; }
        .inactive-btn { background: green; }
        .edit-btn {
    background: #0bb327ff;
    margin-right: 5px;
}

    </style>
</head>

<body>

<h2 style="text-align:center;">Admin Items Management</h2>

<a class="add-btn" href="admin_add_item.php">+ Add New Item</a>

<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Item Name</th>
            <th>Type</th>
            <th>Description</th>
            <th>Price ($)</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php while($row = $items->fetch_assoc()): ?>
            <tr style="<?= $row['is_active'] ? '' : 'opacity:0.5;' ?>">

                <td><img src="<?= $row['image'] ?>"></td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= htmlspecialchars($row['type_name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= $row['price'] ?></td>

                <!-- STATUS -->
                <td>
                    <?php if($row['is_active']): ?>
                        <span style="color: green; font-weight:bold;">Active</span>
                    <?php else: ?>
                        <span style="color: red; font-weight:bold;">Not Active</span>
                    <?php endif; ?>
                </td>

                
                <td>
                    <a class="btn edit-btn" href="admin_edit_item.php?id=<?= $row['id'] ?>">
        Edit
    </a>

                    <a class="btn <?= $row['is_active'] ? 'active-btn' : 'inactive-btn' ?>"
                       href="items.php?toggle=<?= $row['id'] ?>">
                        <?= $row['is_active'] ? 'Deactivate' : 'Activate' ?>
                    </a>
                </td>
                


            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
