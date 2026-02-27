<?php
require "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRILL & FRY menu</title>
<link rel="stylesheet" href="menu.css?v=<?php echo time(); ?>">
</head>
<body>

<section class="Menu">
    <?php include 'nav.php'; ?>

    

    <section class="menupage">
        <button onclick="window.location.href='customer_offers.php'">View Offers</button>
        <br><br>

        <ul class="menu-list">


            <?php
            $q = $connection->query("SELECT * FROM types WHERE is_active = 1 ORDER BY id DESC");


            while ($t = $q->fetch_assoc()):
            ?>
                <li>
                    <a class="menu-item dynamic" href="category_items.php?type_id=<?= $t['id'] ?>">
                        <img src="<?= $t['image'] ?>" class="menu-img">
                        <span><?= htmlspecialchars($t['type_name']) ?></span>
                    </a>
                </li>
            <?php endwhile; ?>

        </ul>
    </section>

</section>

</body>
</html>
