<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'connection.php';

$current = basename($_SERVER['PHP_SELF']);


$navUser = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $connection->prepare("
        SELECT name, avatar 
        FROM grill_fry_users 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $navUser = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$nav_types = $connection->query("SELECT * FROM types ORDER BY type_name");
?>

<style>

.nav ul li a.active::after{
    content:'';
    display:block;
    width:50%;
    height:3px;
    background:#ff511c;
    margin:6px auto 0 auto;
    border-radius:2px;
}


.menu-parent, .profile-parent { position: relative; }

.menu-dropdown, .profile-dropdown{
    position:absolute;
    top:45px;
    background:white;
    list-style:none;
    padding:0;
    margin:0;
    border-radius:6px;
    box-shadow:0 0 10px #0003;
    opacity:0;
    pointer-events:none;
    transform:translateY(-10px);
    transition:.25s;
    z-index:999;
}

.menu-parent.open .menu-dropdown,
.profile-parent.open .profile-dropdown{
    opacity:1;
    transform:translateY(0);
    pointer-events:auto;
}

.menu-dropdown{ left:0; min-width:160px; }
.profile-dropdown{ right:0; min-width:180px; }

.menu-dropdown li a,
.profile-dropdown li a{
    display:block;
    padding:10px;
    color:black;
    text-decoration:none;
}

.menu-dropdown li a:hover,
.profile-dropdown li a:hover{
    background:#ff511c;
    color:white;
}


.profile-trigger{
    display:flex;
    align-items:center;
    gap:6px;
    cursor:pointer;
}
.profile-trigger img{
    width:34px;
    height:34px;
    border-radius:50%;
    object-fit:cover;
}

.voice-toggle{
    margin-left:10px;
    font-weight:bold;
}
</style>

<div class="nav">
    <div class="logo"><h1>GRILL<b>&</b><b>FRY</b></h1></div>

    <ul>
        <li>
            <a href="afterlogin.php"
               class="<?= $current=='afterlogin.php'?'active':'' ?>"
               data-voice="home">Home</a>
        </li>

       
        <li class="menu-parent">
            <a href="#"
               id="menuToggle"
               class="<?= in_array($current,['menu.php','category_items.php'])?'active':'' ?>"
               data-voice="menu">
                Menu <span id="menuArrow">▼</span>
            </a>
            <ul class="menu-dropdown" id="menuDropdown">
                <li>
                    <a href="menu.php"
                       class="<?= $current=='menu.php'?'active':'' ?>"
                       data-voice="all menu">All Menu</a>
                </li>
                <?php while($m = $nav_types->fetch_assoc()): ?>
                    <li>
                        <a href="category_items.php?type_id=<?= $m['id'] ?>"
                           data-voice="<?= strtolower($m['type_name']) ?>">
                            <?= htmlspecialchars($m['type_name']) ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </li>

        <li>
            <a href="voiceorder.php"
               class="<?= $current=='voiceorder.php'?'active':'' ?>"
               data-voice="voice order">Voice Order</a>
        </li>

        <li>
            <a href="services.php"
               class="<?= $current=='services.php'?'active':'' ?>"
               data-voice="service">Service</a>
        </li>

        <li>
            <a href="customer_comments.php"
               class="<?= $current=='customer_comments.php'?'active':'' ?>"
               data-voice="comments">Comments</a>
        </li>

        <li>
            <a href="aboutus.php"
               class="<?= $current=='aboutus.php'?'active':'' ?>"
               data-voice="about">About Us</a>
        </li>

        <li>
            <a href="gallery.php"
               class="<?= $current=='gallery.php'?'active':'' ?>"
               data-voice="gallery">Gallery</a>
        </li>

        <li>
            <a href="mybooking.php"
               class="<?= $current=='mybooking.php'?'active':'' ?>"
               data-voice="booking">My Booking</a>
        </li>

        <li class="cart-icon">
            <a href="cart.php"
               class="<?= $current=='cart.php'?'active':'' ?>"
               data-voice="cart"
               style="background:#ff4d4d;color:white;padding:10px 15px;border-radius:6px;">
               🛒 Cart
            </a>
        </li>

     
        <li class="voice-toggle">
            <label>
                <input type="checkbox" id="voiceToggle"> 🎙 Voice
            </label>
        </li>

        
        <?php if ($navUser): ?>
        <li class="profile-parent">
            <a href="#"
               id="profileToggle"
               class="profile-trigger <?= in_array($current,['profile.php','my_orders.php'])?'active':'' ?>"
               data-voice="profile">
                <img src="<?= $navUser['avatar'] ? 'uploads/avatars/'.$navUser['avatar'] : 'default.png' ?>">
                <?= htmlspecialchars($navUser['name']) ?>
                <span id="profileArrow">▼</span>
            </a>

            <ul class="profile-dropdown">
                <li><a href="profile.php" data-voice="my profile">My Profile</a></li>
                <li><a href="my_orders.php" data-voice="my orders">My Orders</a></li>
                <li><a href="logout.php" data-voice="logout">Logout</a></li>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
</div>

<script>

const menuParent = document.querySelector(".menu-parent");
const menuToggle = document.getElementById("menuToggle");
const menuArrow = document.getElementById("menuArrow");

menuToggle.onclick = e => {
    e.preventDefault();
    menuParent.classList.toggle("open");
    menuArrow.textContent = menuParent.classList.contains("open") ? "▲" : "▼";
};
const profileParent = document.querySelector(".profile-parent");
const profileToggle = document.getElementById("profileToggle");
const profileArrow = document.getElementById("profileArrow");

profileToggle.onclick = e => {
    e.preventDefault();
    profileParent.classList.toggle("open");
    profileArrow.textContent =profileParent.classList.contains("open") ? "▲" : "▼";
};

let recognition;
const voiceToggle = document.getElementById("voiceToggle");


if (localStorage.getItem("customerVoice") === "on") {
    voiceToggle.checked = true;
    startVoice();
}

voiceToggle.onchange = () => {
    if (voiceToggle.checked) {
        localStorage.setItem("customerVoice","on");
        startVoice();
    } else {
        localStorage.setItem("customerVoice","off");
        recognition && recognition.stop();
    }
};

function startVoice(){
    if (!('webkitSpeechRecognition' in window)) return;

    recognition = new webkitSpeechRecognition();
    recognition.lang = "en-US";
    recognition.continuous = true;

    recognition.onresult = e => {
        const cmd = e.results[e.results.length-1][0].transcript.toLowerCase();

        if (cmd.includes("menu")) menuParent.classList.add("open");
        if (cmd.includes("profile")) document.querySelector(".profile-parent")?.classList.add("open");

        document.querySelectorAll("[data-voice]").forEach(el=>{
            if (cmd.includes(el.dataset.voice)) {
                window.location.href = el.href;
            }
        });
    };

    recognition.onend = () => {
        if (localStorage.getItem("customerVoice") === "on") recognition.start();
    };

    recognition.start();
}
</script>
