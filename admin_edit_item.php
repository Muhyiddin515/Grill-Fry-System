<?php
session_start();
require "connection.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}

if (!isset($_GET['id'])) {
    die("No item selected");
}

$id = (int)$_GET['id'];


$stmt = $connection->prepare("
    SELECT * FROM menu_items WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    die("Item not found");
}


$types = $connection->query("SELECT * FROM types WHERE is_active = 1");


if (isset($_POST['update_item'])) {

    $name  = $_POST['item_name'];
    $desc  = $_POST['description'];
    $price = $_POST['price'];
    $type  = $_POST['type_id'];

    $image = $item['image'];

    if (!empty($_FILES['image']['name'])) {
        $image = "uploads/items/" . time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $stmt = $connection->prepare("
        UPDATE menu_items
        SET item_name = ?, description = ?, price = ?, type_id = ?, image = ?
        WHERE id = ?
    ");
    $stmt->bind_param("ssdisi", $name, $desc, $price, $type, $image, $id);
    $stmt->execute();

    header("Location: items.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Item</title>
</head>
<body>


<style>
    body {
    background-color: wheat;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}


h2 {
    text-align: center;
    color: #ff511c;
    margin-top: 30px;
}

.edit-container {
    width: 55%;
    margin: 30px auto;
    background: #fff;
    padding: 25px 30px;
    border-radius: 14px;
    box-shadow: 0 0 15px rgba(0,0,0,0.15);
}


.form-group {
    margin-bottom: 18px;
}

.form-group label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
    color: #333;
}


.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
}

.form-group textarea {
    resize: vertical;
    min-height: 90px;
}


.current-image {
    margin-top: 8px;
}

.current-image img {
    width: 120px;
    border-radius: 10px;
    border: 1px solid #ddd;
}


.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
}

.btn {
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: bold;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

.btn-save {
    background: #ff511c;
    color: white;
}

.btn-save:hover {
    background: #e64916;
}

.btn-back {
    background: #333;
    color: white;
}

.btn-back:hover {
    background: #000;
}


@media (max-width: 768px) {
    .edit-container {
        width: 90%;
    }
}

</style>

<h2>Edit Item</h2>

<div class="edit-container">
<form method="POST" enctype="multipart/form-data">

    <div class="form-group">
        <label>Item Name</label>
        <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" required><?= htmlspecialchars($item['description']) ?></textarea>
    </div>

    <div class="form-group">
        <label>Price ($)</label>
        <input type="number" step="0.01" name="price" value="<?= $item['price'] ?>" required>
    </div>

    <div class="form-group">
        <label>Type</label>
        <select name="type_id">
            <?php while($t = $types->fetch_assoc()): ?>
                <option value="<?= $t['id'] ?>" <?= $t['id'] == $item['type_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['type_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Current Image</label>
        <div class="current-image">
            <img src="<?= $item['image'] ?>">
        </div>
        <input type="file" name="image">
    </div>

    <div class="form-actions">
        <a href="items.php" class="btn btn-back">← Back</a>
        <button type="submit" name="update_item" class="btn btn-save">Save Changes</button>
    </div>

</form>
</div>


</body>
</html>
