<?php
session_start();
require "connection.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    exit("Access Denied");
}

$id = $_GET['id'];

$type = $connection->query("SELECT * FROM types WHERE id=$id")->fetch_assoc();
$msg = "";

if (isset($_POST['save'])) {

    $name = $_POST['type_name'];

    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . $_FILES['image']['name'];
        $target = "uploads/types/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        $new_image = $target;
    } else {
        $new_image = $type['image'];
    }

    $stmt = $connection->prepare("UPDATE types SET type_name=?, image=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $new_image, $id);
    $stmt->execute();
    $stmt->close();

    $msg = "Updated!";
}
?>

<!DOCTYPE html>
<html>
    <?php include 'admin_nav.php'; ?>
<head>
    <title>Edit Type</title>
</head>
<body style="background-color: wheat;;font-family:Arial;">
<style>
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


<div class="container" style="width:450px;margin:40px auto;background:white;padding:20px;border-radius:12px;">

<h2>Edit Type</h2>

<?php if($msg): ?>
<p style="color:green;text-align:center;"><?= $msg ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="type_name" value="<?= $type['type_name'] ?>" required>

    <label>Current Image:</label><br>
    <img src="<?= $type['image'] ?>" width="200"><br><br>

    <label>New Image (optional):</label>
    <input type="file" name="image">

    <button name="save" style="margin-top:10px;padding:12px;background:#ff511c;color:white;border:none;border-radius:6px;">Save</button>
</form>
<div class="back">
            <a href="admin_menu.php">← Back to menu </a>
        </div>
</div>

</body>
</html>
