<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}


$adminName = "Admin";

$stmt = $connection->prepare("
    SELECT name 
    FROM grill_fry_users 
    WHERE id = ? 
    LIMIT 1
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if ($row && !empty($row['name'])) {
    $adminName = $row['name'];
}




$ordersToday = 0;
$q = $connection->query("
    SELECT COUNT(*) AS total 
    FROM orders 
    WHERE DATE(created_at) = CURDATE()
");
if ($q) {
    $ordersToday = (int)$q->fetch_assoc()['total'];
}


$bookingsToday = 0;
$q = $connection->query("
    SELECT COUNT(*) AS total 
    FROM grill_fry_bookings 
    WHERE date = CURDATE() 
      AND canceled = 0
");
if ($q) {
    $bookingsToday = (int)$q->fetch_assoc()['total'];
}


$commentsToday = 0;
$q = $connection->query("
    SELECT COUNT(*) AS total 
    FROM reviews 
    WHERE DATE(created_at) = CURDATE()
");
if ($q) {
    $commentsToday = (int)$q->fetch_assoc()['total'];
}

if (isset($_GET['toggle_id'])) {
    $id = (int)$_GET['toggle_id'];
    $connection->query("
        UPDATE images 
        SET is_active = IF(is_active=1,0,1) 
        WHERE id = $id
    ");
    header("Location: admin_dashboard.php");
    exit;
}


if (isset($_POST['add_image'])) {

    $title   = trim($_POST['title']);
    $section = 'our_menu';

    if (!empty($_FILES['image']['name'])) {
        $imgName = time().'_'.$_FILES['image']['name'];
        $path = "uploads/items/".$imgName;

        move_uploaded_file($_FILES['image']['tmp_name'], $path);

       $stmt = $connection->prepare("
    INSERT INTO images (title, image_path, section, is_active, position)
    VALUES (?, ?, ?, 1, (
        SELECT IFNULL(MAX(position), 0) + 1 FROM images
    ))
");
$stmt->bind_param("sss", $title, $path, $section);
$stmt->execute();

    }

    header("Location: admin_dashboard.php");
    exit;
}


if (isset($_POST['edit_image'])) {

    $id    = (int)$_POST['image_id'];
    $title = trim($_POST['title']);

    if (!empty($_FILES['image']['name'])) {
        $imgName = time().'_'.$_FILES['image']['name'];
        $path = "uploads/items/".$imgName;
        move_uploaded_file($_FILES['image']['tmp_name'], $path);

        $stmt = $connection->prepare("
            UPDATE images SET title=?, image_path=? WHERE id=?
        ");
        $stmt->bind_param("ssi", $title, $path, $id);
    } else {
        $stmt = $connection->prepare("
            UPDATE images SET title=? WHERE id=?
        ");
        $stmt->bind_param("si", $title, $id);
    }

    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}


$images = $connection->query("
    SELECT * FROM images
    WHERE section='our_menu'
    ORDER BY position ASC
");


if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['reorder'])) {
        foreach ($data['reorder'] as $img) {
            $stmt = $connection->prepare(
                "UPDATE images SET position=? WHERE id=?"
            );
            $stmt->bind_param("ii", $img['position'], $img['id']);
            $stmt->execute();
        }
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<style>
body{
    background-color: wheat;
    margin: 0;
    font-family: Arial, sans-serif;
}

.dashboard-container{
    max-width: 950px;
    margin: 60px auto;
    background: #fff;
    padding: 45px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,.15);
    text-align: center;
}

.dashboard-container h1{
    font-size: 34px;
    margin-bottom: 8px;
}

.subtitle{
    color: #555;
    margin-bottom: 30px;
    font-size: 15px;
}

/* Voice Hint */
.voice-hint-box{
    background: #fff5ef;
    border-radius: 14px;
    padding: 22px;
    margin: 25px auto;
    max-width: 760px;
    text-align: left;
    box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}

.voice-hint-box h3{
    margin: 0 0 10px 0;
    color: #ff511c;
    font-size: 18px;
}

.voice-hint-box p{
    margin: 0;
    color: #333;
    line-height: 1.6;
}

/* Today's Activity */
.activity-box{
    background: #fff;
    border-radius: 14px;
    padding: 25px;
    margin: 0 auto;
    max-width: 760px;
    text-align: left;
    border: 1px solid #eee;
}

.activity-box h3{
    margin-bottom: 15px;
    font-size: 18px;
}

.activity-list{
    list-style: none;
    padding: 0;
    margin: 0;
}

.activity-list li{
    display: flex;
    justify-content: space-between;
    padding: 12px 10px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 16px;
}

.activity-list li:last-child{
    border-bottom: none;
}

.badge{
    background: #ff511c;
    color: white;
    padding: 4px 14px;
    border-radius: 999px;
    font-weight: bold;
    min-width: 60px;
    text-align: center;
}
/* Image Management */
.image-box{
    background:#fff;
    border-radius:14px;
    padding:25px;
    margin:30px auto 0;
    max-width:760px;
    border:1px solid #eee;
}

.image-box h3{
    margin-bottom:15px;
    font-size:18px;
}

.image-grid{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:18px;
}

.image-card{
    border:1px solid #ddd;
    border-radius:12px;
    padding:10px;
    text-align:center;
}

.image-card img{
    width:100%;
    height:120px;
    object-fit:cover;
    border-radius:8px;
}

.image-card p{
    margin:8px 0;
    font-weight:bold;
}

.image-actions a{
    display:inline-block;
    margin:4px;
    padding:6px 10px;
    font-size:13px;
    border-radius:6px;
    text-decoration:none;
    color:white;
}

.toggle-btn{ background:#ff511c; }
.edit-btn{ background:#555; }
.add-btn{
    display:inline-block;
    margin-bottom:15px;
    background:#2e8b57;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
}
.status-active{ color:green; font-weight:bold; }
.status-inactive{ color:red; font-weight:bold; }


.image-box{
    background:#ffffff;
    border-radius:20px;
    padding:32px;
    margin-top:40px;
    max-width:1000px;
    border:1px solid #e6e6e6;
}


.image-box h3{
    font-size:22px;
    font-weight:600;
    margin-bottom:24px;
}


.image-add-form{
    display:flex;
    gap:12px;
    margin-bottom:30px;
}

.image-add-form input,
.image-add-form button{
    padding:10px 14px;
    border-radius:10px;
    font-size:14px;
}

.image-add-form input{
    border:1px solid #ccc;
}

.image-add-form button{
    background:#000;
    color:#fff;
    border:none;
    cursor:pointer;
}


.image-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(220px,1fr));
    gap:28px;
}


.image-card{
    border-radius:18px;
    overflow:hidden;
    background:#f8f8f8;
    transition:all .2s ease;
    cursor:grab;
}

.image-card:hover{
    transform:translateY(-4px);
}


.image-card img{
    width:100%;
    height:160px;
    object-fit:cover;
    display:block;
}


.image-info{
    padding:12px 14px;
}


.image-title{
    font-size:14px;
    font-weight:600;
    margin-bottom:6px;
}


.image-footer{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.image-status{
    font-size:12px;
    font-weight:600;
}

.active{ color:#16a34a; }
.inactive{ color:#dc2626; }


.image-actions a{
    font-size:13px;
    text-decoration:none;
    color:#555;
    padding:6px 10px;
    border-radius:8px;
}

.image-actions a:hover{
    background:#eaeaea;
}

</style>
</head>

<body>

<?php include 'admin_nav.php'; ?>

<div class="dashboard-container">

    <h1>Welcome, <?= htmlspecialchars($adminName) ?> 👋</h1>
    <div class="subtitle">You can manage the entire system from here.</div>

   
    <div class="voice-hint-box">
        <h3>🎙 Voice Navigation</h3>
        <p>
            If you want to control the navigation bar using voice commands,
            enable the <b>Voice</b> checkbox at the top right of the page.
            <br>
            Example commands:
            <b>bookings</b>, <b>employees</b>, <b>orders</b>,
            <b>comments</b>, <b>discount</b>, <b>items</b>.
        </p>
    </div>

    
    <div class="activity-box">
        <h3>📊 Today's Activity</h3>
        <ul class="activity-list">
            <li>
                <span>📦 Orders Today</span>
                <span class="badge"><?= $ordersToday ?></span>
            </li>
            <li>
                <span>🪑 Bookings Today</span>
                <span class="badge"><?= $bookingsToday ?></span>
            </li>
            <li>
                <span>💬 Comments Today</span>
                <span class="badge"><?= $commentsToday ?></span>
            </li>
        </ul>
    </div>
    
<div class="image-box">
    <h3>📸 Manage Menu Images</h3>

    <!-- ➕ ADD IMAGE -->
    <form method="post" enctype="multipart/form-data" style="margin-bottom:20px;">
        <input type="text" name="title" placeholder="Image title" required>
        <input type="file" name="image" required>
        <button name="add_image">➕ Add Image</button>
    </form>

    <div class="image-grid">
        <?php while($img = $images->fetch_assoc()): ?>
            <div class="image-card" 
     draggable="true" 
     data-id="<?= $img['id'] ?>">


                <img src="<?= $img['image_path'] ?>">

                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($img['title']) ?>">
                    <input type="file" name="image">
                    <button name="edit_image">✏ Save</button>
                </form>

                <a href="?toggle_id=<?= $img['id'] ?>" class="toggle-btn">
                    <?= $img['is_active'] ? 'Deactivate' : 'Activate' ?>
                </a>

            </div>
        <?php endwhile; ?>
    </div>
</div>


</div>
<script>
const cards = document.querySelectorAll('.image-card');
let draggedItem = null;

cards.forEach(card => {

    card.addEventListener('dragstart', () => {
        draggedItem = card;
        card.style.opacity = "0.5";
    });

    card.addEventListener('dragend', () => {
        draggedItem = null;
        card.style.opacity = "1";
    });

    card.addEventListener('dragover', e => {
        e.preventDefault();
    });

    card.addEventListener('drop', () => {
        if (draggedItem !== card) {
            let parent = card.parentNode;
            parent.insertBefore(draggedItem, card.nextSibling);

            saveOrder();
        }
    });
});

function saveOrder() {
    let order = [];
    document.querySelectorAll('.image-card').forEach((card, index) => {
        order.push({
            id: card.dataset.id,
            position: index + 1
        });
    });

    fetch("admin_dashboard.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({ reorder: order })
    });
}
</script>

</body>
</html>
