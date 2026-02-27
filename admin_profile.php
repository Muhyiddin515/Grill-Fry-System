<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/connection.php';


if (!isset($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
    die("Access denied");
}


$admin_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

$msg = "";
$error = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: signin.html");
        exit;
    }

   
    if ($admin_id <= 0) {
        $error = "❌ Session user_id is missing. Please login again.";
    } else {

        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        
        if (!empty($_FILES['profile_image']['name'])) {

            $allowed = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = "❌ Invalid image type. Use JPG / PNG / WEBP only.";
            } else {

                $uploadDir = __DIR__ . "/uploads/admins/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $imgName = "admin_" . $admin_id . "_" . time() . "." . $ext;
                $pathFS  = $uploadDir . $imgName;
                $pathDB  = "uploads/admins/" . $imgName;

                if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $pathFS)) {
                    $error = "❌ Upload failed. Check folder permissions (uploads/admins).";
                } else {
                    $stmt = $connection->prepare("
                        UPDATE grill_fry_users
                        SET profile_image = ?
                        WHERE id = ?
                    ");
                    $stmt->bind_param("si", $pathDB, $admin_id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        if ($error === "") {
            $stmt = $connection->prepare("
                UPDATE grill_fry_users
                SET name = ?, email = ?, phone = ?
                WHERE id = ?
            ");
            $stmt->bind_param("sssi", $name, $email, $phone, $admin_id);
            $stmt->execute();
            $stmt->close();

            $msg = "✅ Profile updated successfully.";
        }
    }
}

$admin = [];

if ($admin_id <= 0) {
    $error = $error ?: "❌ Session user_id is missing. Please login again.";
} else {
    $stmt = $connection->prepare("
        SELECT id, name, email, phone, profile_image
        FROM grill_fry_users
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $adminProfile = $stmt->get_result()->fetch_assoc() ?? [];
$stmt->close();

    
}
$defaultAvatar = "/senior_project/web_projecttt/image/default-avatar.png";

if (!empty($admin['profile_image'])) {

   
    $realPath = $_SERVER['DOCUMENT_ROOT'] . "/senior_project/web_projecttt/" . $admin['profile_image'];

    if (file_exists($realPath)) {
        $avatar = "/senior_project/web_projecttt/" . $admin['profile_image'];
    } else {
        $avatar = $defaultAvatar;
    }

} else {
    $avatar = $defaultAvatar;
}




include __DIR__ . '/admin_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Profile</title>

<style>
body {
    background-color: wheat;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
}

.profile-box {
    max-width: 520px;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}

.profile-img {
    text-align: center;
    margin-bottom: 15px;
}

.profile-img img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #ff511c;
}

h2 {
    text-align: center;
    margin-top: 10px;
}

.form-group {
    margin-bottom: 14px;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
}

input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

button {
    background: #ff511c;
    color: white;
    padding: 12px;
    border: none;
    width: 100%;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    opacity: 0.95;
}

.logout-btn {
    background: #333;
    margin-top: 10px;
}

.msg {
    text-align: center;
    color: green;
    margin: 10px 0;
    font-weight: bold;
}

.err {
    text-align: center;
    color: red;
    margin: 10px 0;
    font-weight: bold;
}
</style>
</head>

<body>

<div class="profile-box">

    <div class="profile-img">
        <img src="<?= htmlspecialchars($avatar) ?>"
                 onerror="this.src='/senior_project/web_projecttt/image/default-avatar.png';">
    </div>

    <h2>Admin Profile</h2>

    <?php if ($msg): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="err"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Profile Photo</label>
            <input type="file" name="profile_image" accept="image/*">
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name"
                   value="<?= htmlspecialchars($adminProfile['name'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($adminProfile['email'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="tel" name="phone"
                   value="<?= htmlspecialchars($adminProfile['phone'] ?? '') ?>"
                   required>
        </div>

        <button type="submit">Save Changes</button>

        <button type="submit" name="logout" class="logout-btn">
            Logout
        </button>
    </form>

</div>

</body>
</html>
