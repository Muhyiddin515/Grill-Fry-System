<?php
session_start();

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: signin.html");
    exit;
}

require 'connection.php';

$user_id = $_SESSION['user_id'];

$imgs = $connection->query("
    SELECT *
    FROM images
    WHERE section = 'our_menu'
      AND is_active = 1
    ORDER BY position ASC
");



$stmt = $connection->prepare("SELECT name, phone FROM grill_fry_users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name, $user_phone);
$stmt->fetch();
$stmt->close();

$msg = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $customer_name  = trim($_POST['customer_name']);
    $customer_phone = trim($_POST['customer_phone']);

    $people = (int)$_POST['people'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $payment_type = $_POST['payment_type'];
    $table_number = (int)$_POST['table_number'];

  
    $check = $connection->prepare("
        SELECT id 
        FROM grill_fry_bookings
        WHERE table_number = ?
          AND date = ?
          AND time = ?
          AND canceled = 0
    ");
    $check->bind_param("iss", $table_number, $date, $time);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){

        $response = [
            'status' => 'error',
            'message' => '⛔ Time conflict! Please choose another table or another time.'
        ];

    } else {

       
        $stmt = $connection->prepare("
            INSERT INTO grill_fry_bookings 
            (user_id, name, phone, people, date, time, table_number, payment_type, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->bind_param(
            "ississis",
            $user_id,
            $customer_name,
            $customer_phone,
            $people,
            $date,
            $time,
            $table_number,
            $payment_type
        );

        $stmt->execute();

        
        $booking_id = $stmt->insert_id;
        $stmt->close();

       
        $booking_number = "BK-" . str_pad($booking_id, 5, "0", STR_PAD_LEFT);

      
        $update = $connection->prepare("
            UPDATE grill_fry_bookings
            SET booking_number = ?
            WHERE id = ?
        ");
        $update->bind_param("si", $booking_number, $booking_id);
        $update->execute();
        $update->close();

        $response = [
            'status' => 'success',
            'message' => '✅ Booking submitted successfully!',
            'booking_number' => $booking_number
        ];
    }

    $check->close();

    
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])){
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $msg = $response['message'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Menu - GRILL & FRY</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<?php include 'nav.php'; ?>

<div style="text-align:center; margin:40px 0;">
    <h2>Welcome, <span style="color:#ff511c;">
        <?php echo htmlspecialchars($user_name); ?>
    </span> 👋</h2>
</div>
<br><br>

<div style="
text-align:center;
 margin-bottom:30px;
 ">
    <button 
onclick="document.getElementById('booking').scrollIntoView({behavior:'smooth'})"
style="
position:fixed;
bottom:25px;
right:25px;
background:#ff511c;
color:white;
padding:14px 20px;
border:none;
border-radius:50px;
font-size:15px;
box-shadow:0 4px 10px rgba(0,0,0,0.3);
cursor:pointer;
z-index:9999;
">
📅 Book Table
</button>







<section class="menu-grid" style="
    display:grid;
    grid-template-columns: repeat(4, 250px);
    gap:30px;
    justify-content:center;
">
    <?php while($img = $imgs->fetch_assoc()): ?>
        <div class="menu-item">

            <img src="<?= $img['image_path'] ?>" style="width:230px;height:200px;">
            <p><?= htmlspecialchars($img['title']) ?></p>
        </div>
    <?php endwhile; ?>
</section>


<br><br>
<div class="form-container">
    <form action="menu.php">
        <button type="submit">View More</button>
    </form>
</div>
<br><br>

<section class="about">
    <div class="about-image"><img src="image/about-img.png" alt=""></div>
    <div>
        <p><b>We are GRILL & FRY</b><br>
        Heart, family, soul, and profound roots.<br>
        Warmth and genuine hospitality.<br>
        Authentic cuisine woven into tradition.<br>
        Nurturing care and unwavering passion.<br>
        Foodio is more than just a space to experience life; <br>
        it’s a narrative of meaningful connections.<br>
        It’s not merely a residence; it’s your abode, and the story of your journey.
        </p>
        <div class="readmore">
            <form action="aboutus.php" method="get">
                <button type="submit">Read More</button>
            </form>
        </div>
    </div>
</section>
<br><br>

<section class="booking" id="booking">

    <h2>Book a Table </h2>
    <p id="booking-msg" style="font-weight:bold;"></p>


    


    <form method="post" id="booking-form">


       <div class="form-group">
    <label>Your Name:</label>
    <input type="text" name="customer_name" placeholder="Enter your name" required>
</div>

<div class="form-group">
    <label>Phone Number:</label>
    <input type="tel" name="customer_phone" placeholder="Enter your phone number" required>
</div>


        <div class="form-group">
            <label>Number of People:</label>
            <input type="number" name="people" min="1" required>
        </div>

        <div class="form-group">
            <label>Table Number:</label>
            <input type="number" name="table_number" min="1" required>
        </div>

        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" required>
        </div>

        <div class="form-group">
            <label>Time:</label>
            <input type="time" name="time" required>
        </div>

        <div class="form-group">
            <label>Payment Type:</label>
            <select name="payment_type" required>
                <option value="">--Select--</option>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="online">Online</option>
            </select>
        </div>

        <div class="form-group">
            <input type="submit" value="Submit Booking">
        </div>
    </form>
</section>

<div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3279.789734819645!2d36.084437490554116!3d34.56676733232888!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15222535756dcc09%3A0x51f355e466669498!2sLIU-Akkar!5e0!3m2!1sen!2slb!4v1764508561125!5m2!1sen!2slb" 
    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</div>

<section class="footer">
    <footer>
        <div>
            <a href="#" target="_blank">Instagram</a> |
            <a href="mailto:contact@example.com">Email</a> |
            Phone: +1234567890 |
            Location: LIU, Lebanon
        </div>
        <div>&copy; 2025 GRILL & FRY</div>
    </footer>
</section>

<script>
document.getElementById('booking-form').addEventListener('submit', function(e){
    e.preventDefault(); 

    const formData = new FormData(this);

    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        const msg = document.getElementById('booking-msg');
        msg.innerText = data.message;
        msg.style.color = data.status === 'error' ? 'red' : 'green';

        if(data.status === 'success'){
            document.getElementById('booking-form').reset();
        }
    })
    .catch(() => {
        alert('Server error');
    });
});
</script>

</body>
</html>
