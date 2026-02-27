<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$result = $connection->prepare("
    SELECT *
    FROM service_requests
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$result->bind_param("i", $user_id);
$result->execute();
$requests = $result->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Service Requests</title>
<style>
body{ background:wheat; font-family:Arial; }

.container{
    width:90%;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.2);
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}

th{ background:#ff511c; color:white; }

.status-pending{ color:orange; font-weight:bold; }
.status-approved{ color:green; font-weight:bold; }
.status-rejected{ color:red; font-weight:bold; }

.btn{
    padding:6px 12px;
    border-radius:6px;
    color:white;
    text-decoration:none;
}

.btn-edit{ background:#3498db; }
.btn-edit:hover{ background:#2980b9; }
.text{
    text-align: center;
}
.btn-delete{
    background:#e74c3c;
    color:white;
}
.btn-delete:hover{
    background:#c0392b;
}

</style>
</head>

<body>

<?php include 'nav.php'; ?>
<link rel="stylesheet" href="stylesheet.css">


<h2 class=text>📋 My Service Requests</h2>


<div class="container">


<table>
<tr>
    <th>Service</th>
    <th>Date</th>
    <th>Time</th>
    <th>People</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php if ($requests->num_rows == 0): ?>
<tr><td colspan="6">No service requests yet.</td></tr>
<?php endif; ?>

<?php while($r = $requests->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($r['service_type']) ?></td>
    <td><?= $r['event_date'] ?></td>
    <td><?= substr($r['time_from'],0,5) ?> → <?= substr($r['time_to'],0,5) ?></td>
    <td><?= (int)$r['people'] ?></td>

    <td class="status-<?= $r['status'] ?>">
        <?= ucfirst($r['status']) ?>
    </td>

   <td class="actions">
    <a href="view_service_request.php?id=<?= $r['id'] ?>"
       class="btn btn-view">👁 View</a>

    <?php if ($r['status'] == 'pending'): ?>
        <a href="edit_service_request.php?id=<?= $r['id'] ?>"
           class="btn btn-edit">✏️ Edit</a>

        <a href="delete_service_request.php?id=<?= $r['id'] ?>"
           class="btn btn-delete"
           onclick="return confirm('Are you sure you want to delete this request?');">
           🗑 Delete
        </a>
    <?php endif; ?>
</td>

</tr>
<?php endwhile; ?>
</table>
</div>

</body>
</html>
