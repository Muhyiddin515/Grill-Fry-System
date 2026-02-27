<?php
session_start();
require_once 'connection.php';


if (isset($_POST['add_delivery'])) {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $vehicle = $_POST['vehicle'];
    $salary = $_POST['salary'];
    $working_time = $_POST['working_time'];
    $address = $_POST['address'];

    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/delivery/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
    }

    
    $stmt = $connection->prepare(
        "INSERT INTO delivery (name, phone, vehicle, salary, working_time, image)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sssiss", $name, $phone, $vehicle, $salary, $working_time, $imageName);
    $stmt->execute();
    $delivery_id = $connection->insert_id;
    $stmt->close();

    
    $stmt2 = $connection->prepare(
        "INSERT INTO address (user_id, user_type, address)
         VALUES (?, 'delivery', ?)"
    );
    $stmt2->bind_param("is", $delivery_id, $address);
    $stmt2->execute();
    $stmt2->close();

    header("Location: delivery.php");
    exit;
}


if (isset($_POST['edit_delivery'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $vehicle = $_POST['vehicle'];
    $salary = $_POST['salary'];
    $working_time = $_POST['working_time'];
    $address = $_POST['address'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $targetDir = "uploads/delivery/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);

        $stmt = $connection->prepare(
            "UPDATE delivery
             SET name=?, phone=?, vehicle=?, salary=?, working_time=?, image=?
             WHERE id=?"
        );
        $stmt->bind_param("sssissi", $name, $phone, $vehicle, $salary, $working_time, $imageName, $id);

    } else {

        $stmt = $connection->prepare(
            "UPDATE delivery
             SET name=?, phone=?, vehicle=?, salary=?, working_time=?
             WHERE id=?"
        );
        $stmt->bind_param("sssisi", $name, $phone, $vehicle, $salary, $working_time, $id);
    }

    $stmt->execute();
    $stmt->close();


   
$stmt2 = $connection->prepare("
    SELECT id FROM address 
    WHERE user_id = ? AND user_type = 'delivery'
");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$res = $stmt2->get_result();
$stmt2->close();

if ($res->num_rows > 0) {
    
    $stmt3 = $connection->prepare("
        UPDATE address 
        SET address = ?
        WHERE user_id = ? AND user_type = 'delivery'
    ");
    $stmt3->bind_param("si", $address, $id);
    $stmt3->execute();
    $stmt3->close();
} else {
   
    $stmt3 = $connection->prepare("
        INSERT INTO address (user_id, user_type, address)
        VALUES (?, 'delivery', ?)
    ");
    $stmt3->bind_param("is", $id, $address);
    $stmt3->execute();
    $stmt3->close();
}
}


$result = $connection->query("
    SELECT d.*, a.address
    FROM delivery d
    LEFT JOIN address a
      ON a.user_id = d.id AND a.user_type = 'delivery'
    ORDER BY d.id DESC
");
?>


<!DOCTYPE html>
<html>
    <?php include 'admin_nav.php'; ?>
<head>
    <title>Delivery Management</title>

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
      color: #050505ff;
      margin-top: 0;
    }


.delivery-form {
    background:white;
    max-width:450px;
    padding:25px;
    margin:25px auto;
    border-radius:6px;
}
input,select{
    width:100%;
    padding:8px;
    margin:6px 0;
    border:1px solid #ccc;
    border-radius:5px;
}
.add-btn{ background:green; color:white; padding:8px 12px; border:none; border-radius:5px; }


.delivery-container {
    width: 92%;
    margin: 20px auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}


.delivery-card {
    background:white;
    padding:25px;
    border-radius:8px;
    text-align:center;
    box-shadow:0 0 8px rgba(0,0,0,0.15);
}
.delivery-card img {
    width:130px;
    height:130px;
    object-fit:cover;
    border-radius:50%;
    border:3px solid orange;
    cursor:pointer;
}


.edit-btn{ background:blue; color:white; padding:6px 10px; border:none; border-radius:6px; margin-top:6px; }
.active-btn{ background:green; color:white; padding:6px 10px; border:none; border-radius:6px; margin-top:6px; }
.inactive-btn{ background:gray; color:white; padding:6px 10px; border:none; border-radius:6px; margin-top:6px; }


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
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:50%;
    border:3px solid orange;
}
.close-btn{
    background:red;
    color:white;
    border:none;
    padding:8px 16px;
    border-radius:6px;
}

</style>
</head>

<body>

<header>
 </header>





<h2>Delivery Management</h2>


<div class="delivery-form">
<form action="delivery.php" method="post" enctype="multipart/form-data">

    <h3>Add Delivery Worker</h3>

    <input type="text" name="name" placeholder="Name" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <input type="text" name="address" placeholder="address" required>
    <input type="text" name="vehicle" placeholder="Vehicle (Motor/Car)" required>
    <input type="number" name="salary" placeholder="Salary" required>

    <select name="working_time">
        <option value="Full Time">Full Time</option>
        <option value="Part Time">Part Time</option>
        <option value="Night Shift">Night Shift</option>
    </select>

    <input type="file" name="image" accept="image/*">

    <button class="add-btn" name="add_delivery">Add</button>

</form>
</div>


<div class="delivery-container">
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="delivery-card">

        <img src="uploads/delivery/<?php echo $row['image']; ?>"
             onclick="showPopup(
                '<?php echo $row['name']; ?>',
                '<?php echo $row['phone']; ?>',
                '<?php echo $row['address']; ?>',
                '<?php echo $row['vehicle']; ?>',
                '<?php echo $row['salary']; ?>',
                '<?php echo $row['working_time']; ?>',
                '<?php echo $row['image']; ?>'
             )">

        <h3><?php echo $row['name']; ?></h3>
        <p><b>Phone:</b> <?php echo $row['phone']; ?></p>
        <p><b>address:</b> <?php echo $row['address']; ?></p>
        <p><b>Vehicle:</b> <?php echo $row['vehicle']; ?></p>
        <p><b>Salary:</b> $<?php echo $row['salary']; ?></p>
        <p><b>Working Time:</b> <?php echo $row['working_time']; ?></p>

        <form action="delivery.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
    <input type="text" name="phone" value="<?php echo $row['phone']; ?>" required>
    <input type="text" name="address" value="<?php echo $row['address']; ?>" required>
    <input type="text" name="vehicle" value="<?php echo $row['vehicle']; ?>" required>
    <input type="number" name="salary" value="<?php echo $row['salary']; ?>" required>

    <select name="working_time">
        <option value="Full Time" <?php if($row['working_time']=="Full Time") echo "selected"; ?>>Full Time</option>
        <option value="Part Time" <?php if($row['working_time']=="Part Time") echo "selected"; ?>>Part Time</option>
        <option value="Night Shift" <?php if($row['working_time']=="Night Shift") echo "selected"; ?>>Night Shift</option>
    </select>

    <input type="file" name="image" accept="image/*">

    <button class="edit-btn" name="edit_delivery">Edit</button>

    <a href="delivery.php?toggle=<?php echo $row['id']; ?>">
        <button type="button"
            class="<?php echo $row['is_active'] ? 'active-btn' : 'inactive-btn'; ?>">
            <?php echo $row['is_active'] ? 'Set Not Active' : 'Set Active';?>
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

        <p><b>Phone:</b> <span id="popupPhone"></span></p>
        <p><b>Address:</b> <span id="popupAddress"></span></p>
        <p><b>Vehicle:</b> <span id="popupVeh"></span></p>
        <p><b>Salary:</b> $<span id="popupSalary"></span></p>
        <p><b>Working Time:</b> <span id="popupTime"></span></p>
        <button class="close-btn" onclick="closePopup()">Close</button>
    </div>
</div>

<script>
function showPopup(name, phone, address, vehicle, salary, time, image) {
    document.getElementById("popupName").textContent = name;
    document.getElementById("popupPhone").textContent = phone;
    document.getElementById("popupAddress").textContent = address;
    document.getElementById("popupVeh").textContent = vehicle;
    document.getElementById("popupSalary").textContent = salary;
    document.getElementById("popupTime").textContent = time;
    document.getElementById("popupImg").src = "uploads/delivery/" + image;

    document.getElementById("popupBg").style.display = "flex";
}

function closePopup() {
    document.getElementById("popupBg").style.display = "none";
}
</script>

</body>
</html>
