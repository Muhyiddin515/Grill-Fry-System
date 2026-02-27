<?php

require_once 'connection.php';


$REAL_ADMIN_SECRET = "GRILL123"; 

$name         = trim($_POST['name'] ?? '');
$email        = trim($_POST['email'] ?? '');
$phone        = trim($_POST['phone'] ?? '');
$gender       = trim($_POST['gender'] ?? '');
$birthday     = trim($_POST['birthday'] ?? '');
$password     = $_POST['password'] ?? '';
$admin_secret = trim($_POST['admin_secret'] ?? ''); 

if (empty($name) || empty($email) || empty($phone) || empty($gender) || empty($birthday) || empty($password)) {
    echo "<script>
            alert('Please fill in all fields!');
            window.location.href = 'signup.html';
          </script>";
    exit;
}

$check_query = "SELECT id FROM grill_fry_users WHERE email = ?";
$stmt = $connection->prepare($check_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    echo "<script>
            alert('This email is already registered!');
            window.location.href = 'signup.html';
          </script>";
    exit;
}

$isAdmin = ($admin_secret === $REAL_ADMIN_SECRET) ? 1 : 0;

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$insert_query = "
    INSERT INTO grill_fry_users (name, email, phone, gender, birthday, password, admin)
    VALUES (?, ?, ?, ?, ?, ?, ?)
";

$stmt = $connection->prepare($insert_query);
$stmt->bind_param("ssssssi", $name, $email, $phone, $gender, $birthday, $hashedPassword, $isAdmin);

if ($stmt->execute()) {
    $stmt->close();
    $connection->close();

    echo "<script>
            alert('Sign up successful! Please log in.');
            window.location.href = 'signin.html';
          </script>";
    exit;
} else {
    echo "Error: " . $stmt->error;
    $stmt->close();
    $connection->close();
}
?>
