<?php
session_start();
require 'connection.php';
include 'nav.php';

/* =========================
   HANDLE IMAGE UPLOAD
========================= */
if (isset($_POST['upload_image']) && isset($_SESSION['user_id'])) {

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {

            if (!is_dir("uploads/gallery")) {
                mkdir("uploads/gallery", 0777, true);
            }

            $imageName = time() . "_" . rand(1000,9999) . "." . $ext;
            $path = "uploads/gallery/" . $imageName;

            move_uploaded_file($_FILES['image']['tmp_name'], $path);

            $title = "Customer Image";

            $stmt = $connection->prepare("
                INSERT INTO images (title, image_path, section, is_active)
                VALUES (?, ?, 'gallery', 0)
            ");
            $stmt->bind_param("ss", $title, $path);
            $stmt->execute();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Foodio Gallery</title>
<link rel="stylesheet" href="gallery.css">

<style>
    
.picture {
    text-align: center;
    margin: 30px 0;
    color: #ff511c;
}
.picture button {
    background: #ff511c;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.gallery {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    padding: 20px;
}
.gallery-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 10px;
}
.note {
    text-align: center;
    font-size: 14px;
    color: gray;
}
</style>
</head>

<body>

<section class="Menu">

    <!-- ✅ Upload button (same place as your original) -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="picture">
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <br><br>
            <button type="submit" name="upload_image">
                Upload Your Picture here!
            </button>
        </form>
        <p class="note">Image will appear after admin approval</p>
    </div>
    <?php endif; ?>

    <!-- ✅ Gallery Images FROM DATABASE -->
    <div class="gallery">
        <?php
        $imgs = $connection->query("
            SELECT image_path 
            FROM images 
            WHERE section = 'gallery' AND is_active = 1
            ORDER BY id DESC
        ");

        while ($img = $imgs->fetch_assoc()):
        ?>
            <img src="<?= htmlspecialchars($img['image_path']) ?>" 
                 class="gallery-image">
        <?php endwhile; ?>
    </div>

</section>

</body>
</html>
