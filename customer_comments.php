<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];


if (isset($_POST['rating'], $_POST['comment'])) {
    $stmt = $connection->prepare("
        INSERT INTO reviews (user_id, rating, comment)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iis", $user_id, $_POST['rating'], $_POST['comment']);
    $stmt->execute();
    $msg = "Thank you! Your comment is waiting for admin approval.";
}


$approved = $connection->query("
SELECT r.rating, r.comment, r.created_at, u.name
FROM reviews r
JOIN grill_fry_users u ON r.user_id = u.id
WHERE r.status = 'approved'
ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Comments & Ratings</title>

<link rel="stylesheet" href="stylesheet.css">
<link rel="stylesheet" href="order_style.css">

<style>

.star-rating {
    display: flex;
    justify-content: center;
    font-size: 38px;
    cursor: pointer;
    margin: 20px 0;
}

.star {
    color: #ccc;
    transition: color 0.2s;
}

.star.active {
    color: #ffb400;
}


.review-box {
    background: white;
    padding: 20px;
    margin: 20px auto;
    max-width: 700px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,.15);
}

.review-stars {
    color: #ffb400;
    font-size: 18px;
}

.review-date {
    font-size: 12px;
    color: gray;
}
</style>
</head>

<body>

<?php include 'nav.php'; ?>

<div class="page-container">
    <h1 class="page-title">⭐ Rate Our Service</h1>

    <?php if (!empty($msg)): ?>
        <p style="color:green;text-align:center;"><?php echo $msg; ?></p>
    <?php endif; ?>

    
    <form method="post" style="max-width:500px;margin:30px auto;">

        <label style="display:block;text-align:center;font-weight:bold;">
            Rating
        </label>

        <div class="star-rating" id="stars">
            <span class="star" data-value="1">★</span>
            <span class="star" data-value="2">★</span>
            <span class="star" data-value="3">★</span>
            <span class="star" data-value="4">★</span>
            <span class="star" data-value="5">★</span>
        </div>

        <input type="hidden" name="rating" id="rating" required>

        <label>Comment</label>
        <textarea name="comment" required style="width:100%;height:120px;"></textarea>

        <br><br>
        <button class="btn btn-dark" style="width:100%;">Submit</button>
    </form>

    <hr style="margin:50px 0;">

    
    <h2 style="text-align:center;">💬 Customer Reviews</h2>

    <?php if ($approved->num_rows == 0): ?>
        <p style="text-align:center;">No reviews yet.</p>
    <?php else: ?>
        <?php while ($row = $approved->fetch_assoc()): ?>
            <div class="review-box">
                <b><?php echo htmlspecialchars($row['name']); ?></b><br>

                <div class="review-stars">
                    <?php echo str_repeat("⭐", $row['rating']); ?>
                </div>

                <p><?php echo htmlspecialchars($row['comment']); ?></p>

                <div class="review-date">
                    <?php echo $row['created_at']; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>


<script>
const stars = document.querySelectorAll('.star');
const ratingInput = document.getElementById('rating');

stars.forEach(star => {
    star.addEventListener('click', () => {
        const value = star.getAttribute('data-value');
        ratingInput.value = value;

        stars.forEach(s => {
            s.classList.toggle(
                'active',
                s.getAttribute('data-value') <= value
            );
        });
    });
});
</script>

</body>
</html>
