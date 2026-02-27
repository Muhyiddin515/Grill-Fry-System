<?php
session_start();
require 'connection.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];


$sql = "
SELECT 
  b.id, 
  b.name AS booking_name, 
  b.phone, 
  b.people, 
  b.date, 
  b.time,
  b.table_number, 
  b.status, 
  u.name AS username
FROM grill_fry_bookings b
JOIN grill_fry_users u ON b.user_id = u.id
WHERE b.user_id = ? AND b.canceled = 0
ORDER BY b.date desc, b.time desc
";

$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - GRILL & FRY</title>
    <link rel="stylesheet" href="stylesheet.css">
    <script>
    function cancelBooking(id) {
      if (!confirm('Are you sure you want to cancel this booking?')) return;

      fetch('cancel_booking.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + id
      })
      .then(response => response.text())
      .then(data => {
        if (data === 'success') {
          document.getElementById('row-' + id).remove();
        } else {
          alert('Failed to cancel booking.');
        }
      });
    }
    </script>
    <style>
        table {
        background-color: #fff;
        width: 95%;
        margin: 20px auto;
        border-collapse: collapse;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        th{
            font-size:25px;
        }
        table tr {
        height: 60px;
        }
       
    </style>
</head>

<body>
   
    <section class="Home">
        <?php include 'nav.php'; ?>

    </section>

    <section class="grid">
        <div class="content">
            <div class="content-left">
                <div class="info">
                    <h2>My Grill Fry Bookings</h2>
                    <p><b>Here are your active reservations:</b></p>
                </div>
            </div>
        </div>

        <table style="width:100%; margin:auto; border-collapse:collapse; background-color:white; text-align:center; box-shadow:0 0 10px rgba(0,0,0,0.2);">
            <thead>
                <tr style="background-color:#ff551c; color:white; height:70px ;">
                    <th>UserName</th>
                    <th>bookingname</th>
                    <th>Phone</th>
                    <th>People</th>
                    <th>Table No.</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr id="row-<?php echo $row['id']; ?>">
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['booking_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['people']); ?></td>
                    <td><?php echo htmlspecialchars($row['table_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['time']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><button onclick="cancelBooking(<?php echo $row['id']; ?>)" class="signup" style="background-color:red;">Cancel</button></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
