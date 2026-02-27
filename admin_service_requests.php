<?php
session_start();
require 'connection.php';

/* ADMIN ONLY */
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}
/* COUNTERS */
$counts = [
    'pending' => 0,
    'approved' => 0,
    'rejected' => 0
];

$cq = $connection->query("
    SELECT status, COUNT(*) as total
    FROM service_requests
    GROUP BY status
");

while ($c = $cq->fetch_assoc()) {
    $counts[$c['status']] = $c['total'];
}

/* UPDATE STATUS */
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if (in_array($action, ['approved','rejected'])) {
        $stmt = $connection->prepare("
            UPDATE service_requests 
            SET status = ? 
            WHERE id = ?
        ");
        $stmt->bind_param("si", $action, $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin_service_requests.php");
    exit;
}
$status_filter = $_GET['status'] ?? '';
$search = trim($_GET['search'] ?? '');

$sql = "
    SELECT sr.*, u.name AS user_name
    FROM service_requests sr
    JOIN grill_fry_users u ON sr.user_id = u.id
    WHERE 1
";

$params = [];
$types = "";


if (in_array($status_filter, ['pending','approved','rejected'])) {
    $sql .= " AND sr.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}


if ($search !== '') {
    $sql .= " AND (u.name LIKE ? OR sr.service_type LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}

$sql .= " ORDER BY sr.created_at DESC";

$stmt = $connection->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

/* FETCH REQUESTS 
$result = $connection->query("
    SELECT sr.*, u.name AS user_name
    FROM service_requests sr
    JOIN grill_fry_users u ON sr.user_id = u.id
    ORDER BY sr.created_at DESC
");*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Service Requests</title>

<style>
body{
    background:wheat;
    font-family: Arial, sans-serif;
}

.container{
    width: 95%;
    margin: 40px auto;
}

h2{
    text-align:center;
    margin-bottom:30px;
}


table{
    width:100%;
    border-collapse: collapse;
    background:#fff;
    box-shadow:0 10px 25px rgba(0,0,0,.2);
}

th, td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
    font-size:14px;
}

th{
    background:#ff511c;
    color:white;
}


.status-pending{ color:orange; font-weight:bold; }
.status-approved{ color:green; font-weight:bold; }
.status-rejected{ color:red; font-weight:bold; }


.btn{
    padding:6px 14px;
    border-radius:8px;
    text-decoration:none;
    color:white;
    font-weight:bold;
    margin:2px;
    display:inline-block;
}

.btn-approve{ background:#28a745; }
.btn-reject{ background:#e74c3c; }

.btn-approve:hover{ background:#218838; }
.btn-reject:hover{ background:#c0392b; }


.notes{
    max-width:220px;
    font-size:13px;
    color:#444;
}

.stats{
    display:flex;
    gap:20px;
    justify-content:center;
    margin-bottom:25px;
}

.stat{
    padding:14px 24px;
    border-radius:14px;
    background:#fff;
    box-shadow:0 6px 18px rgba(0,0,0,.15);
    font-weight:bold;
    text-align:center;
}

.stat.pending{ border-left:6px solid orange; }
.stat.approved{ border-left:6px solid green; }
.stat.rejected{ border-left:6px solid red; }


.filter-bar{
    display:flex;
    gap:12px;
    justify-content:center;
    margin-bottom:25px;
}

.filter-bar select,
.filter-bar input{
    padding:10px 14px;
    border-radius:10px;
    border:1px solid #ccc;
}

.filter-bar button{
    background:#ff511c;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:10px;
    cursor:pointer;
}


.row-pending{
    background:#fff8e6;
}

</style>
</head>

<body>
 
<?php include 'admin_nav.php'; ?>

<div class="container">

<h2>📋 Service Requests Management</h2>
<div class="stats">
    <div class="stat pending">Pending<br><b><?= $counts['pending'] ?></b></div>
    <div class="stat approved">Approved<br><b><?= $counts['approved'] ?></b></div>
    <div class="stat rejected">Rejected<br><b><?= $counts['rejected'] ?></b></div>
</div>

<form method="GET" class="filter-bar">
    <select name="status">
        <option value="">All Status</option>
        <option value="pending" <?= $status_filter=='pending'?'selected':'' ?>>Pending</option>
        <option value="approved" <?= $status_filter=='approved'?'selected':'' ?>>Approved</option>
        <option value="rejected" <?= $status_filter=='rejected'?'selected':'' ?>>Rejected</option>
    </select>

    <input type="text" name="search"
           placeholder="Search by user or service"
           value="<?= htmlspecialchars($search) ?>">

    <button type="submit">Filter</button>
</form>

<table>
<thead>
<tr>
    <th>customer</th>
    <th>Service</th>
    <th>Date</th>
    <th>Time</th>
    <th>People</th>
    <th>Notes</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($result->num_rows == 0): ?>
<tr>
    <td colspan="8">No service requests found.</td>
</tr>
<?php else: ?>
<?php while($r = $result->fetch_assoc()): ?>
<tr class="<?= $r['status'] == 'pending' ? 'row-pending' : '' ?>">

    <td><?= htmlspecialchars($r['user_name']) ?></td>
    <td><?= htmlspecialchars($r['service_type']) ?></td>
    <td><?= $r['event_date'] ?></td>
    <td><?= substr($r['time_from'],0,5) ?> → <?= substr($r['time_to'],0,5) ?></td>
    <td><?= (int)$r['people'] ?></td>
    <td class="notes"><?= nl2br(htmlspecialchars($r['notes'])) ?></td>

    <td class="status-<?= $r['status'] ?>">
        <?= ucfirst($r['status']) ?>
    </td>

    <td>
        <?php if ($r['status'] == 'pending'): ?>
            <a class="btn btn-approve"
               href="?action=approved&id=<?= $r['id'] ?>">
               Approve
            </a>

            <a class="btn btn-reject"
               href="?action=rejected&id=<?= $r['id'] ?>"
               onclick="return confirm('Reject this request?');">
               Reject
            </a>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>

</tr>
<?php endwhile; ?>

<?php endif; ?>
</tbody>
</table>

</div>

</body>
</html>
