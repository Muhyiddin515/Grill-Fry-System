<?php
session_start();
require "connection.php";


$discount_q = $connection->query("
    SELECT discount_name, discount_percent, start_date, end_date
    FROM global_discounts
    WHERE is_active = 1
      AND CURDATE() BETWEEN start_date AND end_date
    LIMIT 1
");
$discount = $discount_q->fetch_assoc();


if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

if (!isset($_GET['type_id'])) {
    die("No type selected.");
}

$type_id = (int)$_GET['type_id'];

$q = $connection->prepare("SELECT type_name FROM types WHERE id = ? AND is_active = 1");
$q->bind_param("i", $type_id);
$q->execute();
$type = $q->get_result()->fetch_assoc();

if (!$type) {
    die("This category is not available.");
}

$type_name = $type['type_name'];

/* ======================
   ITEMS
====================== */
$items_q = $connection->prepare("
    SELECT *
    FROM menu_items
    WHERE type_id = ?
      AND is_active = 1
");
$items_q->bind_param("i", $type_id);
$items_q->execute();
$items = $items_q->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= htmlspecialchars($type_name) ?> Menu</title>
    <link rel="stylesheet" href="sushi.css">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>

<?php include 'nav.php'; ?>
<div class="category-search">
    <input type="text" id="itemSearch"
           placeholder="🔍 Search in <?= htmlspecialchars($type_name) ?> ..."
           autocomplete="off">
</div>

<div class="menu-grid">

<?php while ($item = $items->fetch_assoc()): ?>

<?php
$old_price = (float)$item['price'];
$new_price = $old_price;

if ($discount) {
    $new_price = round(
        $old_price * (1 - $discount['discount_percent'] / 100),
        2
    );
}
?>

<div class="menu-item"
     data-price="<?= $new_price ?>"
     data-name="<?= strtolower($item['item_name']) ?>">


    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['item_name']) ?>">

    <h3 class="item-name"><?= htmlspecialchars($item['item_name']) ?></h3>

    <div class="counter">
        <button class="minus">-</button>
        <span class="count">1</span>
        <button class="plus">+</button>
    </div>

    <!-- PRICE BLOCK -->
    <p class="price">
        <?php if ($discount): ?>
            <span style="text-decoration:line-through;color:#999;">
                $<?= number_format($old_price, 2) ?>
            </span><br>

            <span style="color:#ff511c;font-weight:bold;">
                $<?= number_format($new_price, 2) ?>
            </span><br>

            <small>
                <?= htmlspecialchars($discount['discount_name']) ?>
                (<?= (int)$discount['discount_percent'] ?>% OFF)<br>
                <span style="color:#666;">
                    From <?= date('d M Y', strtotime($discount['start_date'])) ?>
                    to <?= date('d M Y', strtotime($discount['end_date'])) ?>
                </span>
            </small>
        <?php else: ?>
            $<?= number_format($old_price, 2) ?>
        <?php endif; ?>
    </p>

    <p class="ingredients"><?= htmlspecialchars($item['description']) ?></p>
<textarea class="special-note"
          placeholder="📝 Special instructions (optional)"></textarea>

    <button class="addToCart"
       onclick="addToCart(
    '<?= htmlspecialchars($item['item_name']) ?>',
    parseInt(this.parentElement.querySelector('.count').textContent),
    parseFloat(this.closest('.menu-item').dataset.price),
    '<?= htmlspecialchars($type_name) ?>',
    this.parentElement.querySelector('img'),
    this.parentElement.querySelector('.special-note').value
)"
>
        Add to Cart
    </button>

</div>

<?php endwhile; ?>

</div>

<script>
function animateToCart(img) {
    const cart = document.querySelector(".cart-icon a");
    let clone = img.cloneNode(true);
    let rect = img.getBoundingClientRect();

    clone.style.position = "fixed";
    clone.style.left = rect.left + "px";
    clone.style.top = rect.top + "px";
    clone.style.width = "100px";
    clone.style.transition = "all .7s ease";
    clone.style.zIndex = "99999";

    document.body.appendChild(clone);

    setTimeout(() => {
        let cartRect = cart.getBoundingClientRect();
        clone.style.left = cartRect.left + "px";
        clone.style.top = cartRect.top + "px";
        clone.style.opacity = "0";
        clone.style.transform = "scale(0.2)";
    }, 50);

    setTimeout(() => clone.remove(), 800);
}

function addToCart(item, qty, price, typeName, imgElement, note) {
    animateToCart(imgElement);

    fetch("add_to_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            item_name: item,
            quantity: qty,
            price: price * qty,
            type: typeName,
            special_note: note
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === "success") {
            alert("Added to Cart ✔");
        }
    });
}

document.querySelectorAll(".menu-item").forEach(item => {
    let plus = item.querySelector(".plus");
    let minus = item.querySelector(".minus");
    let count = item.querySelector(".count");
    let priceBox = item.querySelector(".price");
    let unitPrice = parseFloat(item.dataset.price);

    plus.onclick = () => {
        count.textContent++;
        priceBox.querySelector("span[style*='ff511c']").textContent =
            "$" + (count.textContent * unitPrice).toFixed(2);
    };

    minus.onclick = () => {
        if (count.textContent > 1) {
            count.textContent--;
            priceBox.querySelector("span[style*='ff511c']").textContent =
                "$" + (count.textContent * unitPrice).toFixed(2);
        }
    };
});

const searchInput = document.getElementById("itemSearch");
const items = document.querySelectorAll(".menu-item");

searchInput.addEventListener("keyup", () => {
    const keyword = searchInput.value.toLowerCase();

    items.forEach(item => {
        const name = item.dataset.name;
        item.style.display = name.includes(keyword) ? "block" : "none";
    });
});


</script>

</body>
</html>
