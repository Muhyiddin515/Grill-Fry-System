<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['address_id'])) {
    header("Location: cart.php");
    exit;
}

$address_id = (int)$_GET['address_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Method - GRILL & FRY</title>

<link rel="stylesheet" href="stylesheet.css">
<link rel="stylesheet" href="order_style.css">

<style>
.payment-box {
    background: white;
    padding: 25px;
    border-radius: 15px;
    width: 400px;
    margin: 15px auto;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
    text-align: center;
}
.wish-box {
    display: none;
    margin-top: 20px;
}
.wish-box p {
    background: #fff3f3;
    padding: 15px;
    border-radius: 10px;
    border: 1px dashed #ff4d4d;
}
</style>
</head>

<body>

<?php include 'nav.php'; ?>

<div class="page-container">
    <h1 class="page-title">💳 Choose Payment Method</h1>

    <form method="post" action="checkout.php" enctype="multipart/form-data">

       
        <input type="hidden" name="address_id" value="<?= $address_id ?>">

        <div class="payment-box">
            <label>
                <input type="radio" name="payment_method" value="Cash on Delivery" required onclick="toggleWish(false)">
                💵 Cash on Delivery
            </label>
        </div>

        <div class="payment-box">
            <label>
                <input type="radio" name="payment_method" value="Wish Money" onclick="toggleWish(true)">
                📱 Wish Money
            </label>

            <div class="wish-box" id="wishBox">
                <p>
                    📲 Please send the amount to:<br>
                    <b>Wish Number: 03 123 456</b>
                </p>

                <input type="file" name="voucher" accept="image/*">
            </div>
        </div>

        <div class="payment-box">
            <label>
                <input type="radio" name="payment_method" value="Card" onclick="toggleWish(false)">
                💳 Card
            </label>
        </div>

        <br>
        <button type="submit" class="btn btn-dark">Confirm Payment</button>
    </form>
</div>

<script>
function toggleWish(show) {
    document.getElementById("wishBox").style.display = show ? "block" : "none";
}
</script>

</body>
</html>
