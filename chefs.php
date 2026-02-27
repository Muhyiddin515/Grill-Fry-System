<?php
session_start();
require_once 'connection.php';

if (isset($_POST['add_chef'])) {

    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $specialty = $_POST['specialty'];
    $salary = $_POST['salary'];
    $working_time = $_POST['working_time'];

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $dir = "uploads/chefs/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $dir . $imageName);
    }

    $stmt = $connection->prepare("
        INSERT INTO chefs (name, email, specialty, salary, working_time, image)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssiss", $name, $email, $specialty, $salary, $working_time, $imageName);
    $stmt->execute();

    $chef_id = $stmt->insert_id;
    $stmt->close();

    $stmt2 = $connection->prepare("
        INSERT INTO address (user_id, user_type, address)
        VALUES (?, 'chef', ?)
    ");
    $stmt2->bind_param("is", $chef_id, $address);
    $stmt2->execute();
    $stmt2->close();

    header("Location: chefs.php");
    exit;
}

if (isset($_POST['edit_chef'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $specialty = $_POST['specialty'];
    $salary = $_POST['salary'];
    $working_time = $_POST['working_time'];

    if (!empty($_FILES['image']['name'])) {
        $dir = "uploads/chefs/";
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $dir . $imageName);

        $stmt = $connection->prepare("
            UPDATE chefs
            SET name=?, email=?, specialty=?, salary=?, working_time=?, image=?
            WHERE id=?
        ");
        $stmt->bind_param("sssissi", $name, $email, $specialty, $salary, $working_time, $imageName, $id);
    } else {
        $stmt = $connection->prepare("
            UPDATE chefs
            SET name=?, email=?, specialty=?, salary=?, working_time=?
            WHERE id=?
        ");
        $stmt->bind_param("sssisi", $name, $email, $specialty, $salary, $working_time, $id);
    }

    $stmt->execute();
    $stmt->close();

    $check = $connection->prepare("
        SELECT id FROM address WHERE user_id=? AND user_type='chef'
    ");
    $check->bind_param("i", $id);
    $check->execute();
    $exists = $check->get_result()->num_rows;
    $check->close();

    if ($exists) {
        $stmt2 = $connection->prepare("
            UPDATE address SET address=? WHERE user_id=? AND user_type='chef'
        ");
        $stmt2->bind_param("si", $address, $id);
    } else {
        $stmt2 = $connection->prepare("
            INSERT INTO address (user_id, user_type, address)
            VALUES (?, 'chef', ?)
        ");
        $stmt2->bind_param("is", $id, $address);
    }

    $stmt2->execute();
    $stmt2->close();

    header("Location: chefs.php");
    exit;
}

$result = $connection->query("
    SELECT c.*, a.address
    FROM chefs c
    LEFT JOIN address a ON a.user_id = c.id AND a.user_type='chef'
    ORDER BY c.id DESC
");
?>

<!DOCTYPE html>
<html>
    <?php include 'admin_nav.php'; ?>
<head>
    <title>Admin Chefs Management</title>

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
      color: #0e0d0dff;
      margin-top: 0;
    }


.chef-form{
    background:white;
    max-width:450px;
    padding:25px;
    margin:20px auto;
    border-radius:6px;
}
input,select{
    width:100%;
    padding:8px;
    margin:5px 0;
    border-radius:5px;
    border:1px solid #ccc;
}
.add-btn{ background:green; color:white; border:none; padding:8px; border-radius:6px; }


.chef-container{
    width:92%;
    margin:20px auto;
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
}

.chef-card{
    background:white;
    padding:25px;
    border-radius:8px;
    text-align:center;
    box-shadow:0 0 8px rgba(0,0,0,0.15);
}
.chef-card img{
    width:130px; height:130px;
    object-fit:cover;
    border-radius:50%;
    border:3px solid orange;
    cursor:pointer;
}


.edit-btn{ background:blue; color:white; padding:6px 10px; border:none; border-radius:6px; }
.active-btn{ background:green; color:white; padding:6px 10px; border:none; border-radius:6px; }
.inactive-btn{ background:gray; color:white; padding:6px 10px; border:none; border-radius:6px; }


.popup-bg{
    position:fixed; top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.6);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}
.popup-box{
    background:white;
    width:300px;
    padding:20px;
    border-radius:8px;
    text-align:center;
}
.popup-box img{
    width:110px; height:110px;
    border-radius:50%;
    border:3px solid orange;
}
.close-btn{ background:red; color:white; padding:8px 16px; border:none; border-radius:6px; }

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



<h2>Admin Chefs Management</h2>


<div class="chef-form">
<form action="chefs.php" method="post" enctype="multipart/form-data">

    <h3>Add New Chef</h3>

    <input type="text" name="name" placeholder="Name" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="specialty" placeholder="Specialty" required>
    <input type="number" name="salary" placeholder="Salary" required>

    <select name="working_time" required>
        <option value="Full Time">Full Time</option>
        <option value="Part Time">Part Time</option>
        <option value="Night Shift">Night Shift</option>
    </select>

    <input type="file" name="image" accept="image/*">

    <button class="add-btn" name="add_chef">Add Chef</button>

</form>
</div>


<div class="chef-container">

<?php while ($row = $result->fetch_assoc()): ?>
<div class="chef-card">

    <img src="uploads/chefs/<?php echo $row['image']; ?>"
         onclick="showPopup(
            '<?php echo $row['name']; ?>',
            '<?php echo $row['address']; ?>',
            '<?php echo $row['email']; ?>',
            '<?php echo $row['specialty']; ?>',
            '<?php echo $row['salary']; ?>',
            '<?php echo $row['working_time']; ?>',
            '<?php echo $row['image']; ?>'
         )">

    <h3><?php echo $row['name']; ?></h3>
    <p><b>Address:</b> <?php echo $row['address']; ?></p>
    <p><b>Email:</b> <?php echo $row['email']; ?></p>
    <p><b>Specialty:</b> <?php echo $row['specialty']; ?></p>
    <p><b>Salary:</b> $<?php echo $row['salary']; ?></p>
    <p><b>Working:</b> <?php echo $row['working_time']; ?></p>

    <form action="chefs.php" method="post" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
    <input type="text" name="address" value="<?php echo $row['address']; ?>" required>
    <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
    <input type="text" name="specialty" value="<?php echo $row['specialty']; ?>" required>
    <input type="number" name="salary" value="<?php echo $row['salary']; ?>" required>

    <select name="working_time">
        <option value="Full Time" <?= $row['working_time']=="Full Time"?'selected':'' ?>>Full Time</option>
        <option value="Part Time" <?= $row['working_time']=="Part Time"?'selected':'' ?>>Part Time</option>
        <option value="Night Shift" <?= $row['working_time']=="Night Shift"?'selected':'' ?>>Night Shift</option>
    </select>

    <input type="file" name="image">

    <button class="edit-btn" name="edit_chef">Edit</button>

        <a href="chefs.php?toggle=<?php echo $row['id']; ?>">
            <button type="button"
                class="<?php echo $row['is_active'] ? 'active-btn' : 'inactive-btn'; ?>">
                <?php echo $row['is_active'] ? 'Set Not Active' : 'Set Active'; ?>
            </button>
        </a>
    </form>

</div>
<?php endwhile; ?>

</div>


<div class="popup-bg" id="popupBg">
    <div class="popup-box">
        <img id="popupImg">
        <h3 id="popupName"></h3>
        <p id="popupAddress"></p>
        <p id="popupEmail"></p>
        <p><b>Specialty:</b> <span id="popupSpec"></span></p>
        <p><b>Salary:</b> $<span id="popupSalary"></span></p>
        <p><b>Working:</b> <span id="popupTime"></span></p>

        <button class="close-btn" onclick="closePopup()">Close</button>
    </div>
</div>

<script>

function showPopup(name, address, email, specialty, salary, time, image) {
    document.getElementById("popupName").textContent = name;
    document.getElementById("popupAddress").textContent = address;
    document.getElementById("popupEmail").textContent = email;
    document.getElementById("popupSpec").textContent = specialty;
    document.getElementById("popupSalary").textContent = salary;
    document.getElementById("popupTime").textContent = time;
    document.getElementById("popupImg").src = "uploads/chefs/" + image;

    document.getElementById("popupBg").style.display = "flex";
}

function closePopup() {
    document.getElementById("popupBg").style.display = "none";
}

</script>

</body>
</html>
