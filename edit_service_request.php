<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = (int)($_GET['id'] ?? 0);

/* FETCH REQUEST */
$stmt = $connection->prepare("
    SELECT *
    FROM service_requests
    WHERE id = ? AND user_id = ? AND status = 'pending'
");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$request = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$request) {
    die("You cannot edit this request.");
}

/* UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $service_type = $_POST['service_type'];
    $event_date   = $_POST['event_date'];
    $time_from    = $_POST['time_from'];
    $time_to      = $_POST['time_to'];
    $people       = (int)$_POST['people'];
    $notes        = $_POST['notes'];

    $upd = $connection->prepare("
        UPDATE service_requests
        SET service_type=?, event_date=?, time_from=?, time_to=?, people=?, notes=?
        WHERE id=? AND user_id=?
    ");

    $upd->bind_param(
        "ssssissi",
        $service_type,
        $event_date,
        $time_from,
        $time_to,
        $people,
        $notes,
        $id,
        $user_id
    );
    $upd->execute();
    $upd->close();

    header("Location: my_service_requests.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Service Request</title>
<style>
.form-box{
    width:500px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.2);
}
input, select, textarea, button{
    width:100%;
    padding:12px;
    margin:8px 0;
}
button{
    background:#ff511c;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
.text{
    text-align: center;
}
</style>
</head>

<body>
<link rel="stylesheet" href="stylesheet.css">
<?php include 'nav.php'; ?>
<div class = text><h2>✏️Edit Service Request</h2></div>

<div class="form-box">


<form method="POST">

<select name="service_type" required>
    <option <?= $request['service_type']=='Dine-In'?'selected':'' ?>>Dine-In</option>
    <option <?= $request['service_type']=='Catering'?'selected':'' ?>>Catering</option>
    <option <?= $request['service_type']=='Delivery'?'selected':'' ?>>Delivery</option>
    <option <?= $request['service_type']=='Special Event'?'selected':'' ?>>Special Event</option>
</select>

<input type="date" name="event_date" value="<?= $request['event_date'] ?>" required>

<input type="time" name="time_from" value="<?= $request['time_from'] ?>" required>
<input type="time" name="time_to" value="<?= $request['time_to'] ?>" required>

<input type="number" name="people" value="<?= $request['people'] ?>" min="1">

<textarea name="notes"><?= htmlspecialchars($request['notes']) ?></textarea>

<button type="submit">Update Request</button>
</form>
</div>

</body>
</html>
