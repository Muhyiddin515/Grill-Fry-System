<?php
session_start();
require "connection.php";


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "Access denied";
    exit;
}

$result = $connection->query("SELECT * FROM types ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<?php include 'admin_nav.php'; ?>
<head>
    <title>Admin Types</title>
    <style>
        body {
           background-color: wheat;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #ff4d4d;
            margin-top: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px #00000040;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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

        img {
            width: 120px;
            border-radius: 8px;
        }

        .btn {
            padding: 7px 12px;
            text-decoration: none;
            color: white;
            border-radius: 6px;
            font-size: 14px;
        }

        .add { background: #28a745; }
        .edit { background: #007bff; }

        .toggle {
            background: #ff9900;
        }

        .active-label {
            color: green;
            font-weight: bold;
        }

        .inactive-label {
            color: red;
            font-weight: bold;
        }

        .back {
            margin-top: 15px;
            text-align: center;
        }

        .back a {
            color: #ff511c;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>All Menu Types</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Type Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php while($type = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $type['id'] ?></td>

                <td><img src="<?= $type['image'] ?>"></td>

                <td><?= htmlspecialchars($type['type_name']) ?></td>

                <td>
                    <?php if ($type['is_active'] == 1): ?>
                        <span class="active-label">Active</span>
                    <?php else: ?>
                        <span class="inactive-label">Not Active</span>
                    <?php endif; ?>
                </td>

                <td>
                    <a class="btn add" href="admin_add_item.php?type_id=<?= $type['id'] ?>">Add Item</a>
                    <a class="btn edit" href="admin_edit_type.php?id=<?= $type['id'] ?>">Edit</a>

                    
                    <a class="btn toggle"
                       href="admin_toggle_type.php?id=<?= $type['id'] ?>"
                       onclick="return confirm('Change status for this type?')">
                       <?= ($type['is_active'] == 1 ? 'Deactivate' : 'Activate') ?>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="back">
        <a href="admin_menu.php">← Back to menu</a>
    </div>
</div>

</body>
</html>
