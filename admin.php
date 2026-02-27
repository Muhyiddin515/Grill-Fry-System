<?php
session_start();
require_once 'connection.php';


if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "Access denied.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = ($_POST['action'] === 'accept') ? 'accepted' : 'rejected';

    $update = $connection->prepare("UPDATE grill_fry_bookings SET status = ? WHERE id = ?");
    $update->bind_param("si", $new_status, $booking_id);
    $update->execute();
    $update->close();
}


$query = "
    SELECT 
        b.*, 
        u.name AS username
    FROM grill_fry_bookings b
    JOIN grill_fry_users u ON b.user_id = u.id
    ORDER BY b.date desc, b.time desc
";
$result = $connection->query($query);
?>

<!DOCTYPE html>
<html>
  <?php include 'admin_nav.php'; ?>
<head>
  <title>Admin - Manage Bookings</title>
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

    table {
      background-color: #fff;
      width: 95%;
      margin: 20px auto;
      border-collapse: collapse;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #f57c00;
      color: white;
    }

    button {
      padding: 6px 12px;
      margin: 2px;
      border: none;
      color: white;
      cursor: pointer;
      border-radius: 4px;
    }

    .accept { background-color: green; }
    .reject { background-color: red; }
    .accept:hover { background-color: darkgreen; }
    .reject:hover { background-color: darkred; }
  </style>
</head>
<body>

<h2 style="text-align:center;">Admin Booking Management</h2>

<table>
  <thead>
    <tr>
      <th>Booking #</th>
      <th>User Name</th>
      <th>Phone</th>
      <th>People</th>
      <th>Table Number</th>
      <th>Date</th>
      <th>Time</th>
      <th>Payment</th>
      <th>Canceled</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>

  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td>
  <?= htmlspecialchars($row['booking_number']) ?>
</td>

        <td><?php echo htmlspecialchars($row['name']); ?></td>

        <td><?php echo htmlspecialchars($row['phone']); ?></td>
        <td><?php echo $row['people']; ?></td>
        <td><?php echo $row['table_number']; ?></td>
        <td><?php echo $row['date']; ?></td>
        <td><?php echo $row['time']; ?></td>
        <td><?php echo htmlspecialchars($row['payment_type']); ?></td>
        <td><?php echo $row['canceled'] ? 'Yes' : 'No'; ?></td>
        <td><?php echo htmlspecialchars($row['status']); ?></td>

        <td>
          <?php if ($row['canceled'] == 1): ?>
            Canceled
          
          <?php elseif ($row['status'] === 'pending'): ?>
            <form method="POST">
              <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
              <button type="submit" name="action" value="accept" class="accept">Accept</button>
              <button type="submit" name="action" value="reject" class="reject">Reject</button>
            </form>

          <?php else: ?>
            <?php echo ucfirst($row['status']); ?>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>
