<?php
session_start();
require 'connection.php';




if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}


if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];

    $stmt = $connection->prepare(
        "UPDATE images SET is_active = 1 WHERE id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>window.location='admin_gallery.php';
</script>";
    exit;

}


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $connection->prepare(
        "SELECT image_path FROM images WHERE id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $img = $stmt->get_result()->fetch_assoc();

    if ($img && file_exists($img['image_path'])) {
        unlink($img['image_path']);
    }

    $stmt = $connection->prepare(
        "DELETE FROM images WHERE id = ?"
    );
    $stmt->bind_param("i", $id);
   $stmt->execute();
    echo "<script>window.location='admin_gallery.php';
</script>";
    exit;


}


?>


<!DOCTYPE html>
<html lang="en">

    <?php include 'admin_nav.php'; ?>
<head>
<meta charset="UTF-8">
<title>Admin | Gallery Moderation</title>

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
body {
    background: wheat;
    font-family: Arial, sans-serif;
    margin: 0;
}

h2 {
    text-align: center;
    margin: 25px 0;
}

.gallery {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    padding: 25px;
}

.card {
    background: white;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,.15);
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.status {
    margin: 10px 0;
    font-weight: bold;
}

.pending {
    color: orange;
}

.approved {
    color: green;
}

.actions a {
    display: inline-block;
    padding: 8px 14px;
    margin: 5px;
    text-decoration: none;
    color: white;
    border-radius: 5px;
    font-size: 14px;
}

.approve {
    background: green;
}

.delete {
    background: red;
}
</style>
</head>

<body>

<h2>Gallery Image Moderation</h2>

<div class="gallery">
<?php
$imgs = $connection->query("
    SELECT id, image_path, is_active
    FROM images
    WHERE section = 'gallery'
    ORDER BY id DESC
");

while ($img = $imgs->fetch_assoc()):
?>
    <div class="card">
        <img src="<?= htmlspecialchars($img['image_path']) ?>">

        <div class="status <?= $img['is_active'] ? 'approved' : 'pending' ?>">
            <?= $img['is_active'] ? 'Approved' : 'Pending' ?>
        </div>

        <div class="actions">
            <?php if ($img['is_active'] == 0): ?>
                <a class="approve" href="?approve=<?= $img['id'] ?>">
                    Approve
                </a>
            <?php endif; ?>

            <a class="delete"
               href="?delete=<?= $img['id'] ?>"
               onclick="return confirm('Are you sure you want to delete this image?')">
               Delete
            </a>
        </div>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>
