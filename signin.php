<?php
session_start();
require_once 'connection.php';


$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';


if (empty($email) || empty($password)) {
    echo "<script>
            alert('Please enter both email and password.');
            window.location.href = 'signin.html';
          </script>";
    exit;
}

$stmt = $connection->prepare("SELECT id, password, admin FROM grill_fry_users WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $connection->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();


$connection->close();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();

    if (password_verify($password, $user_data['password'])) {
       
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['is_admin'] = $user_data['admin'];

        
        if ($user_data['admin'] == 1) {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: afterlogin.php");
        }
        exit;
    } else {
       
        echo "<script>
                alert('Incorrect password.');
                window.location.href = 'signin.html';
              </script>";
        exit;
    }
} else {
    
    echo "<script>
            alert('Email not found.');
            window.location.href = 'signin.html';
          </script>";
    exit;
}
?>
