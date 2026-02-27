<?php
session_start();
require 'connection.php';


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}


if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $stmt = $connection->prepare("UPDATE reviews SET status='approved' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    
    $stmt = $connection->prepare("
        UPDATE reviews 
        SET status = 'expected' 
        WHERE id = ? AND status = 'pending'
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_comments.php");
    exit;
}



$sql = "
SELECT r.id, r.rating, r.comment, r.status, r.created_at, u.name, u.email
FROM reviews r
JOIN grill_fry_users u ON r.user_id = u.id
ORDER BY r.created_at DESC
";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin - Manage Comments</title>

  <style>
    body {
      background-color: wheat;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #000;
      margin: 20px 0;
    }

    table {
      width: 90%;
      margin: auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }

    th {
      background-color: #ff511c;
      color: white;
    }

    .status-pending {
      color: orange;
      font-weight: bold;
    }

    .status-approved {
      color: green;
      font-weight: bold;
    }

    .approve-btn {
      background-color: #28a745;
      padding: 6px 14px;
      border-radius: 6px;
      color: white;
      text-decoration: none;
    }

    .approve-btn:hover {
      background-color: #218838;
    }

    .approved-text {
      color: green;
      font-weight: bold;
    }
    .delete-btn {
  background-color: #e74c3c;
  padding: 6px 14px;
  border-radius: 6px;
  color: white;
  text-decoration: none;
  margin-left: 5px;
}

.delete-btn:hover {
  background-color: #c0392b;
}
.status-expected {
  color: red;
  font-weight: bold;
}


.status-expected {
    color: #e60000;   
    font-weight: bold;
}


  </style>
</head>

<body>

<?php include 'admin_nav.php'; ?>

<h2>💬 Customers Comments & Ratings</h2>

<table>
  <tr>
    <th>User</th>
    <th>Email</th>
    <th>Rating</th>
    <th>Comment</th>
    <th>Status</th>
    <th>Date</th>
    <th>Action</th>
  </tr>

  <?php if ($result->num_rows == 0): ?>
    <tr>
      <td colspan="7">No comments yet.</td>
    </tr>
  <?php else: ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['name']); ?></td>

        <td><?php echo htmlspecialchars($row['email']); ?></td>

        <td><?php echo str_repeat("⭐", $row['rating']); ?></td>

        <td><?php echo htmlspecialchars($row['comment']); ?></td>

        <td class="
<?php 
    if ($row['status'] == 'pending') echo 'status-pending';
    elseif ($row['status'] == 'approved') echo 'status-approved';
    elseif ($row['status'] == 'expected' || $row['status'] == 'rejected') echo 'status-expected';
?>">
<?php
    if ($row['status'] == 'pending') {
        echo 'Pending';
    } elseif ($row['status'] == 'approved') {
        echo 'Approved';
    } elseif ($row['status'] == 'expected' || $row['status'] == 'rejected') {
        echo 'Rejected';
    } else {
        echo 'Rejected';
    }
?>
</td>



        <td><?php echo $row['created_at']; ?></td>

        <td>

  <?php if ($row['status'] == 'pending'): ?>
    
    <a href="admin_comments.php?approve=<?= $row['id'] ?>" class="approve-btn">
      Approve
    </a>

    <a href="admin_comments.php?delete=<?= $row['id'] ?>"
       class="delete-btn"
       onclick="return confirm('Reject this comment?');">
       Reject
    </a>

  <?php elseif ($row['status'] == 'approved'): ?>

    <span class="approved-text">✔ Approved</span>

  <?php else: ?>

    <span class="status-expected">✖ Rejected</span>

  <?php endif; ?>

</td>



      </tr>
    <?php endwhile; ?>
  <?php endif; ?>
</table>

</body>
</html>
