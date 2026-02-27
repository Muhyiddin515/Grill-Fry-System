<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}

$current = basename($_SERVER['PHP_SELF']);


$stmt = $connection->prepare("SELECT profile_image FROM grill_fry_users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();


$defaultAvatar = "/senior_project/web_projecttt/image/default-avatar.png";
$avatarSrc = (!empty($admin['profile_image'])) ? $admin['profile_image'] : $defaultAvatar;
?>

<style>


.dropdown { position: relative; }
.menu-dropdown {
    position: absolute;
    top: 38px;
    left: 0;
    background: white;
    min-width: 180px;
    padding: 10px 0;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    display: none;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.25s ease;
    z-index: 9999;
}
.menu-dropdown.open {
    display: block;
    opacity: 1;
    transform: translateY(0);
}
.menu-dropdown li a {
    padding: 10px 15px;
    display: block;
    color: black;
}
.menu-dropdown li a:hover {
    background-color: #ff511c;
    color: #fff;
}
.arrow {
    display: inline-block;
    transition: transform 0.25s ease;
}
.arrow.rotate {
    transform: rotate(180deg);
}
.nav{
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px 0;
}
.nav .logo h1{
    font-weight: 600;
    font-family: sans-serif;
    color: #000000;
}
.nav .logo b{ color:#ff511c; }
.nav ul{
    display: flex;
    list-style: none;
}
.nav ul li{ margin-right:30px; }
.nav ul li a{
    text-decoration: none;
    color:#000000;
    font-weight: 500;
    font-size:17px;
}
.nav ul .active::after{
    content: '';
    width: 50%;
    height: 3px;
    background-color: #ff511c;
    display: flex;
    position: relative;
    margin-left: 10px;
}
.profile-icon a{
    padding:6px;
    border-radius:60px;
    display:flex;
    align-items:center;
    justify-content:center;
}
.nav-avatar{
    width:34px;
    height:34px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid white;
}
.profile-icon a:hover{ background:#e84343; }
.profile-active{ box-shadow:0 0 0 3px rgba(255,255,255,0.7); }
</style>

<div class="nav">

    <div class="logo"><h1>GRILL<b>&</b><b>FRY</b></h1></div>

    <ul>
        <li><a class="<?= $current == 'admin_dashboard.php' ? 'active' : '' ?>" href="admin_dashboard.php">Dashboard</a></li>
        <li><a class="<?= $current == 'admin.php' ? 'active' : '' ?>" href="admin.php">Bookings</a></li>
        <li><a class="<?= $current == 'employees.php' ? 'active' : '' ?>" href="employees.php">Employees</a></li>
        <li><a class="<?= $current == 'chefs.php' ? 'active' : '' ?>" href="chefs.php">Chefs</a></li>
        <li><a class="<?= $current == 'delivery.php' ? 'active' : '' ?>" href="delivery.php">Delivery</a></li>
        <li><a class="<?= in_array($current, ['items.php','admin_add_item.php']) ? 'active' : '' ?>" href="items.php">Items</a></li>
        <li><a class="<?= in_array($current, ['offers.php','admin_add_offer.php']) ? 'active' : '' ?>" href="offers.php">Offers</a></li>
        <li><a class="<?= $current == 'admin_add_discount.php' ? 'active' : '' ?>" href="admin_add_discount.php">Discount</a></li>
        <li><a class="<?= $current == 'admin_comments.php' ? 'active' : '' ?>" href="admin_comments.php">Comments</a></li>
        <li><a class="<?= $current == 'admin_gallery.php' ? 'active' : '' ?>" href="admin_gallery.php">Gallery</a></li>
        <li><a class="<?= $current == 'money.php' ? 'active' : '' ?>" href="money.php">Money</a></li>
        <li><a class="<?= $current == 'admin_orders.php' ? 'active' : '' ?>" href="admin_orders.php">Orders</a></li>
        <li class="dropdown">

          <a class="dropdown-toggle <?= in_array($current, ['admin_menu.php', 'admin_add_type.php', 'admin_types.php']) ? 'active' : '' ?>">
                Menu <span class="arrow">▼</span>
          </a>

            <ul class="menu-dropdown">
                <li><a href="admin_menu.php">🍽 Admin Menu</a></li>
                <li><a href="admin_add_type.php">➕ Add Type</a></li>
                <li><a href="admin_types.php">📋 Types List</a></li>
            </ul>
        </li>
        
        <li style="margin-left:20px;">
            <label style="cursor:pointer;font-weight:bold;">
                <input type="checkbox" id="voiceToggle">
                🎙 Voice
            </label>
        </li>
        <li>
    <a class="<?= $current == 'admin_service_requests.php' ? 'active' : '' ?>"
       href="admin_service_requests.php">
       Service
    </a>
</li>

        <li class="profile-icon">
            <a href="admin_profile.php" class="<?= $current == 'admin_profile.php' ? 'profile-active' : '' ?>">
                <img src="<?= htmlspecialchars($avatarSrc) ?>" class="nav-avatar">
            </a>
        </li>
    </ul>
</div>

<script>

document.addEventListener("DOMContentLoaded", () => {

    const toggleMenu = document.querySelector(".dropdown-toggle");
    const menu = document.querySelector(".menu-dropdown");
    const arrow = document.querySelector(".arrow");

    if (toggleMenu) {
        toggleMenu.addEventListener("click", (e) => {
            e.preventDefault();
            menu.classList.toggle("open");
            arrow.classList.toggle("rotate");
        });
    }

});


let recognition;
const voiceToggle = document.getElementById("voiceToggle");


if (localStorage.getItem("voiceControl") === "on") {
    voiceToggle.checked = true;
    startVoice();
}

voiceToggle.addEventListener("change", () => {
    if (voiceToggle.checked) {
        localStorage.setItem("voiceControl", "on");
        startVoice();
    } else {
        localStorage.setItem("voiceControl", "off");
        stopVoice();
    }
});

function startVoice() {
    if (!('webkitSpeechRecognition' in window)) {
        alert("Speech recognition not supported");
        return;
    }

    recognition = new webkitSpeechRecognition();
    recognition.lang = "en-US";
    recognition.continuous = true;

    recognition.onresult = function(event) {
        const command = event.results[event.results.length - 1][0].transcript.toLowerCase();

        if (command.includes("booking")) location.href = "admin.php";
        else if (command.includes("employee")) location.href = "employees.php";
        else if (command.includes("chef")) location.href = "chefs.php";
        else if (command.includes("delivery")) location.href = "delivery.php";
        else if (command.includes("item")) location.href = "items.php";
        else if (command.includes("offer")) location.href = "offers.php";
        else if (command.includes("discount")) location.href = "admin_add_discount.php";
        else if (command.includes("comment")) location.href = "admin_comments.php";
        else if (command.includes("order")) location.href = "admin_orders.php";
        else if (command.includes("gallery")) location.href = "admin_gallery.php";
        else if (command.includes("money")) location.href = "money.php";
        else if (command.includes("dashboard")) location.href = "admin_dashboard.php";
        else if (command.includes("service")) location.href = "admin_service_requests.php";
    };

    recognition.onend = function () {
        if (localStorage.getItem("voiceControl") === "on") {
            recognition.start(); 
        }
    };

    recognition.start();
}

function stopVoice() {
    if (recognition) {
        recognition.stop();
        recognition = null;
    }
}
</script>
