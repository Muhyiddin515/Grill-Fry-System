<?php
session_start();
require_once 'connection.php';




if (isset($_POST['add_offer'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $old_price = $_POST['old_price'];
    $new_price = $_POST['new_price'];
    $show_day = $_POST['show_day'];

  
    $imageName = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $targetDir = "uploads/offers/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $imageName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
    }

    $stmt = $connection->prepare(
        "INSERT INTO offers (title, description, old_price, new_price, image, show_day)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssddss", $title, $desc, $old_price, $new_price, $imageName, $show_day);
    $stmt->execute();
    $stmt->close();
    header("Location: offers.php");
    exit;
}



if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];

    $q = $connection->query("SELECT is_active FROM offers WHERE id=$id");
    $current = $q->fetch_assoc()['is_active'];

    $new_status = $current ? 0 : 1;

    $stmt = $connection->prepare("UPDATE offers SET is_active=? WHERE id=?");
    $stmt->bind_param("ii", $new_status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: offers.php");
    exit;
}


  

if (isset($_POST['edit_offer'])) {

    $id = $_POST['id'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $old_price = $_POST['old_price'];
    $new_price = $_POST['new_price'];
    $show_day = $_POST['show_day'];

    $imageName = null;

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $targetDir = "uploads/offers/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $imageName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);

        $stmt = $connection->prepare(
            "UPDATE offers SET title=?, description=?, old_price=?, new_price=?, image=?, show_day=? WHERE id=?"
        );
        $stmt->bind_param("ssddssi", $title, $desc, $old_price, $new_price, $imageName, $show_day, $id);

    } else {

        $stmt = $connection->prepare(
            "UPDATE offers SET title=?, description=?, old_price=?, new_price=?, show_day=? WHERE id=?"
        );
        $stmt->bind_param("ssddsi", $title, $desc, $old_price, $new_price, $show_day, $id);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: offers.php");
    exit;
}



$result = $connection->query("SELECT * FROM offers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Offers Management</title>

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

    h2 {
      text-align: center;
      color: #0a0a0aff;
      margin-top: 0;
    }
 
.offer-form {
    background:white; max-width:450px;
    margin:25px auto; padding:25px;
    border-radius:6px;
}
input, textarea, select {
    width:100%; padding:8px;
    margin:6px 0; border-radius:5px;
    border:1px solid #ccc;
}
textarea{ height:80px; }
.add-btn { background:green; color:white; padding:8px 12px; border:none; border-radius:6px; }


.offer-container{
    width:92%; display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:22px; margin:auto;
}


.offer-card{
    background:white;
     padding:25px;
    border-radius:8px; 
    text-align:center;
    box-shadow:0 0 8px rgba(0,0,0,0.15);
}
.offer-card img{
    width:100%; height:180px;
    object-fit:cover; border-radius:8px;
}

.edit-btn{ background:blue; color:white; padding:6px 10px; border-radius:6px; }
.active-btn{ background:green; color:white; padding:6px 10px; border-radius:6px; }
.inactive-btn{ background:gray; color:white; padding:6px 10px; border-radius:6px; }
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
    <?php include 'admin_nav.php'; ?>
<header>
 </header>



<h2>Offers Management</h2>


<div class="offer-form">
<form action="offers.php" method="post" enctype="multipart/form-data">
    <h3>Add Offer</h3>

    <input type="text" name="title" placeholder="Title" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" step="0.01" name="old_price" placeholder="Old Price" required>
    <input type="number" step="0.01" name="new_price" placeholder="New Price" required>

    <label>Offer Image:</label>
    <input type="file" name="image" accept="image/*">

    <label>Show Day:</label>
    <select name="show_day">
        <option value="all">All Days</option>
        <option value="monday">Monday</option>
        <option value="tuesday">Tuesday</option>
        <option value="wednesday">Wednesday</option>
        <option value="thursday">Thursday</option>
        <option value="friday">Friday</option>
        <option value="saturday">Saturday</option>
        <option value="sunday">Sunday</option>
    </select>

    <button class="add-btn" name="add_offer">Add Offer</button>
</form>
</div>


<div class="offer-container">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="offer-card">

        <?php $img = $row['image'] ? $row['image'] : "default.jpg"; ?>

        <img src="uploads/offers/<?php echo $img; ?>">

        <h3><?php echo $row['title']; ?></h3>
        <p><?php echo $row['description']; ?></p>

        <p><b>Old:</b> $<?php echo $row['old_price']; ?></p>
        <p><b>New:</b> $<?php echo $row['new_price']; ?></p>
        <p><b>Day:</b> <?php echo ucfirst($row['show_day']); ?></p>

        <form action="offers.php" method="post" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <input type="text" name="title" value="<?php echo $row['title']; ?>" required>
            <textarea name="description"><?php echo $row['description']; ?></textarea>
            <input type="number" step="0.01" name="old_price" value="<?php echo $row['old_price']; ?>">
            <input type="number" step="0.01" name="new_price" value="<?php echo $row['new_price']; ?>">

            <select name="show_day">
                <option value="all" <?php if($row['show_day']=="all") echo "selected"; ?>>All Days</option>
                <option value="monday" <?php if($row['show_day']=="monday") echo "selected"; ?>>Monday</option>
                <option value="tuesday" <?php if($row['show_day']=="tuesday") echo "selected"; ?>>Tuesday</option>
                <option value="wednesday" <?php if($row['show_day']=="wednesday") echo "selected"; ?>>Wednesday</option>
                <option value="thursday" <?php if($row['show_day']=="thursday") echo "selected"; ?>>Thursday</option>
                <option value="friday" <?php if($row['show_day']=="friday") echo "selected"; ?>>Friday</option>
                <option value="saturday" <?php if($row['show_day']=="saturday") echo "selected"; ?>>Saturday</option>
                <option value="sunday" <?php if($row['show_day']=="sunday") echo "selected"; ?>>Sunday</option>
            </select>

            <label>New Image:</label>
            <input type="file" name="image" accept="image/*">

            <button class="edit-btn" name="edit_offer">Edit</button>

            <a href="offers.php?toggle=<?php echo $row['id']; ?>">
                <button type="button"
                    class="<?php echo $row['is_active'] ? 'active-btn' : 'inactive-btn'; ?>">
                    <?php echo $row['is_active'] ? 'Active' : 'Not Active'; ?>
                </button>
            </a>

        </form>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>
