<?php
session_start();
require 'connection.php';

if(!isset($_SESSION['user_id'])){
    header("Location: signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];


if(isset($_GET['remove_id'])){
    $remove_id = (int)$_GET['remove_id'];
    $stmt = $connection->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $remove_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: cart.php");
    exit;
}


if(isset($_POST['update_id']) && isset($_POST['quantity'])){
    $update_id = (int)$_POST['update_id'];
    $quantity = (int)$_POST['quantity'];
    if($quantity < 1) $quantity = 1;

    $stmt = $connection->prepare("
        SELECT unit_price FROM cart
        WHERE id=? AND user_id=?
    ");
    $stmt->bind_param("ii", $update_id, $user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $new_total = $row['unit_price'] * $quantity;

    $stmt = $connection->prepare("
        UPDATE cart SET quantity=?, price=?
        WHERE id=? AND user_id=?
    ");
    $stmt->bind_param("idii", $quantity, $new_total, $update_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: cart.php");
    exit;
}



$stmt = $connection->prepare("SELECT * FROM cart WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Cart - GRILL & FRY</title>
<link rel="stylesheet" href="stylesheet.css">
<link rel="stylesheet" href="cart.css">


</head>
<body>
    
<section class="Home">
        <?php include 'nav.php'; ?>
        
</section>
<section class="cart-page">
<section class="container">
    <h1>My Cart</h1>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>special_note</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        while($row = $result->fetch_assoc()){
            $total += $row['price'];
            echo '<tr>
                <td>'.htmlspecialchars($row['item_name']).'</td>
                <td>'.htmlspecialchars($row['type']).'</td>
                <td>
                    <form method="post" style="display:inline-block;">
                        <input type="number" name="quantity" value="'.$row['quantity'].'" min="1">
                        <input type="hidden" name="update_id" value="'.$row['id'].'">
                        <button type="submit" class="update">Update</button>
                    </form>
                </td>
                <td>$'.number_format($row['price'],2).'</td>
                <td>'.htmlspecialchars($row['special_note']).'</td>
                <td><a href="cart.php?remove_id='.$row['id'].'" class="remove">Remove</a></td>
            </tr>';
        }
        ?>
        </tbody>
    </table>
    <p class="total"><b>Total: $<?php echo number_format($total,2); ?></b></p>
    <a href="select_address.php">
    <button type="button" class="checkout-btn">Checkout</button>
</a>





    <script> 
        function addToCart(type, item_name, quantity, price, special_note){
    fetch("add_to_cart.php", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({type, item_name, quantity, price, special_note})
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status=="success") alert("Added to Cart!");
    });
}

    </script>
</section>
</section>

</body>
</html>
