<?php
session_start();
require_once 'connection.php';


if (isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $working_time = $_POST['working_time'];

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/employees/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
    }

    
    $stmt = $connection->prepare("
        INSERT INTO employees (name,email,position,salary,working_time,image)
        VALUES (?,?,?,?,?,?)
    ");
    $stmt->bind_param("sssiss", $name,$email,$position,$salary,$working_time,$imageName);
    $stmt->execute();

    $employee_id = $stmt->insert_id; 
    $stmt->close();

  
    $stmt = $connection->prepare("
        INSERT INTO address (user_id, user_type, address)
        VALUES (?, 'employee', ?)
    ");
    $stmt->bind_param("is", $employee_id, $address);
    $stmt->execute();
    $stmt->close();

    header("Location: employees.php");
    exit;
}



if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];

    $q = $connection->query("SELECT is_active FROM employees WHERE id=$id");
    $current = $q->fetch_assoc()['is_active'];

    $new_status = $current ? 0 : 1;

    $stmt = $connection->prepare("UPDATE employees SET is_active=? WHERE id=?");
    $stmt->bind_param("ii", $new_status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: employees.php");
    exit;
}


if (isset($_POST['edit_employee'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $working_time = $_POST['working_time'];

    
    $stmt = $connection->prepare("
        UPDATE employees
        SET name=?, email=?, position=?, salary=?, working_time=?
        WHERE id=?
    ");
    $stmt->bind_param("sssdsi", $name,$email,$position,$salary,$working_time,$id);
    $stmt->execute();
    $stmt->close();

   
    $check = $connection->prepare("
        SELECT id FROM address WHERE user_id=? AND user_type='employee'
    ");
    $check->bind_param("i", $id);
    $check->execute();
    $exists = $check->get_result()->num_rows;
    $check->close();

    if ($exists) {
        $stmt = $connection->prepare("
            UPDATE address SET address=?
            WHERE user_id=? AND user_type='employee'
        ");
        $stmt->bind_param("si", $address, $id);
    } else {
        $stmt = $connection->prepare("
            INSERT INTO address (user_id,user_type,address)
            VALUES (?, 'employee', ?)
        ");
        $stmt->bind_param("is", $id, $address);
    }

    $stmt->execute();
    $stmt->close();

    header("Location: employees.php");
    exit;
}



$result = $connection->query("
    SELECT e.*, a.address
    FROM employees e
    LEFT JOIN address a
      ON a.user_id = e.id AND a.user_type='employee'
    ORDER BY e.id DESC
");

?>

<!DOCTYPE html>
<html>
    <?php include 'admin_nav.php'; ?>
<head>
    <title>Admin Employees Management</title>

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

.employee-form {
    background:white;
    max-width:450px;
    padding:25px;
    margin:25px auto;
    border-radius:6px;
}
input[type=text],input[type=email],input[type=number],input[type=file], select{
    width:100%; padding:8px; margin:6px 0; border:1px solid #ccc; border-radius:5px;
}
.add-btn{ 
    background:green; 
    color:white; 
    padding:8px 12px;
     border:none;
      border-radius:5px; }


.employee-container {
    width: 92%;
    margin: 25px auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}


.employee-card {
    background:white;
    padding: 25px;
    border-radius: 8px;
    text-align:center;
    box-shadow: 0 0 8px rgba(0,0,0,0.15);
}
.employee-card img {
    width: 130px;
    height: 130px;
    object-fit:cover;
    border-radius:50%;
    border:3px solid orange;
    cursor:pointer;
}
.employee-card h3 { margin:10px 0 5px; }
.employee-card p { margin:4px; }


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
    width:120px; height:120px;
    object-fit:cover;
    border-radius:50%;
    border:3px solid orange;
}
.close-btn{
    background:red;
    color:white;
    border:none;
    padding:8px 16px;
    margin-top:10px;
    border-radius:6px;
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

</style>

</head>
<body>
<header>
  
</header>



<h2>Admin Employees Management</h2>


<div class="employee-form">
    <form action="employees.php" method="post" enctype="multipart/form-data">
        <h3>Add Employee</h3>

        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="address" placeholder="Address" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="position" placeholder="Position" required>
        <input type="number" name="salary" placeholder="Salary" required>

        <select name="working_time" required>
            <option value="Full Time">Full Time</option>
            <option value="Part Time">Part Time</option>
            <option value="Night Shift">Night Shift</option>
        </select>

        <input type="file" name="image" accept="image/*">

        <button class="add-btn" name="add_employee">Add</button>
    </form>
</div>


<div class="employee-container">
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="employee-card">

        <img src="uploads/employees/<?php echo $row['image']; ?>"
             onclick="showPopup(
                '<?php echo $row['name']; ?>',
                '<?php echo $row['address']; ?>',
                '<?php echo $row['email']; ?>',
                '<?php echo $row['position']; ?>',
                '<?php echo $row['salary']; ?>',
                '<?php echo $row['working_time']; ?>',
                '<?php echo $row['image']; ?>'
             )">

        <h3><?php echo $row['name']; ?></h3>
        <p><b>Address:</b> <?php echo $row['address']; ?></p>
        <p><b>Email:</b> <?php echo $row['email']; ?></p>
        <p><b>Position:</b> <?php echo $row['position']; ?></p>
        <p><b>Salary:</b> $<?php echo $row['salary']; ?></p>
        <p><b>Working Time:</b> <?php echo $row['working_time']; ?></p>

        <form action="employees.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
    <input type="text" name="address" value="<?php echo $row['address']; ?>" required>
    <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
    <input type="text" name="position" value="<?php echo $row['position']; ?>" required>
    <input type="number" name="salary" value="<?php echo $row['salary']; ?>" required>

    <select name="working_time">
        <option value="Full Time" <?php if($row['working_time']=="Full Time") echo "selected"; ?>>Full Time</option>
        <option value="Part Time" <?php if($row['working_time']=="Part Time") echo "selected"; ?>>Part Time</option>
        <option value="Night Shift" <?php if($row['working_time']=="Night Shift") echo "selected"; ?>>Night Shift</option>
    </select>

    <input type="file" name="image" accept="image/*">

    <button class="edit-btn" name="edit_employee">Edit</button>

            <a href="employees.php?toggle=<?php echo $row['id']; ?>">
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
        <p><b>Position:</b> <span id="popupPosition"></span></p>
        <p><b>Salary:</b> $<span id="popupSalary"></span></p>
        <p><b>Working Time:</b> <span id="popupTime"></span></p>
        <button class="close-btn" onclick="closePopup()">Close</button>
    </div>
</div>

<script>
function showPopup(name, address, email, position, salary, time, image) {
    document.getElementById("popupName").textContent = name;
    document.getElementById("popupAddress").textContent = address;
    document.getElementById("popupEmail").textContent = email;
    document.getElementById("popupPosition").textContent = position;
    document.getElementById("popupSalary").textContent = salary;
    document.getElementById("popupTime").textContent = time;
    document.getElementById("popupImg").src = "uploads/employees/" + image;

    document.getElementById("popupBg").style.display = "flex";
}

function closePopup() {
    document.getElementById("popupBg").style.display = "none";
}
</script>

</body>
</html>
