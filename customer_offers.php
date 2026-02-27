<?php
require_once 'connection.php';

$today = strtolower(date("l")); 

$query = "SELECT * FROM offers 
          WHERE is_active = 1 
          AND (show_day = 'all' OR show_day = ?)
          ORDER BY id DESC";

$stmt = $connection->prepare($query);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>Special Offers</title>

<style>
body { font-family: Arial; background:#fff3e6; margin:0; }
.header { background:#ff7b00; padding:20px; text-align:center; color:white; }
.back-btn{ background:white;color:#ff7b00;padding:10px 15px;border-radius:6px;cursor:pointer; }
.offer-container{ width:90%;margin:20px auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px; }
.offer-card{ background:white;padding:15px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,.2);text-align:center; }
.offer-card img{ width:100%;height:170px;border-radius:8px;object-fit:cover;cursor:pointer; }
.old-price{ text-decoration:line-through;color:red;font-weight:bold; }
.new-price{ color:green;font-size:22px;font-weight:bold; }


.popup-bg{ display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);justify-content:center;align-items:center; }
.popup-box{ background:white;width:320px;padding:15px;border-radius:10px;text-align:center; }
.popup-box img{ width:100%;height:170px;border-radius:8px;object-fit:cover; }
.add-btn{ background:#ff7b00;color:white;padding:10px 18px;border:none;border-radius:6px;margin-top:10px;cursor:pointer; }
.close-btn{ background:red;color:white;padding:8px 14px;border:none;border-radius:6px;margin-top:10px;cursor:pointer; }
</style>

</head>
<body>

<div class="header">
    <h1>🔥 Special Offers 🔥</h1>
    <button class="back-btn" onclick="window.location.href='menu.php'">⬅ Back to Menu</button>
</div>

<div class="offer-container">
<?php while($offer = $result->fetch_assoc()): 
$image = $offer['image'] ? $offer['image'] : "default.jpg";
$title = htmlspecialchars($offer['title'], ENT_QUOTES);
$desc = htmlspecialchars($offer['description'], ENT_QUOTES);
?>
    <div class="offer-card">
        <img src="uploads/offers/<?php echo $image; ?>"
            onclick="openPopup('<?php echo $title; ?>','<?php echo $desc; ?>','<?php echo $offer['old_price']; ?>','<?php echo $offer['new_price']; ?>','<?php echo $image; ?>')">

        <h3><?php echo $offer['title']; ?></h3>
        <p><?php echo $offer['description']; ?></p>
        <p class="old-price">$<?php echo $offer['old_price']; ?></p>
        <p class="new-price">$<?php echo $offer['new_price']; ?></p>
    </div>
<?php endwhile; ?>
</div>


<div class="popup-bg" id="popupBg">
    <div class="popup-box">
        <img id="popupImg">
        <h3 id="popupTitle"></h3>
        <p id="popupDesc"></p>
        <p class="old-price">$<span id="popupOld"></span></p>
        <p class="new-price">$<span id="popupNew"></span></p>

        <label>Quantity:</label><br>
        <input type="number" id="popupQty" value="1" min="1">

        <button class="add-btn" onclick="addOfferToCart()">Add to Cart</button>
        <button class="close-btn" onclick="closePopup()">Close</button>
    </div>
</div>

<script>
let offerName="", offerPrice=0;

function openPopup(t,d,oldP,newP,img){
    offerName=t;
    offerPrice=newP;

    document.getElementById("popupImg").src="uploads/offers/"+img;
    document.getElementById("popupTitle").innerHTML=t;
    document.getElementById("popupDesc").innerHTML=d;
    document.getElementById("popupOld").innerHTML=oldP;
    document.getElementById("popupNew").innerHTML=newP;

    document.getElementById("popupBg").style.display="flex";
}
function closePopup(){
    document.getElementById("popupBg").style.display="none";
}
function addOfferToCart(){
    let qty=parseInt(document.getElementById("popupQty").value);

    fetch("add_to_cart.php",{
        method:"POST",
        headers:{"Content-Type":"application/json"},
        body:JSON.stringify({
            item_name:offerName,
            quantity:qty,
            price:offerPrice*qty,
            instructions:"-"
        })
    })
    .then(r=>r.json())
    .then(d=>{
        if(d.status=="success"){
            alert("Added to cart!");
            closePopup();
        }
    })
}
</script>

</body>
</html>
