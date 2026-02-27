<?php
session_start();
require 'connection.php';

if(!isset($_SESSION['user_id'])){
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];


if(isset($_POST['add_to_cart'])){
    $item_name = $_POST['item_name'];
    $type = "Drinks";
    $quantity = (int)$_POST['quantity'];
    $unit_price = (float)$_POST['unit_price'];
    $instructions = $_POST['instructions'];
    $total_price = $quantity * $unit_price;

    if($quantity > 0){
        $stmt = $connection->prepare(
            "INSERT INTO cart (user_id, type, item_name, quantity, price, instructions)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("issids", $user_id, $type, $item_name, $quantity, $total_price, $instructions);
        $stmt->execute();
        $stmt->close();
        $msg = "Item added to cart!";
    }
}


$drink_items_stmt = $connection->prepare("SELECT * FROM menu_items WHERE type_id = 5");
$drink_items_stmt->execute();
$drink_items = $drink_items_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cold Drinks Menu</title>
    <link rel="stylesheet" href="sushi.css">

    <style>
        .nav ul .active::after{
            content: '';
            width: 50%;
            height: 3px;
            background-color: #ff511c;
            display: flex;
            position: relative;
            margin-left: 10px;
        }
    </style>
</head>

<body>


<section class="Menu">
    <div class="nav">
        <div class="logo"><h1>GRILL<b>&</b>FRY</h1></div>
        <ul>
            <li><a href="afterlogin.php">Home</a></li>
            <li><a href="menu.php" style="background:black;color:white;padding:10px 15px;border-radius:6px;">Menu</a></li>
            <li><a href="sushi.php">Sushi</a></li>
            <li><a href="burger.php">Burgers</a></li>
            <li><a href="pizza.php">Pizza</a></li>
            <li><a href="meat.php">Meats</a></li>
            <li><a class="active" href="drinks.php">Drinks</a></li>
            <li><a href="voiceorder.html">Voice Order</a></li>
        <li><a href="services.php">Service</a></li>
        <li><a href="aboutus.html">About Us</a></li>
        <li><a href="gallery.html">Gallery</a></li>
        <li><a href="mybooking.php">My Booking</a></li>
        <li><a href="cart.php" style="background:#ff4d4d;color:white;padding:10px 15px;border-radius:6px;">🛒 Cart</a></li>
        <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</section>

<br><br>

<div class="menu-grid">

<?php while($item = $drink_items->fetch_assoc()): ?>

<div class="menu-item" data-price="<?= $item['price'] ?>">
    <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['item_name']) ?>">
    <h3 class="item-name"><?= htmlspecialchars($item['item_name']) ?></h3>

    <div class="counter">
        <button class="minus">-</button>
        <span class="count">0</span>
        <button class="plus">+</button>
    </div>

    <p class="price">Price: $0</p>
    <p class="ingredients"><?= htmlspecialchars($item['description']) ?></p>

    <form method="post">
        <input type="hidden" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>">
        <input type="hidden" name="unit_price" value="<?= $item['price'] ?>">
        <input type="hidden" class="php-qty" name="quantity" value="0">

        <textarea name="instructions" placeholder="Special instructions..."></textarea><br>

        <button type="submit" name="add_to_cart" class="addToCart">Add to Cart</button>
    </form>
</div>

<br><br>
<?php endwhile; ?>
</div>

<?php
if(isset($msg)){
    echo "<p style='color:green;text-align:center;font-weight:bold;'>$msg</p>";
}
?>

<script>
document.querySelectorAll(".menu-item").forEach(item => {
    let plus = item.querySelector(".plus");
    let minus = item.querySelector(".minus");
    let count = item.querySelector(".count");
    let phpQty = item.querySelector(".php-qty");
    let price = item.querySelector(".price");
    let unitPrice = parseFloat(item.dataset.price);

    plus.addEventListener("click", () => {
        count.textContent = Number(count.textContent) + 1;
        phpQty.value = count.textContent;
        price.textContent = "Price: $" + (count.textContent * unitPrice);
    });

    minus.addEventListener("click", () => {
        if(count.textContent > 0){
            count.textContent = Number(count.textContent) - 1;
            phpQty.value = count.textContent;
            price.textContent = "Price: $" + (count.textContent * unitPrice);
        }
    });
});
</script>

</body>
</html>
